<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\Userstable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\AuditLogService;

class ShortCutLinksController extends Controller
{
    public function index(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_shortcut_links')) {
            abort(403, 'You do not have permission to manage shortcut links.');
        }
        
        $query = Link::with('user');
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('posted_by', $request->user_id);
        }
        
        $links = $query->latest()->get();
        return view('adminpages.shortcutlinks.shortcutlink', compact('links'));
    }

    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_shortcut_links')) {
            abort(403, 'You do not have permission to manage shortcut links.');
        }
        
        return view('adminpages.shortcutlinks.create');
    }

    public function store(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_shortcut_links')) {
            abort(403, 'You do not have permission to manage shortcut links.');
        }
        
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'shortcut_links');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $link = null;

        if ($validatedData['link_type'] === 'link') {
            $link = $validatedData['link'];

            // Custom validation for unique combination of link_name and link
            $existingLink = Link::where('link_name', $validatedData['link_name'])
                               ->where('link', $link)
                               ->where('is_file', false)
                               ->first();

            if ($existingLink) {
                return redirect()->back()
                    ->withErrors(['link' => 'A shortcut link with this name and URL already exists.'])
                    ->withInput();
            }

            $createdLink = Link::create([
                'link_name' => $validatedData['link_name'],
                'link' => $link,
                'is_file' => false,
                'posted_by' => Auth::id(),
            ]);
        } else {
            // file upload
            $file = $request->file('file');

            // Store the file in the public disk storage folder, e.g. storage/app/public/files
            $path = $file->store('files', 'public');

            $createdLink = Link::create([
                'link_name' => $validatedData['link_name'],
                'link' => $path,
                'is_file' => true,
                'posted_by' => Auth::id(),
            ]);
        }

        // Audit log
        AuditLogService::log(
            'create',
            'ShortcutLink',
            $createdLink->id,
            null,
            ['link_name' => $createdLink->link_name, 'is_file' => $createdLink->is_file]
        );

        return redirect()->route('shortcut-links.index')
                         ->with('success', 'Link created successfully.');
    }

    public function show($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_shortcut_links')) {
            abort(403, 'You do not have permission to manage shortcut links.');
        }
        
        $link = Link::with('user')->findOrFail($id);
        
        // Audit log for viewing
        AuditLogService::log(
            'view',
            'ShortcutLink',
            $link->id,
            null,
            ['link_name' => $link->link_name]
        );
        
        return view('adminpages.shortcutlinks.show', compact('link'));
    }

    public function edit($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_shortcut_links')) {
            abort(403, 'You do not have permission to manage shortcut links.');
        }
        
        $link = Link::findOrFail($id);
        return view('adminpages.shortcutlinks.edit', compact('link'));
    }

    public function update(Request $request, $id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_shortcut_links')) {
            abort(403, 'You do not have permission to manage shortcut links.');
        }
        
        $link = Link::findOrFail($id);

        // Store old values for audit log
        $oldValues = [
            'link_name' => $link->link_name,
            'is_file' => $link->is_file
        ];

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'shortcut_links_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        if ($validatedData['link_type'] === 'link') {

            // Custom validation for unique combination of link_name and link (excluding current record)
            $existingLink = Link::where('link_name', $validatedData['link_name'])
                               ->where('link', $validatedData['link'])
                               ->where('is_file', false)
                               ->where('id', '!=', $id)
                               ->first();

            if ($existingLink) {
                return redirect()->back()
                    ->withErrors(['link' => 'A shortcut link with this name and URL already exists.'])
                    ->withInput();
            }

            // If previously was a file, delete the old file
            if ($link->is_file && Storage::disk('public')->exists($link->link)) {
                Storage::disk('public')->delete($link->link);
            }

            $link->update([
                'link_name' => $validatedData['link_name'],
                'link' => $validatedData['link'],
                'is_file' => false,
            ]);
        } else {
            // File upload (optional, if a new file is uploaded)
            $request->validate([
                'file' => 'nullable|file|max:102400',
            ]);

            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($link->is_file && Storage::disk('public')->exists($link->link)) {
                    Storage::disk('public')->delete($link->link);
                }

                $file = $request->file('file');
                $path = $file->store('files', 'public');

                $link->update([
                    'link_name' => $validatedData['link_name'],
                    'link' => $path,
                    'is_file' => true,
                ]);
            } else {
                // No new file uploaded, keep old file link and just update name
                $link->update([
                    'link_name' => $validatedData['link_name'],
                ]);
            }
        }

        // Audit log
        AuditLogService::log(
            'update',
            'ShortcutLink',
            $link->id,
            $oldValues,
            ['link_name' => $link->link_name, 'is_file' => $link->is_file]
        );

        return redirect()->route('shortcut-links.index')
                         ->with('success', 'Link updated successfully.');
    }

    public function destroy($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_shortcut_links')) {
            abort(403, 'You do not have permission to manage shortcut links.');
        }
        
        $link = Link::findOrFail($id);

        // Audit log before deletion
        AuditLogService::log(
            'delete',
            'ShortcutLink',
            $link->id,
            ['link_name' => $link->link_name, 'is_file' => $link->is_file],
            null
        );

        // Delete file if applicable
        if ($link->is_file && Storage::disk('public')->exists($link->link)) {
            Storage::disk('public')->delete($link->link);
        }

        $link->delete();

        return redirect()->route('shortcut-links.index')
                         ->with('success', 'Link deleted successfully.');
    }
}

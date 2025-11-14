<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShortCutLinksController extends Controller
{
    public function index()
    {
        $links = Link::with('user')->latest()->get();
        return view('adminpages.shortcutlinks.shortcutlink', compact('links'));
    }

    public function create()
    {
        return view('adminpages.shortcutlinks.create');
    }

    public function store(Request $request)
    {
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
            $existingLink = Link::where('link_name', $request->link_name)
                               ->where('link', $link)
                               ->where('is_file', false)
                               ->first();

            if ($existingLink) {
                return redirect()->back()
                    ->withErrors(['link' => 'A shortcut link with this name and URL already exists.'])
                    ->withInput();
            }

            Link::create([
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

            Link::create([
                'link_name' => $validatedData['link_name'],
                'link' => $path,
                'is_file' => true,
                'posted_by' => Auth::id(),
            ]);
        }

        return redirect()->route('shortcut-links.index')
                         ->with('success', 'Link created successfully.');
    }

    public function show($id)
    {
        $link = Link::with('user')->findOrFail($id);
        return view('adminpages.shortcutlinks.show', compact('link'));
    }

    public function edit($id)
    {
        $link = Link::findOrFail($id);
        return view('adminpages.shortcutlinks.edit', compact('link'));
    }

    public function update(Request $request, $id)
    {
        $link = Link::findOrFail($id);

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
                'link_name' => $request->link_name,
                'link' => $request->link,
                'is_file' => false,
            ]);
        } else {
            // File upload (optional, if a new file is uploaded)
            $request->validate([
                'file' => 'nullable|file|max:10240',
            ]);

            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($link->is_file && Storage::disk('public')->exists($link->link)) {
                    Storage::disk('public')->delete($link->link);
                }

                $file = $request->file('file');
                $path = $file->store('files', 'public');

                $link->update([
                    'link_name' => $request->link_name,
                    'link' => $path,
                    'is_file' => true,
                ]);
            } else {
                // No new file uploaded, keep old file link and just update name
                $link->update([
                    'link_name' => $request->link_name,
                ]);
            }
        }

        return redirect()->route('shortcut-links.index')
                         ->with('success', 'Link updated successfully.');
    }

    public function destroy($id)
    {
        $link = Link::findOrFail($id);

        // Delete file if applicable
        if ($link->is_file && Storage::disk('public')->exists($link->link)) {
            Storage::disk('public')->delete($link->link);
        }

        $link->delete();

        return redirect()->route('shortcut-links.index')
                         ->with('success', 'Link deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Userstable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\AuditLogService;

class PartnerManageController extends Controller
{
    /**
     * Display a listing of the partners.
     */
    public function index(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_partners')) {
            abort(403, 'You do not have permission to manage partners.');
        }
        
        $query = Partner::with('user');
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('posted_by', $request->user_id);
        }
        
        $partners = $query->latest()->paginate(10)->appends($request->all());
        return view('adminpages.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new partner.
     */
    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_partners')) {
            abort(403, 'You do not have permission to manage partners.');
        }
        
        return view('adminpages.partners.create');
    }

    /**
     * Store a newly created partner in storage.
     */
    public function store(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_partners')) {
            abort(403, 'You do not have permission to manage partners.');
        }
        
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'partners');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Custom validation for unique combination
        $existingPartner = Partner::where('name', $validatedData['name'])
            ->where('acronym_name', $validatedData['acronym_name'])
            ->where('link', $validatedData['link'])
            ->first();

        if ($existingPartner) {
            return redirect()->back()
                ->withErrors(['name' => 'A strategic partner with this name, acronym, and link combination already exists.'])
                ->withInput();
        }

        $data = [
            'name' => $validatedData['name'],
            'acronym_name' => $validatedData['acronym_name'],
            'link' => $validatedData['link'],
            'posted_by' => Auth::id()
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validatedData['name']) . '.' . $image->getClientOriginalExtension();
            
            // Store in partner_image folder
            $image->storeAs('partner_image', $imageName, 'public');
            $data['image_path'] = $imageName;
        }

        $partner = Partner::create($data);

        // Audit log
        AuditLogService::log(
            'create',
            'Partner',
            $partner->id,
            null,
            ['name' => $partner->name, 'acronym_name' => $partner->acronym_name]
        );

        return redirect()->route('admin.partners.index')
                        ->with('success', 'Partner created successfully.');
    }

    /**
     * Display the specified partner.
     */
    public function show(Partner $partner)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_partners')) {
            abort(403, 'You do not have permission to manage partners.');
        }
        
        // Audit log for viewing
        AuditLogService::log(
            'view',
            'Partner',
            $partner->id,
            null,
            ['name' => $partner->name]
        );
        
        return view('adminpages.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified partner.
     */
    public function edit(Partner $partner)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_partners')) {
            abort(403, 'You do not have permission to manage partners.');
        }
        
        return view('adminpages.partners.edit', compact('partner'));
    }

    /**
     * Update the specified partner in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_partners')) {
            abort(403, 'You do not have permission to manage partners.');
        }
        
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'partners_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Custom validation for unique combination (excluding current partner)
        $existingPartner = Partner::where('name', $validatedData['name'])
            ->where('acronym_name', $validatedData['acronym_name'])
            ->where('link', $validatedData['link'])
            ->where('id', '!=', $partner->id)
            ->first();

        if ($existingPartner) {
            return redirect()->back()
                ->withErrors(['name' => 'A strategic partner with this name, acronym, and link combination already exists.'])
                ->withInput();
        }

        // Store old values for audit log
        $oldValues = [
            'name' => $partner->name,
            'acronym_name' => $partner->acronym_name,
            'link' => $partner->link
        ];

        $data = [
            'name' => $validatedData['name'],
            'acronym_name' => $validatedData['acronym_name'],
            'link' => $validatedData['link']
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($partner->image_path) {
                Storage::disk('public')->delete('partner_image/' . $partner->image_path);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validatedData['name']) . '.' . $image->getClientOriginalExtension();
            
            // Store in partner_image folder
            $image->storeAs('partner_image', $imageName, 'public');
            $data['image_path'] = $imageName;
        }

        $partner->update($data);

        // Audit log
        AuditLogService::log(
            'update',
            'Partner',
            $partner->id,
            $oldValues,
            ['name' => $partner->name, 'acronym_name' => $partner->acronym_name, 'link' => $partner->link]
        );

        return redirect()->route('admin.partners.index')
                        ->with('success', 'Partner updated successfully.');
    }

    /**
     * Remove the specified partner from storage.
     */
    public function destroy(Partner $partner)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_partners')) {
            abort(403, 'You do not have permission to manage partners.');
        }
        
        // Audit log before deletion
        AuditLogService::log(
            'delete',
            'Partner',
            $partner->id,
            ['name' => $partner->name, 'acronym_name' => $partner->acronym_name],
            null
        );

        // Delete image if exists
        if ($partner->image_path) {
            Storage::disk('public')->delete('partner_image/' . $partner->image_path);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')
                        ->with('success', 'Partner deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerManageController extends Controller
{
    /**
     * Display a listing of the partners.
     */
    public function index()
    {
        $partners = Partner::with('user')->latest()->paginate(10);
        return view('adminpages.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new partner.
     */
    public function create()
    {
        return view('adminpages.partners.create');
    }

    /**
     * Store a newly created partner in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'partners');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Custom validation for unique combination
        $existingPartner = Partner::where('name', $request->name)
            ->where('acronym_name', $request->acronym_name)
            ->where('link', $request->link)
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
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            
            // Store in partner_image folder
            $image->storeAs('partner_image', $imageName, 'public');
            $data['image_path'] = $imageName;
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')
                        ->with('success', 'Partner created successfully.');
    }

    /**
     * Display the specified partner.
     */
    public function show(Partner $partner)
    {
        return view('adminpages.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified partner.
     */
    public function edit(Partner $partner)
    {
        return view('adminpages.partners.edit', compact('partner'));
    }

    /**
     * Update the specified partner in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'partners_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Custom validation for unique combination (excluding current partner)
        $existingPartner = Partner::where('name', $request->name)
            ->where('acronym_name', $request->acronym_name)
            ->where('link', $request->link)
            ->where('id', '!=', $partner->id)
            ->first();

        if ($existingPartner) {
            return redirect()->back()
                ->withErrors(['name' => 'A strategic partner with this name, acronym, and link combination already exists.'])
                ->withInput();
        }

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
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            
            // Store in partner_image folder
            $image->storeAs('partner_image', $imageName, 'public');
            $data['image_path'] = $imageName;
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')
                        ->with('success', 'Partner updated successfully.');
    }

    /**
     * Remove the specified partner from storage.
     */
    public function destroy(Partner $partner)
    {
        // Delete image if exists
        if ($partner->image_path) {
            Storage::disk('public')->delete('partner_image/' . $partner->image_path);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')
                        ->with('success', 'Partner deleted successfully.');
    }
}
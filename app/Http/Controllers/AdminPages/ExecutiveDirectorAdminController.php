<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\ExecutiveDirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExecutiveDirectorAdminController extends Controller
{
    /**
     * Display a listing of the executive directors.
     */
    public function index()
    {
        $executiveDirectors = ExecutiveDirector::orderBy('created_at', 'desc')->paginate(10);

        return view('adminpages.executivedirector.index', compact('executiveDirectors'));
    }

    /**
     * Show the form for creating a new executive director.
     */
    public function create()
    {
        return view('adminpages.executivedirector.create');
    }

    /**
     * Store a newly created executive director in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name'         => 'required|string|max:255',
            'imagepath'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
            'start_year'        => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'end_year'          => 'nullable|integer|min:1900|max:' . (date('Y') + 10) . '|gt:start_year',
            'term_description'  => 'nullable|string',
            'status'            => 'required|in:Active,Former',
        ]);

        $imagePath = null;

        if ($request->hasFile('imagepath')) {
            $image     = $request->file('imagepath');
            $imageName = time() . '_' . Str::slug($request->full_name) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('executivedirectors', $imageName, 'public');
        }

        ExecutiveDirector::create([
            'full_name'         => $request->full_name,
            'imagepath'         => $imagePath,
            'start_year'        => $request->start_year,
            'end_year'          => $request->end_year,
            'term_description'  => $request->term_description,
            'status'            => $request->status,
            'posted_by'         => auth()->user()->name ?? auth()->user()->email,
        ]);

        return redirect()
            ->route('admin.executive-directors.index')
            ->with('success', 'Executive Director added successfully!');
    }

    /**
     * Display the specified executive director.
     */
    public function show($id)
    {
        $executiveDirector = ExecutiveDirector::findOrFail($id);

        return view('adminpages.executivedirector.show', compact('executiveDirector'));
    }

    /**
     * Show the form for editing the specified executive director.
     */
    public function edit($id)
    {
        $executiveDirector = ExecutiveDirector::findOrFail($id);

        return view('adminpages.executivedirector.edit', compact('executiveDirector'));
    }

    /**
     * Update the specified executive director in storage.
     */
    public function update(Request $request, $id)
    {
        $executiveDirector = ExecutiveDirector::findOrFail($id);

        $request->validate([
            'full_name'         => 'required|string|max:255',
            'imagepath'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
            'start_year'        => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'end_year'          => 'nullable|integer|min:1900|max:' . (date('Y') + 10) . '|gt:start_year',
            'term_description'  => 'nullable|string',
            'status'            => 'required|in:Active,Former',
        ]);

        $imagePath = $executiveDirector->imagepath;

        if ($request->hasFile('imagepath')) {
            // Delete old image if exists
            if ($executiveDirector->imagepath && Storage::disk('public')->exists($executiveDirector->imagepath)) {
                Storage::disk('public')->delete($executiveDirector->imagepath);
            }

            $image     = $request->file('imagepath');
            $imageName = time() . '_' . Str::slug($request->full_name) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('executivedirectors', $imageName, 'public');
        }

        $executiveDirector->update([
            'full_name'         => $request->full_name,
            'imagepath'         => $imagePath,
            'start_year'        => $request->start_year,
            'end_year'          => $request->end_year,
            'term_description'  => $request->term_description,
            'status'            => $request->status,
            'posted_by'         => auth()->user()->name ?? auth()->user()->email,
        ]);

        return redirect()
            ->route('admin.executive-directors.index')
            ->with('success', 'Executive Director updated successfully!');
    }

    /**
     * Remove the specified executive director from storage.
     */
    public function destroy($id)
    {
        $executiveDirector = ExecutiveDirector::findOrFail($id);

        // Delete image file if exists
        if ($executiveDirector->imagepath && Storage::disk('public')->exists($executiveDirector->imagepath)) {
            Storage::disk('public')->delete($executiveDirector->imagepath);
        }

        $executiveDirector->delete();

        return redirect()
            ->route('admin.executive-directors.index')
            ->with('success', 'Executive Director deleted successfully!');
    }
}

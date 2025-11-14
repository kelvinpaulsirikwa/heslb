<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\BoardOfDirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BoardOfDirectorController extends Controller
{
    
    public function index()
    {
        $boardMembers = BoardOfDirector::orderBy('created_at', 'desc')->paginate(10);

        return view('adminpages.boardofdirectors.index', compact('boardMembers'));
    }

   
    public function create()
    {
        return view('adminpages.boardofdirectors.create');
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('boardofdirectors', $imageName, 'public');
        }

        BoardOfDirector::create([
            'image'       => $imagePath,
            'name'        => $request->name,
            'description' => $request->description,
            'posted_by'   => auth()->user()->name ?? auth()->user()->email,
        ]);

        return redirect()
            ->route('admin.board-of-directors.index')
            ->with('success', 'Board member added successfully!');
    }

   
    public function show($id)
    {
        $boardMember = BoardOfDirector::findOrFail($id);

        return view('adminpages.boardofdirectors.show', compact('boardMember'));
    }

    
    public function edit($id)
    {
        $boardMember = BoardOfDirector::findOrFail($id);

        return view('adminpages.boardofdirectors.edit', compact('boardMember'));
    }

  
    public function update(Request $request, $id)
    {
        $boardMember = BoardOfDirector::findOrFail($id);

        $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $imagePath = $boardMember->image;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($boardMember->image && Storage::disk('public')->exists($boardMember->image)) {
                Storage::disk('public')->delete($boardMember->image);
            }

            $image     = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('boardofdirectors', $imageName, 'public');
        }

        $boardMember->update([
            'image'       => $imagePath,
            'name'        => $request->name,
            'description' => $request->description,
            'posted_by'   => auth()->user()->name ?? auth()->user()->email,
        ]);

        return redirect()
            ->route('admin.board-of-directors.index')
            ->with('success', 'Board member updated successfully!');
    }

    public function destroy($id)
    {
        $boardMember = BoardOfDirector::findOrFail($id);

        // Delete image file if exists
        if ($boardMember->image && Storage::disk('public')->exists($boardMember->image)) {
            Storage::disk('public')->delete($boardMember->image);
        }

        $boardMember->delete();

        return redirect()
            ->route('admin.board-of-directors.index')
            ->with('success', 'Board member deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScholarshipAdminController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::orderByDesc('published_at')->orderBy('title')->paginate(15);
        return view('adminpages.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        return view('adminpages.scholarships.create');
    }

    public function store(Request $request)
    {
        // Check for recent duplicate submissions (within last 30 seconds)
        $recentSubmission = Scholarship::where('posted_by', auth()->id())
            ->where('title', $request->input('title'))
            ->where('created_at', '>=', now()->subSeconds(30))
            ->first();

        if ($recentSubmission) {
            return redirect()->route('admin.scholarships.index')
                ->with('warning', 'A scholarship with this title was recently created. Please wait a moment before creating another.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'donor_organization' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'eligible_applicants' => 'nullable|string',
            'fields_of_study' => 'nullable|string',
            'level_of_study' => 'nullable|array',
            'level_of_study.*' => 'string|in:certificate,diploma,undergraduate,masters,phd,postdoctoral',
            'benefits_summary' => 'nullable|string',
            'external_link' => 'nullable|url|max:500',
            'content_html' => 'nullable|string',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $data['slug'] = Scholarship::generateUniqueSlug($data['title']);
        $data['is_active'] = true;
        $data['published_at'] = now();
        $data['posted_by'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('scholarships', 'public');
            // mirror to public/images/storage for this project setup
            $publicTarget = public_path('images/storage/' . $path);
            @mkdir(dirname($publicTarget), 0755, true);
            @copy(storage_path('app/public/' . $path), $publicTarget);
            $data['cover_image'] = $path;
        }

        Scholarship::create($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship created successfully');
    }

    public function edit(Scholarship $scholarship)
    {
        return view('adminpages.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'donor_organization' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'eligible_applicants' => 'nullable|string',
            'fields_of_study' => 'nullable|string',
            'level_of_study' => 'nullable|array',
            'level_of_study.*' => 'string|in:certificate,diploma,undergraduate,masters,phd,postdoctoral',
            'benefits_summary' => 'nullable|string',
            'external_link' => 'nullable|url|max:500',
            'content_html' => 'nullable|string',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $data['slug'] = Scholarship::generateUniqueSlug($data['title'], $scholarship->id);
        // Keep existing is_active and published_at (no UI inputs)

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('scholarships', 'public');
            $publicTarget = public_path('images/storage/' . $path);
            @mkdir(dirname($publicTarget), 0755, true);
            @copy(storage_path('app/public/' . $path), $publicTarget);
            $data['cover_image'] = $path;
        }

        $scholarship->update($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship updated successfully');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();
        return back()->with('success', 'Scholarship deleted');
    }
}



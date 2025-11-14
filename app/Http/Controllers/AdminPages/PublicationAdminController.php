<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PublicationAdminController extends Controller
{
    // ===================== PUBLICATIONS CRUD =====================

    /**
     * Display publications index
     */
    public function index()
    {
        $publications = Publication::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('adminpages.publication.publication', compact('publications', 'categories'));
    }

    /**
     * Show the form for creating a new publication
     */
    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('adminpages.publication.create', compact('categories'));
    }

    /**
     * Store a newly created publication
     */
    public function store(Request $request)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'publications');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        try {
            // Handle file upload
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Store file in public/downloads directory
            $filePath = $file->storeAs('downloads', $fileName, 'public');
            $publicPath = '/images/storage/' . $filePath;

            // Get file size in KB
            $fileSize = round($file->getSize() / 1024);

            // Get category to check if it's guidelines
            $category = Category::find($validatedData['category_id']);
            $isGuidelineCategory = $category && $category->slug === 'guidelines';

            // Create publication
            Publication::create([
                'title' => $validatedData['title'],
                'category_id' => $validatedData['category_id'],
                'file_name' => $fileName,
                'file_path' => $publicPath,
                'file_type' => strtoupper($file->getClientOriginalExtension()),
                'file_size' => $fileSize,
                'description' => $validatedData['description'],
                'publication_date' => now(),
                'posted_by' => Auth::id(),
                'is_active' => $request->boolean('is_active', true),
                'is_direct_guideline' => $isGuidelineCategory ? $request->boolean('is_direct_guideline', false) : false
            ]);

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create publication: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified publication
     */
    public function show(Publication $publication)
    {
        $publication->load('category');
        return view('adminpages.publication.show', compact('publication'));
    }

    /**
     * Show the form for editing the specified publication
     */
    public function edit(Publication $publication)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('adminpages.publication.edit', compact('publication', 'categories'));
    }

    /**
     * Update the specified publication
     */
    public function update(Request $request, Publication $publication)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'publications_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        try {
            // Get category to check if it's guidelines
            $category = Category::find($validatedData['category_id']);
            $isGuidelineCategory = $category && $category->slug === 'guidelines';

            $updateData = [
                'title' => $validatedData['title'],
                'category_id' => $validatedData['category_id'],
                'description' => $validatedData['description'],
                'publication_date' => $validatedData['publication_date'],
                'version' => $validatedData['version'] ?? null,
                'posted_by' => Auth::id(),
                'is_active' => $request->boolean('is_active', true),
                'is_direct_guideline' => $isGuidelineCategory ? $request->boolean('is_direct_guideline', false) : false
            ];

            // Handle file upload if new file is provided
            if ($request->hasFile('file')) {
                // Delete old file
                if ($publication->file_path && Storage::disk('public')->exists(str_replace('/storage/', '', $publication->file_path))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $publication->file_path));
                }

                // Upload new file
                $file = $request->file('file');
                $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                $filePath = $file->storeAs('downloads', $fileName, 'public');
                $publicPath = '/images/storage/' . $filePath;
                $fileSize = round($file->getSize() / 1024);

                $updateData = array_merge($updateData, [
                    'file_name' => $fileName,
                    'file_path' => $publicPath,
                    'file_type' => strtoupper($file->getClientOriginalExtension()),
                    'file_size' => $fileSize
                ]);
            }

            $publication->update($updateData);

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update publication: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified publication
     */
    public function destroy(Publication $publication)
    {
        try {
            // Delete associated file
            if ($publication->file_path && Storage::disk('public')->exists(str_replace('/storage/', '', $publication->file_path))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $publication->file_path));
            }

            $publication->delete();

            return redirect()->route('admin.publications.index')
                ->with('success', 'Publication deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete publication: ' . $e->getMessage()]);
        }
    }

    // ===================== CATEGORIES CRUD =====================

    /**
     * Display categories index
     */
    public function categoriesIndex()
    {
        $categories = Category::withCount('publications')
            ->orderBy('display_order')
            ->paginate(10);

        return view('adminpages.publication.categories', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function categoriesCreate()
    {
        return view('adminpages.publication.createcategory');
    }

    /**
     * Store a newly created category
     */
    public function categoriesStore(Request $request)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'categories');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        try {
            Category::create([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'],
                'display_order' => $validatedData['display_order'] ?? 0,
                'posted_by' => Auth::id(),
                'is_active' => $request->boolean('is_active', true)
            ]);

            return redirect()->route('admin.publications.categories.index')
                ->with('success', 'Category created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create category: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified category
     */
    public function categoriesEdit(Category $category)
    {
        return view('adminpages.publication.editcategory', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function categoriesUpdate(Request $request, Category $category)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'categories_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        try {
            $category->update([
                'name' => $validatedData['name'],
                'slug' => Str::slug($validatedData['name']),
                'description' => $validatedData['description'],
                'display_order' => $validatedData['display_order'] ?? 0,
                'posted_by' => Auth::id(),
                'is_active' => $request->boolean('is_active', true)
            ]);

            return redirect()->route('admin.publications.categories.index')
                ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update category: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified category (with publication check)
     */
    public function categoriesDestroy(Category $category)
    {
        try {
            // Check if category has publications
            $publicationCount = $category->publications()->count();
            
            if ($publicationCount > 0) {
                return redirect()->back()
                    ->withErrors(['error' => "Cannot delete category '{$category->name}'. It has {$publicationCount} publication(s). Please move or delete the publications first."]);
            }

            $category->delete();

            return redirect()->route('admin.publications.categories.index')
                ->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete category: ' . $e->getMessage()]);
        }
    }

    // ===================== UTILITY METHODS =====================

    /**
     * Toggle publication status
     */
    public function toggleStatus(Publication $publication)
    {
        try {
            $publication->update(['is_active' => !$publication->is_active]);
            
            $status = $publication->is_active ? 'activated' : 'deactivated';
            
            return response()->json([
                'success' => true,
                'message' => "Publication {$status} successfully!",
                'status' => $publication->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle direct guideline status
     */
    public function toggleDirectGuideline(Publication $publication)
    {
        try {
            // Only allow for guidelines category
            if (!$publication->isGuidelineCategory()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only publications in Guidelines category can be set as direct guidelines.'
                ], 400);
            }

            // Only allow for active publications
            if (!$publication->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only active publications can be set as direct guidelines.'
                ], 400);
            }

            $publication->update(['is_direct_guideline' => !$publication->is_direct_guideline]);
            
            $status = $publication->is_direct_guideline ? 'set as direct guideline' : 'removed from direct guidelines';
            
            return response()->json([
                'success' => true,
                'message' => "Publication {$status} successfully!",
                'is_direct_guideline' => $publication->is_direct_guideline
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle direct guideline status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle category status
     */
    public function toggleCategoryStatus(Category $category)
    {
        try {
            $category->update(['is_active' => !$category->is_active]);
            
            $status = $category->is_active ? 'activated' : 'deactivated';
            
            return response()->json([
                'success' => true,
                'message' => "Category {$status} successfully!",
                'status' => $category->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete publications
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:publications,id'
        ]);

        try {
            $publications = Publication::whereIn('id', $request->ids)->get();
            
            foreach ($publications as $publication) {
                // Delete associated file
                if ($publication->file_path && Storage::disk('public')->exists(str_replace('/storage/', '', $publication->file_path))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $publication->file_path));
                }
                
                $publication->delete();
            }

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' publications deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete publications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update category display order
     */
    public function updateCategoryOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->categories as $categoryData) {
                Category::where('id', $categoryData['id'])
                    ->update(['display_order' => $categoryData['order'],
                                'posted_by' => Auth::id(),

                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category order updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search publications
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category_id');
        
        $publications = Publication::with('category')
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->all());

        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return view('adminpages.publication.publication', compact('publications', 'categories', 'query', 'categoryId'));
    }
}
<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function show($choice = null)
    {
        // Convert choice to slug format
        $slug = strtolower(str_replace(' ', '-', $choice ?? ''));
        
        // Find the category by slug
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->withCount(['publications' => function ($query) {
                $query->where('is_active', true);
            }])
            ->first();
        
        if (!$category) {
return view('publications.partial.nopublications');        }
        
        // Get all active publications for this category
        $publications = Publication::where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('publication_date', 'desc')
            ->orderBy('title', 'asc')
            ->get();
        
        // Transform publications to match the original format
        $items = $publications->map(function ($publication) {
            return [
                'id' => $publication->id,
                'title' => $publication->title,
                'link' => $publication->file_path ?: '/downloads/' . $publication->file_name,
                'description' => $publication->description,
                'version' => $publication->version,
                'publication_date' => $publication->publication_date?->format('Y-m-d'),
                'formatted_date' => $publication->publication_date?->format('F j, Y'),
                'file_type' => $publication->file_type,
                'file_size' => $publication->formatted_file_size,
                'download_count' => $publication->download_count,
            ];
        })->toArray();
        
        // Get all categories for sidebar
        $sidebarCategories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->withCount(['publications' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();
        
        // Set page title from category name
        $pageTitle = strlen($category->name) > 50 ? substr($category->name, 0, 47) . '...' : $category->name;
        
        return view('publications.partial.resuablepartial', compact('items', 'pageTitle', 'category', 'sidebarCategories'));
    }
    
    /**
     * Display all categories with their publications count
     */
    public function index()
    {
        // Get all categories for sidebar
        $sidebarCategories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->withCount(['publications' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();
        
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->withCount(['publications' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();
        
        $pageTitle = 'Publications';
        
        return view('publications.index', compact('categories', 'sidebarCategories', 'pageTitle'));
    }
    
    /**
     * Download a publication file
     */
    public function download($id)
    {
        $publication = Publication::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Increment download count
        $publication->increment('download_count');
        
        // Get file path
        $filePath = $publication->file_path 
            ? public_path($publication->file_path) 
            : public_path('/downloads/' . $publication->file_name);
        
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        // Return file download
        return response()->download($filePath, $publication->file_name);
    }
    
    /**
     * Search publications
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('publications.index');
        }
        
        $publications = Publication::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('version', 'like', "%{$query}%");
            })
            ->with('category')
            ->orderBy('publication_date', 'desc')
            ->paginate(15);
        
        return view('publications.search', compact('publications', 'query'));
    }
    
    /**
     * Show all publications with automatic first category selection
     */
    public function allpublication()
    {
        // Get all categories with their publications
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->withCount(['publications' => function ($query) {
                $query->where('is_active', true);
            }])
            ->with(['publications' => function ($query) {
                $query->where('is_active', true)
                      ->orderBy('publication_date', 'desc')
                      ->orderBy('title', 'asc');
            }])
            ->get();
        
        if ($categories->isEmpty()) {

return view('publications.partial.nopublications');
        }
        
        // Get the first category with publications (automation choosing one)
        $selectedCategory = $categories->first(function ($category) {
            return $category->publications->isNotEmpty();
        });
        
        // If no category has publications, just use the first category
        if (!$selectedCategory) {
            $selectedCategory = $categories->first();
        }
        
        // Transform publications for the selected category to match original format
        $items = $selectedCategory->publications->map(function ($publication) {
            return [
                'id' => $publication->id,
                'title' => $publication->title,
                'link' => $publication->file_path ?: '/downloads/' . $publication->file_name,
                'description' => $publication->description,
                'version' => $publication->version,
                'publication_date' => $publication->publication_date?->format('Y-m-d'),
                'formatted_date' => $publication->publication_date?->format('F j, Y'),
                'file_type' => $publication->file_type,
                'file_size' => $publication->formatted_file_size,
                'download_count' => $publication->download_count,
            ];
        })->toArray();
        
        // Also create the full lists array for JavaScript/dynamic loading if needed
        $lists = [];
        foreach ($categories as $category) {
            $lists[$category->slug] = $category->publications->map(function ($publication) {
                return [
                    'id' => $publication->id,
                    'title' => $publication->title,
                    'link' => $publication->file_path ?: '/downloads/' . $publication->file_name,
                    'description' => $publication->description,
                    'version' => $publication->version,
                    'publication_date' => $publication->publication_date?->format('Y-m-d'),
                    'formatted_date' => $publication->publication_date?->format('F j, Y'),
                    'file_type' => $publication->file_type,
                    'file_size' => $publication->formatted_file_size,
                    'download_count' => $publication->download_count,
                ];
            })->toArray();
        }
        
        // Get sidebar categories
        $sidebarCategories = $categories;
        
        // Set data for the view
        $pageTitle = strlen($selectedCategory->name) > 50 ? substr($selectedCategory->name, 0, 47) . '...' : $selectedCategory->name;
        $category = $selectedCategory;
        
        return view('publications.partial.resuablepartial', compact('items', 'pageTitle', 'category', 'sidebarCategories', 'lists', 'categories'));
    }
    
    /**
     * Alternative: Show all publications with specific priority selection
     */
    public function allpublicationWithPriority()
    {
        // Define priority order for automatic selection
        $priorityCategories = [
            'heslb-act-amendments',
            'national-higher-education-policy',
            'education-policy',
            'pgd-guidelines'
        ];
        
        // Get all categories
        $categories = Category::where('is_active', true)
            ->orderBy('display_order')
            ->withCount(['publications' => function ($query) {
                $query->where('is_active', true);
            }])
            ->with(['publications' => function ($query) {
                $query->where('is_active', true)
                      ->orderBy('publication_date', 'desc')
                      ->orderBy('title', 'asc');
            }])
            ->get();
        
        if ($categories->isEmpty()) {
            abort(404, 'No publication categories found.');
        }
        
        // Try to find a priority category with publications
        $selectedCategory = null;
        foreach ($priorityCategories as $prioritySlug) {
            $selectedCategory = $categories->first(function ($category) use ($prioritySlug) {
                return $category->slug === $prioritySlug && $category->publications->isNotEmpty();
            });
            
            if ($selectedCategory) {
                break;
            }
        }
        
        // If no priority category found, use first category with publications
        if (!$selectedCategory) {
            $selectedCategory = $categories->first(function ($category) {
                return $category->publications->isNotEmpty();
            }) ?: $categories->first();
        }
        
        // Transform publications for display
        $items = $selectedCategory->publications->map(function ($publication) {
            return [
                'id' => $publication->id,
                'title' => $publication->title,
                'link' => $publication->file_path ?: '/downloads/' . $publication->file_name,
                'description' => $publication->description,
                'version' => $publication->version,
                'publication_date' => $publication->publication_date?->format('Y-m-d'),
                'formatted_date' => $publication->publication_date?->format('F j, Y'),
                'file_type' => $publication->file_type,
                'file_size' => $publication->formatted_file_size,
                'download_count' => $publication->download_count,
            ];
        })->toArray();
        
        $pageTitle = strlen($selectedCategory->name) > 50 ? substr($selectedCategory->name, 0, 47) . '...' : $selectedCategory->name;
        $category = $selectedCategory;
        $sidebarCategories = $categories;
        
        return view('publications.partial.resuablepartial', compact('items', 'pageTitle', 'category', 'sidebarCategories'));
    }
}
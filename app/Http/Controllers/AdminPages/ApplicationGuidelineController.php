<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\ApplicationGuideline;
use App\Models\Publication;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicationGuidelineController extends Controller
{
    
    public function index()
    {
        $guidelines = ApplicationGuideline::with(['creator', 'updater', 'publication'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adminpages.application-guidelines.index', compact('guidelines'));
    }

     
    public function create()
    {
        // Get guidelines category and its publications (including direct guidelines)
        $guidelinesCategory = Category::where('slug', 'guidelines')->first();
        $publications = $guidelinesCategory ? $guidelinesCategory->activePublications()->orderBy('title')->get() : collect();
        
        // Get direct guidelines separately for special display
        $directGuidelines = $publications->where('is_direct_guideline', true);
        $regularPublications = $publications->where('is_direct_guideline', false);
        
        return view('adminpages.application-guidelines.create', compact('publications', 'directGuidelines', 'regularPublications'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'publication_id' => 'required|exists:publications,id',
            'version' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        // Get the selected publication
        $publication = Publication::findOrFail($request->publication_id);

        // Create new guideline (automatically set as current, but allow multiple current guidelines)
        $guideline = ApplicationGuideline::create([
            'title' => $request->title,
            'academic_year' => $request->academic_year,
            'publication_id' => $request->publication_id,
            'version' => $request->version,
            'description' => $request->description,
            // Store the original file path information from the publication
            'file_path' => $publication->file_path,
            'file_name' => $publication->file_name,
            'file_original_name' => $publication->title,
            'file_size' => $publication->file_size,
            'file_type' => $publication->file_type,
            'is_current' => true, // Always set new guidelines as current
            'sort_order' => $request->sort_order ?? 0,
            'published_at' => $request->published_at,
            'expires_at' => $request->expires_at,
            'tags' => $request->tags,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('admin.application-guidelines.index')
            ->with('success', 'Application guideline created successfully and set as current using publication: ' . $publication->title);
    }

     
    public function show($id)
    {
        $guideline = ApplicationGuideline::with(['creator', 'updater'])->findOrFail($id);
        return view('adminpages.application-guidelines.show', compact('guideline'));
    }

    
    public function edit($id)
    {
        $guideline = ApplicationGuideline::findOrFail($id);
        
        // Prevent editing current guidelines
        if ($guideline->is_current) {
            return redirect()->route('admin.application-guidelines.index')
                ->with('error', 'Current guidelines cannot be edited. Please set another guideline as current first.');
        }

        // Get guidelines category and its publications (including direct guidelines)
        $guidelinesCategory = Category::where('slug', 'guidelines')->first();
        $publications = $guidelinesCategory ? $guidelinesCategory->activePublications()->orderBy('title')->get() : collect();
        
        // Get direct guidelines separately for special display
        $directGuidelines = $publications->where('is_direct_guideline', true);
        $regularPublications = $publications->where('is_direct_guideline', false);
        
        return view('adminpages.application-guidelines.edit', compact('guideline', 'publications', 'directGuidelines', 'regularPublications'));
    }
 
    public function update(Request $request, $id)
    {
        $guideline = ApplicationGuideline::findOrFail($id);

        // Prevent editing current guidelines
        if ($guideline->is_current) {
            return redirect()->route('admin.application-guidelines.index')
                ->with('error', 'Current guidelines cannot be edited. Please set another guideline as current first.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'publication_id' => 'required|exists:publications,id',
            'version' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        // Get the selected publication
        $publication = Publication::findOrFail($request->publication_id);

        $data = [
            'title' => $request->title,
            'academic_year' => $request->academic_year,
            'publication_id' => $request->publication_id,
            'version' => $request->version,
            'description' => $request->description,
            // Store the original file path information from the publication
            'file_path' => $publication->file_path,
            'file_name' => $publication->file_name,
            'file_original_name' => $publication->title,
            'file_size' => $publication->file_size,
            'file_type' => $publication->file_type,
            'sort_order' => $request->sort_order ?? 0,
            'published_at' => $request->published_at,
            'expires_at' => $request->expires_at,
            'tags' => $request->tags,
            'updated_by' => Auth::id()
        ];

        // Note: is_current is not updated - only new guidelines are automatically set as current

        $guideline->update($data);

        return redirect()->route('admin.application-guidelines.index')
            ->with('success', 'Application guideline updated successfully using publication: ' . $publication->title);
    } 
    public function destroy($id)
    {
        try {
            // Find the guideline by ID
            $guideline = ApplicationGuideline::findOrFail($id);
            
            Log::info('Attempting to delete application guideline', [
                'id' => $guideline->id,
                'title' => $guideline->title,
                'is_current' => $guideline->is_current
            ]);

            // Store info for message before deletion
            $isCurrent = $guideline->is_current;
            $guidelineTitle = $guideline->title;
            $guidelineId = $guideline->id;
            
            // Delete file if exists (only if not using publication)
            // When guideline uses a publication, we don't delete the file as it's shared
            if (!$guideline->publication_id && $guideline->file_path && Storage::disk('public')->exists($guideline->file_path)) {
                Storage::disk('public')->delete($guideline->file_path);
                Log::info('Deleted file for guideline', ['file_path' => $guideline->file_path]);
            }

            // Actually delete the guideline record from database
            $deleted = $guideline->delete();

            if (!$deleted) {
                Log::error('Failed to delete application guideline - delete() returned false', ['id' => $guidelineId]);
                return redirect()->route('admin.application-guidelines.index')
                    ->with('error', 'Failed to delete the guideline. Please try again.');
            }

            Log::info('Successfully deleted application guideline', [
                'id' => $guidelineId,
                'title' => $guidelineTitle
            ]);

            $message = 'Application guideline "' . $guidelineTitle . '" deleted successfully.';

            return redirect()->route('admin.application-guidelines.index')
                ->with('success', $message);
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Application guideline not found for deletion', ['id' => $id]);
            return redirect()->route('admin.application-guidelines.index')
                ->with('error', 'Guideline not found.');
                
        } catch (\Exception $e) {
            Log::error('Error deleting application guideline: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.application-guidelines.index')
                ->with('error', 'An error occurred while deleting the guideline: ' . $e->getMessage());
        }
    } 
    public function download(ApplicationGuideline $applicationGuideline)
    {
        $guideline = $applicationGuideline;

        // Get file path from publication or direct file
        $filePath = $guideline->file_path;
        $fileName = $guideline->file_original_name;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download(storage_path('app/public/' . $filePath), $fileName);
    }

    
    public function setCurrent(ApplicationGuideline $applicationGuideline)
    {
        $guideline = $applicationGuideline;

        // Unset all other current guidelines
        ApplicationGuideline::where('is_current', true)->update(['is_current' => false]);

        // Set this one as current
        $guideline->update(['is_current' => true, 'updated_by' => Auth::id()]);

        return redirect()->route('admin.application-guidelines.index')
            ->with('success', 'Application guideline set as current successfully.');
    }
}

<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use App\Models\StoryActionHistory;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersStoriesController extends Controller
{
  
    
    public function showstoryform(){
        // Redirect to contact form with success stories pre-selected
        return redirect()->route('contactus.getusintouch', ['type' => 'success_stories']);
    }
    
    //store 
    public function store(Request $request)
    {
        // Check for recent duplicate submissions (within last 30 seconds)
        $recentSubmission = SuccessStory::where('email', $request->input('email'))
            ->where('title', $request->input('title'))
            ->where('content', $request->input('content'))
            ->where('created_at', '>=', now()->subSeconds(30))
            ->first();

        if ($recentSubmission) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A similar success story was recently submitted. Please wait a moment before submitting again.',
                ], 429);
            }
            
            return redirect()
                ->back()
                ->with('error', 'A similar success story was recently submitted. Please wait a moment before submitting again.');
        }

        // Log the incoming request for debugging
        Log::info('Success Story Form Submission', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'files' => $request->allFiles(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            $request->validate([
                'author'        => 'required|string|max:255',
                'email'         => 'required|email|max:255',
                'phone'         => 'required|string|max:20',
                'university'    => 'required|string|max:255',
                'title'         => 'required|string|max:255',
                'content'       => 'required|string|min:150',
                'category'      => 'required|in:' . implode(',', array_keys(SuccessStory::CATEGORIES)),
                'form_four_index_number' => 'required|string|regex:/^S\d{4}\/\d{4}\/\d{4}$/',
                'images.*'     => 'nullable|image|max:5120', // 5MB limit
                'agree_terms'  => 'required|accepted',
            ], [
                'author.required' => 'Please provide your full name.',
                'author.max' => 'Name cannot exceed 255 characters.',
                'email.required' => 'Please provide your email address.',
                'email.email' => 'Please provide a valid email address.',
                'email.max' => 'Email cannot exceed 255 characters.',
                'phone.required' => 'Please provide your phone number.',
                'phone.max' => 'Phone number cannot exceed 20 characters.',
                'university.required' => 'Please provide your university/institution name.',
                'university.max' => 'University name cannot exceed 255 characters.',
                'title.required' => 'Please provide a title for your story.',
                'title.max' => 'The title cannot exceed 255 characters.',
                'content.required' => 'Please share your success story.',
                'content.min' => 'Your story must be at least 150 characters long.',
                'category.required' => 'Please select the type of HESLB support you received.',
                'category.max' => 'The category name is too long.',
                'form_four_index_number.required' => 'Please provide your Form Four Index Number.',
                'form_four_index_number.regex' => 'Form Four Index Number must be in the format S0000/0000/year (e.g., S1234/0001/2020).',
                'images.*.image' => 'Please upload only image files.',
                'images.*.max' => 'Each image must be smaller than 5MB.',
                'agree_terms.required' => 'You must agree to the terms and conditions.',
                'agree_terms.accepted' => 'You must agree to the terms and conditions.',
            ]);

            $data = $request->except(['images']);
            $data['ip_address']        = $request->ip();
            $data['user_agent']        = $request->userAgent();
            $data['publication_status'] = 'pending';

            // Upload handlers
            $data['images']     = $this->handleImageUploads($request);
            $data['video']      = null;
            $data['documents']  = null;

            // Checkboxes
            $data['allow_photos']  = $request->boolean('allow_photos');
            $data['allow_video']   = false;
            $data['allow_contact'] = $request->boolean('allow_contact');

            Log::info('Processed data before creation', $data);

            $story = SuccessStory::create($data);

            // Record the initial submission action
            StoryActionHistory::create([
                'story_id' => $story->id,
                'admin_id' => null, // No admin involved in initial submission
                'action' => 'submit',
                'old_status' => null,
                'new_status' => 'pending',
                'notes' => 'Story submitted by user',
                'changes' => [
                    'status' => [
                        'from' => null,
                        'to' => 'pending'
                    ]
                ]
            ]);

            Log::info('Success story created successfully', ['story_id' => $story->id]);

            // Return response based on request type
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your success story has been submitted successfully!',
                    'story_id' => $story->id
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Your success story has been submitted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please fix the following errors:',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            Log::error('PostTooLargeException in success story submission', [
                'message' => $e->getMessage(),
                'content_length' => $request->header('content-length'),
                'ip' => $request->ip()
            ]);

            $errorMessage = 'The uploaded files are too large. Please ensure:';
            $errorMessage .= '<ul class="mb-0 mt-2">';
            $errorMessage .= '<li>Images are under 5MB each</li>';
            $errorMessage .= '</ul>';

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 413);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage);

        } catch (\Exception $e) {
            Log::error('Error storing success story', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

public function show($choice)
{
    // First try to find in SuccessStory model (new system)
    $successStory = SuccessStory::where('id', $choice)
                        ->where('publication_status', 'approved')
                        ->whereNotNull('published_at')
                        ->first();

    // If not found in SuccessStory, try Contact model (legacy system)
    if (!$successStory) {
        $successStory = Contact::where('id', $choice)
                            ->where('contact_type', 'success_stories')
                            ->where('status', 'seen')
                            ->where('published', true)
                            ->first();
    }

    // If not found or not published, return 404
    if (!$successStory) {
        abort(404);
    }

    // Increment views - handle both models
    if ($successStory instanceof SuccessStory) {
        $successStory->incrementViews();
    } else {
        // For Contact model, manually increment views
        $successStory->increment('views');
    }

    // Get related published stories - check both models
    $relatedStories = collect();
    
    // Get from SuccessStory model
    $successStoryRelated = SuccessStory::where('publication_status', 'approved')
        ->whereNotNull('published_at')
        ->where('id', '!=', $choice)
        ->take(3)
        ->get();
    
    // Get from Contact model
    $contactRelated = Contact::where('contact_type', 'success_stories')
        ->where('status', 'seen')
        ->where('published', true)
        ->where('id', '!=', $choice)
        ->take(3)
        ->get();
    
    // Merge and take first 3
    $relatedStories = $successStoryRelated->merge($contactRelated)->take(3);

    // Return the success story view
    return view('newspage.successstory', compact('successStory', 'relatedStories'));
}


    
   public function index(Request $request)
{
    // Get stories from both models and merge them
    $successStories = SuccessStory::where('publication_status', 'approved')
                    ->whereNotNull('published_at')
                    ->orderBy('created_at', 'desc')
                    ->get();
    
    $contactStories = Contact::where('contact_type', 'success_stories')
                    ->where('status', 'seen')
                    ->where('published', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
    
    // Merge collections and sort by created_at
    $allStories = $successStories->merge($contactStories)
                    ->sortByDesc('created_at')
                    ->values();
    
    // Paginate manually since we have a collection
    $perPage = 12;
    $currentPage = $request->get('page', 1);
    $offset = ($currentPage - 1) * $perPage;
    $stories = new \Illuminate\Pagination\LengthAwarePaginator(
        $allStories->slice($offset, $perPage),
        $allStories->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'pageName' => 'page']
    );
    
    $categories = ['success_story' => 'Success Story'];

    // For storiescontent.blade.php, we need to pass data in the format expected by the partials
    $newsArticles = $stories; // Rename for compatibility with newsgrid partial
    
    // Get featured stories (most popular by views)
    $featuredStories = $allStories->sortByDesc('views')->take(3);
    $habariKuu = $featuredStories; // Featured stories for header

    return view('seeallproduct.storiescontent', compact('newsArticles', 'habariKuu', 'stories', 'categories'));
}

    /**
     * Show success stories by category
     */
    public function showByCategory($category)
    {
        // Since we only have success stories now, redirect to main stories page
        return redirect()->route('story.getallstories');
    }


    /**
     * Search success stories
     */
    public function search(Request $request)
    {
        $searchTerm = $request->q;
        
        // Search in SuccessStory model
        $successStories = SuccessStory::where('publication_status', 'approved')
                        ->whereNotNull('published_at')
                        ->where(function($q) use ($searchTerm) {
                            $q->where('title', 'like', "%{$searchTerm}%")
                              ->orWhere('content', 'like', "%{$searchTerm}%")
                              ->orWhere('author', 'like', "%{$searchTerm}%")
                              ->orWhere('university', 'like', "%{$searchTerm}%");
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Search in Contact model
        $contactStories = Contact::where('contact_type', 'success_stories')
                        ->where('status', 'seen')
                        ->where('published', true)
                        ->where(function($q) use ($searchTerm) {
                            $q->where('message', 'like', "%{$searchTerm}%")
                              ->orWhere('first_name', 'like', "%{$searchTerm}%")
                              ->orWhere('last_name', 'like', "%{$searchTerm}%")
                              ->orWhere('email', 'like', "%{$searchTerm}%");
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Merge collections and sort by created_at
        $allStories = $successStories->merge($contactStories)
                        ->sortByDesc('created_at')
                        ->values();
        
        // Paginate manually since we have a collection
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $stories = new \Illuminate\Pagination\LengthAwarePaginator(
            $allStories->slice($offset, $perPage),
            $allStories->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
        
        $categories = ['success_story' => 'Success Story'];

        // For storiescontent.blade.php, we need to pass data in the format expected by the partials
        $newsArticles = $stories; // Rename for compatibility with newsgrid partial
        
        // Get featured stories (most popular by views)
        $featuredStories = $allStories->sortByDesc('views')->take(3);
        $habariKuu = $featuredStories; // Featured stories for header

        return view('seeallproduct.storiescontent', compact('newsArticles', 'habariKuu', 'stories', 'categories'));
    }

    protected function handleImageUploads(Request $request)
    {
        if (!$request->hasFile('images')) {
            return null;
        }

        $paths = [];
        foreach ($request->file('images') as $image) {
            try {
                $path = $image->store('success-stories/images', 'public');
                $paths[] = $path;
                Log::info('Image uploaded successfully', ['path' => $path]);
            } catch (\Exception $e) {
                Log::error('Image upload failed', [
                    'error' => $e->getMessage(),
                    'file' => $image->getClientOriginalName()
                ]);
                throw new \Exception('Failed to upload image: ' . $image->getClientOriginalName());
            }
        }

        return json_encode($paths);
    }


}
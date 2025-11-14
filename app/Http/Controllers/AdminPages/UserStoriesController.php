<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use App\Models\StoryActionHistory;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserStoriesController extends Controller
{
    /**
     * Display all user stories with filtering options
     */
    public function index(Request $request)
    {
        // Show all success stories from contacts table
        $query = Contact::where('contact_type', 'success_stories');

        // Filter by status (using the status field from contacts table)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%");
            });
        }

        $stories = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = ['success_story' => 'Success Story'];
        $statuses = ['seen' => 'Seen', 'not seen' => 'Not Seen'];

        return view('adminpages.stories.index', compact('stories', 'categories', 'statuses'));
    }

    /**
     * Show details of a specific story
     */
    public function show($id)
    {
        $story = Contact::findOrFail($id);
        return view('adminpages.stories.show', compact('story'));
    }

    /**
     * Approve a story
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $story = Contact::findOrFail($id);
        
        // Check if author name is empty
        $authorName = trim(($story->first_name ?? '') . ' ' . ($story->last_name ?? ''));
        if (empty($authorName)) {
            return back()
                ->with('error', 'Cannot approve story: Author name is required. Please contact the user to provide their first name and last name before approving this story.');
        }
        
        try {
            $story->update([
                'status' => 'seen',
                'seen_by' => auth()->id(),
            ]);
            
            return redirect()
                ->route('admin.user-stories.index')
                ->with('success', 'Story approved successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error approving story', [
                'story_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Failed to approve story. Please try again.');
        }
    }

    /**
     * Reject a story
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        $story = Contact::findOrFail($id);
        
        try {
            $story->update([
                'status' => 'seen',
                'seen_by' => auth()->id(),
            ]);
            
            return redirect()
                ->route('admin.user-stories.index')
                ->with('success', 'Story rejected successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error rejecting story', [
                'story_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Failed to reject story. Please try again.');
        }
    }

    /**
     * Post a story to the website
     */
    public function post($id)
    {
        $story = Contact::findOrFail($id);
        
        // Check if author name is empty
        $authorName = trim(($story->first_name ?? '') . ' ' . ($story->last_name ?? ''));
        if (empty($authorName)) {
            return back()
                ->with('error', 'Cannot post story: Author name is required. Please contact the user to provide their first name and last name before posting this story to the website.');
        }
        
        try {
            $story->update([
                'published' => true,
            ]);
            
            return redirect()
                ->route('admin.user-stories.index')
                ->with('success', 'Story posted to website successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error posting story', [
                'story_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()
                ->with('error', 'Failed to post story. Please try again.');
        }
    }

    /**
     * Remove a story from the website
     */
    public function unpost($id)
    {
        $story = Contact::findOrFail($id);
        
        try {
            $story->update([
                'published' => false,
            ]);
            
            return redirect()
                ->route('admin.user-stories.index')
                ->with('success', 'Story removed from website successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error unposting story', [
                'story_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()
                ->with('error', 'Failed to remove story from website. Please try again.');
        }
    }

    /**
     * Delete a story
     */
    public function destroy($id)
    {
        $story = Contact::findOrFail($id);
        
        try {
            $story->delete();
            
            return redirect()
                ->route('admin.user-stories.index')
                ->with('success', 'Story deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting story', [
                'story_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()
                ->with('error', 'Failed to delete story. Please try again.');
        }
    }

    /**
     * Show pending stories
     */
    public function pending()
    {
        $stories = SuccessStory::where('publication_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.user-stories.pending', compact('stories'));
    }

    /**
     * Show approved stories
     */
    public function approved()
    {
        $stories = SuccessStory::where('publication_status', 'approved')
            ->orderBy('published_at', 'desc')
            ->paginate(20);
            
        return view('admin.user-stories.approved', compact('stories'));
    }

    /**
     * Show rejected stories
     */
    public function rejected()
    {
        $stories = SuccessStory::where('publication_status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
            
        return view('admin.user-stories.rejected', compact('stories'));
    }

    /**
     * Update a story (admin edit)
     */
       /**
     * Show the form for editing a story
     */
    public function edit($id)
    {
        $story = SuccessStory::findOrFail($id);
        $categories = SuccessStory::CATEGORIES;
        $statuses = SuccessStory::PUBLICATION_STATUSES;
        
        return view('adminpages.stories.edit', compact('story', 'categories', 'statuses'));
    }

    /**
     * Update a story (admin edit) - Debug Version
     */
    public function update(Request $request, $id)
{
    // Debug: Log the incoming request
    Log::info('Story update attempt', [
        'story_id' => $id,
        'request_data' => $request->all(),
        'method' => $request->method()
    ]);

    $request->validate([
        'category' => 'required|in:' . implode(',', array_keys(SuccessStory::CATEGORIES)),
        'publication_status' => 'required|in:' . implode(',', array_keys(SuccessStory::PUBLICATION_STATUSES)),
        'admin_notes' => 'nullable|string|max:1000'
    ]);

    $story = SuccessStory::findOrFail($id);
    
    // Debug: Log the story before update
    Log::info('Story before update', [
        'story_id' => $id,
        'current_status' => $story->publication_status,
        'current_category' => $story->category
    ]);
    
    try {
        $data = $request->only(['category', 'publication_status', 'admin_notes']);
        
        // Debug: Log the data that will be updated
        Log::info('Data to update', ['data' => $data]);
        
        // Record who made the status change (for any status change)
        if ($request->publication_status != $story->publication_status) {
            $data['approved_by'] = auth()->id();
            
            // Record the action in history
            $action = $request->publication_status;
            if ($request->publication_status == 'approved') {
                $action = 'approve';
            } elseif ($request->publication_status == 'rejected') {
                $action = 'reject';
            } elseif ($request->publication_status == 'draft') {
                $action = 'draft';
            }
            
            StoryActionHistory::create([
                'story_id' => $story->id,
                'admin_id' => auth()->id(),
                'action' => $action,
                'old_status' => $story->publication_status,
                'new_status' => $request->publication_status,
                'notes' => $request->admin_notes,
                'changes' => [
                    'status' => [
                        'from' => $story->publication_status,
                        'to' => $request->publication_status
                    ],
                    'category' => $request->category != $story->category ? [
                        'from' => $story->category,
                        'to' => $request->category
                    ] : null
                ]
            ]);
        }
        
        // If story is being approved and wasn't approved before
        if ($request->publication_status == 'approved' && $story->publication_status != 'approved') {
            $data['published_at'] = now();
        }
        
        // If story is being changed from approved to something else, clear published_at
        if ($request->publication_status != 'approved' && $story->publication_status == 'approved') {
            $data['published_at'] = null;
        }
        
        $updated = $story->update($data);
        
        // Debug: Log the result
        Log::info('Story update result', [
            'story_id' => $id,
            'update_success' => $updated,
            'new_status' => $story->fresh()->publication_status
        ]);
        
        return redirect()
            ->route('admin.user-stories.show', $story->id)
            ->with('success', 'Story updated successfully!');
            
    } catch (\Exception $e) {
        Log::error('Error updating story', [
            'story_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
        return back()
 
        ->withInput()
            ->with('error', 'Failed to update story. Please try again.');
    }
}
}
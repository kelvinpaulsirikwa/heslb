<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class FeedBackController extends Controller
{
    /**
     * Display unseen and not deleted contacts.
     */
    public function index()
    {
        $contacts = Contact::where('status', 'not seen')
                            ->where('delete', 'no')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('adminpages.feedback.index', compact('contacts'));
    }

    /**
     * Display all seen contacts.
     */
    public function seen()
    {
        $contacts = Contact::where('status', 'seen')
                            ->where('delete', 'no')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('adminpages.feedback.seen', compact('contacts'));
    }

    /**
     * Display all deleted contacts.
     */
    public function deleted()
    {
        $contacts = Contact::where('delete', 'yes')
                            ->with('deletedByUser')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('adminpages.feedback.deleted', compact('contacts'));
    }

    /**
     * Display a specific contact and mark it as seen.
     */
 public function show($id)
{
    $contact = Contact::findOrFail($id);

    // Mark as seen if not already
    if ($contact->status === 'not seen') {
        $contact->status = 'seen';
        $contact->seen_by = Auth::id(); // store user id who saw
        $contact->save();
    }

    return view('adminpages.feedback.show', compact('contact'));
}


    /**
     * Show a print-friendly view of the feedback.
     */
    public function print($id)
    {
        $contact = Contact::findOrFail($id);
        return view('adminpages.feedback.print', compact('contact'));
    }

    /**
     * Mark a contact as seen.
     */
    public function markAsSeen($id)
    {
        $contact = Contact::findOrFail($id);
        
        if ($contact->status !== 'seen') {
            $contact->status = 'seen';
            $contact->seen_by = Auth::id();
            $contact->save();
        }
        
        return redirect()->back()->with('success', 'Feedback has been marked as reviewed.');
    }

    /**
     * "Delete" a contact (set delete = yes and store deleted_by).
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete = 'yes';
        $contact->deleted_by = Auth::id();
        $contact->save();

        return redirect()->route('adminpages.feedback.index')
                         ->with('success', 'Contact deleted successfully.');
    }

    /**
 * Display contacts filtered by contact type.
 */
public function byType($type)
{
    $contacts = Contact::where('contact_type', $type)
                        ->where('delete', 'no')
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('adminpages.feedback.by_type', compact('contacts', 'type'));
}

}

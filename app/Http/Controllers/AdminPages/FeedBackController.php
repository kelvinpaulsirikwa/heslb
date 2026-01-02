<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Userstable;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;

class FeedBackController extends Controller
{
    /**
     * Display unseen and not deleted contacts.
     */
    public function index()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_feedback')) {
            abort(403, 'You do not have permission to manage feedback.');
        }
        
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
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_feedback')) {
            abort(403, 'You do not have permission to manage feedback.');
        }
        
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
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_feedback')) {
            abort(403, 'You do not have permission to manage feedback.');
        }
        
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
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_feedback')) {
        abort(403, 'You do not have permission to manage feedback.');
    }
    
    $contact = Contact::findOrFail($id);

    // Mark as seen if not already
    if ($contact->status === 'not seen') {
        $contact->status = 'seen';
        $contact->seen_by = Auth::id(); // store user id who saw
        $contact->save();
        
        // Audit log
        AuditLogService::log(
            'view',
            'Feedback',
            $contact->id,
            null,
            ['contact_type' => $contact->contact_type, 'status' => 'seen']
        );
    } else {
        // Audit log for viewing already seen feedback
        AuditLogService::log(
            'view',
            'Feedback',
            $contact->id,
            null,
            ['contact_type' => $contact->contact_type]
        );
    }

    return view('adminpages.feedback.show', compact('contact'));
}


    /**
     * Show a print-friendly view of the feedback.
     */
    public function print($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_feedback')) {
            abort(403, 'You do not have permission to manage feedback.');
        }
        
        $contact = Contact::findOrFail($id);
        return view('adminpages.feedback.print', compact('contact'));
    }

    /**
     * Mark a contact as seen.
     */
    public function markAsSeen($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_feedback')) {
            abort(403, 'You do not have permission to manage feedback.');
        }
        
        $contact = Contact::findOrFail($id);
        
        if ($contact->status !== 'seen') {
            $contact->status = 'seen';
            $contact->seen_by = Auth::id();
            $contact->save();
            
            // Audit log
            AuditLogService::log(
                'update',
                'Feedback',
                $contact->id,
                ['status' => 'not seen'],
                ['status' => 'seen']
            );
        }
        
        return redirect()->back()->with('success', 'Feedback has been marked as reviewed.');
    }

    /**
     * "Delete" a contact (set delete = yes and store deleted_by).
     */
    public function destroy($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_feedback')) {
            abort(403, 'You do not have permission to manage feedback.');
        }
        
        $contact = Contact::findOrFail($id);

        // Audit log before deletion
        AuditLogService::log(
            'delete',
            'Feedback',
            $contact->id,
            ['contact_type' => $contact->contact_type, 'status' => $contact->status],
            null
        );

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
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_feedback')) {
        abort(403, 'You do not have permission to manage feedback.');
    }
    
    $contacts = Contact::where('contact_type', $type)
                        ->where('delete', 'no')
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('adminpages.feedback.by_type', compact('contacts', 'type'));
}

}

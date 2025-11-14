<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactFeedback;
use Illuminate\Http\Request;

class AdminContactUsController extends Controller
{
    /**
     * Show all feedback messages.
     */
    public function index()
    {
        // Fetch all feedback messages ordered by latest first
        $feedbacks = Contact::orderBy('created_at', 'desc')->get();

        return view('adminpages.feedback.feedback', compact('feedbacks'));
    }
}

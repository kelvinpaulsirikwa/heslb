<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;
use App\Models\Userstable;
use Illuminate\Support\Facades\Auth;

class FAQController extends Controller
{
    // Display all FAQs
    public function index(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_faqs')) {
            abort(403, 'You do not have permission to manage FAQs.');
        }
        
        $query = FAQ::with('user');
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('posted_by', $request->user_id);
        }
        
        $faqs = $query->get();
        return view('adminpages.faq.index', compact('faqs'));
    }

    // Show form to create
    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_faqs')) {
            abort(403, 'You do not have permission to manage FAQs.');
        }
        
        $users = Userstable::all();
        return view('adminpages.faq.create', compact('users'));
    }

    // Store new FAQ
   public function store(Request $request)
{
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_faqs')) {
        abort(403, 'You do not have permission to manage FAQs.');
    }
    
    try {
        $validatedData = \App\Services\AdminValidationService::validate($request, 'faqs');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    FAQ::create([
        'question' => $validatedData['question'],
        'answer' => json_encode($validatedData['steps']), // store steps as JSON array
        'posted_by' => Auth::check() ? Auth::id() : null,
        'type' => $validatedData['type'],
        'qnstype' => $validatedData['qnstype'],
    ]);

    return redirect()->route('faq.index')->with('success', 'FAQ added successfully!');
}

    // Show single FAQ
    public function show($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_faqs')) {
            abort(403, 'You do not have permission to manage FAQs.');
        }
        
        $faq = FAQ::with('user')->findOrFail($id);
        return view('adminpages.faq.show', compact('faq'));
    }

    // Edit FAQ form
    public function edit($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_faqs')) {
            abort(403, 'You do not have permission to manage FAQs.');
        }
        
        $faq = FAQ::findOrFail($id);
        $users = Userstable::all();
        return view('adminpages.faq.edit', compact('faq', 'users'));
    }

    // Update FAQ
    public function update(Request $request, $id)
{
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_faqs')) {
        abort(403, 'You do not have permission to manage FAQs.');
    }
    
    $faq = FAQ::findOrFail($id);

    try {
        $validatedData = \App\Services\AdminValidationService::validate($request, 'faqs');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    $faq->update([
        'question' => $validatedData['question'],
        'answer' => json_encode($validatedData['steps']), // store as JSON array
        'posted_by' => Auth::check() ? Auth::id() : null,
        'type' => $validatedData['type'],
        'qnstype' => $validatedData['qnstype'],
    ]);

    return redirect()->route('faq.index')->with('success', 'FAQ updated successfully!');
}

    // Delete FAQ
    public function destroy($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_faqs')) {
            abort(403, 'You do not have permission to manage FAQs.');
        }
        
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq.index')->with('success', 'FAQ deleted successfully!');
    }
}

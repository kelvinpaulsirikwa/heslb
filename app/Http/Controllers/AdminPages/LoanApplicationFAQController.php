<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;
use App\Models\Userstable;
use Illuminate\Support\Facades\Auth;

class LoanApplicationFAQController extends Controller
{
    // Display all Loan Application FAQs
    public function index()
    {
        $faqs = FAQ::with('user')->where('type', 'loan_application')->get();
        return view('adminpages.loan-application-faqs.index', compact('faqs'));
    }

    // Show form to create
    public function create()
    {
        $users = Userstable::all();
        return view('adminpages.loan-application-faqs.create', compact('users'));
    }

    // Store new FAQ
    public function store(Request $request)
    {
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
            'type' => $validatedData['type'], // Use validated type
            'qnstype' => $validatedData['qnstype'],
        ]);

        return redirect()->route('loan-application-faqs.index')->with('success', 'Loan Application FAQ added successfully!');
    }

    // Show single FAQ
    public function show(FAQ $faq)
    {
        // Ensure this is a loan application FAQ
        if ($faq->type !== 'loan_application') {
            abort(404);
        }
        
        $faq->load('user');
        return view('adminpages.loan-application-faqs.show', compact('faq'));
    }

    // Edit FAQ form
    public function edit(FAQ $faq)
    {
        // Ensure this is a loan application FAQ
        if ($faq->type !== 'loan_application') {
            abort(404);
        }
        
        $users = Userstable::all();
        return view('adminpages.loan-application-faqs.edit', compact('faq', 'users'));
    }

    // Update FAQ
    public function update(Request $request, FAQ $faq)
    {
        // Ensure this is a loan application FAQ
        if ($faq->type !== 'loan_application') {
            abort(404);
        }

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'faqs');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $faq->update([
            'question' => $validatedData['question'],
            'answer' => json_encode($validatedData['steps']), // store steps as JSON array
            'posted_by' => Auth::check() ? Auth::id() : null,
            'type' => $validatedData['type'], // Use validated type
            'qnstype' => $validatedData['qnstype'],
        ]);

        return redirect()->route('loan-application-faqs.index')->with('success', 'Loan Application FAQ updated successfully!');
    }

    // Delete FAQ
    public function destroy(FAQ $faq)
    {
        // Ensure this is a loan application FAQ
        if ($faq->type !== 'loan_application') {
            abort(404);
        }
        
        $faq->delete();

        return redirect()->route('loan-application-faqs.index')->with('success', 'Loan Application FAQ deleted successfully!');
    }
}

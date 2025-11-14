<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;
use App\Models\Userstable;
use Illuminate\Support\Facades\Auth;

class LoanRepaymentFAQController extends Controller
{
    // Display all Loan Repayment FAQs
    public function index()
    {
        $faqs = FAQ::with('user')->where('type', 'loan_repayment')->get();
        return view('adminpages.loan-repayment-faqs.index', compact('faqs'));
    }

    // Show form to create
    public function create()
    {
        $users = Userstable::all();
        return view('adminpages.loan-repayment-faqs.create', compact('users'));
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

        return redirect()->route('loan-repayment-faqs.index')->with('success', 'Loan Repayment FAQ added successfully!');
    }

    // Show single FAQ
    public function show(FAQ $faq)
    {
        // Ensure this is a loan repayment FAQ
        if ($faq->type !== 'loan_repayment') {
            abort(404);
        }
        
        $faq->load('user');
        return view('adminpages.loan-repayment-faqs.show', compact('faq'));
    }

    // Edit FAQ form
    public function edit(FAQ $faq)
    {
        // Ensure this is a loan repayment FAQ
        if ($faq->type !== 'loan_repayment') {
            abort(404);
        }
        
        $users = Userstable::all();
        return view('adminpages.loan-repayment-faqs.edit', compact('faq', 'users'));
    }

    // Update FAQ
    public function update(Request $request, FAQ $faq)
    {
        // Ensure this is a loan repayment FAQ
        if ($faq->type !== 'loan_repayment') {
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
            'answer' => json_encode($validatedData['steps']), // store as JSON array
            'posted_by' => Auth::check() ? Auth::id() : null,
            'type' => $validatedData['type'], // Use validated type
            'qnstype' => $validatedData['qnstype'],
        ]);

        return redirect()->route('loan-repayment-faqs.index')->with('success', 'Loan Repayment FAQ updated successfully!');
    }

    // Delete FAQ
    public function destroy(FAQ $faq)
    {
        // Ensure this is a loan repayment FAQ
        if ($faq->type !== 'loan_repayment') {
            abort(404);
        }
        
        $faq->delete();

        return redirect()->route('loan-repayment-faqs.index')->with('success', 'Loan Repayment FAQ deleted successfully!');
    }
}

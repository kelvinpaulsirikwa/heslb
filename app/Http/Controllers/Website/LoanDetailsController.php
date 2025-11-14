<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoanDetailsController extends Controller
{
    public function show($loanType)
    {
        // Define valid loan types
        $validLoanTypes = ['tuition', 'meals', 'books', 'research', 'special_faculty', 'field_work'];
        
        // Check if loan type exists
        if (!in_array($loanType, $validLoanTypes)) {
            abort(404, 'Loan type not found');
        }

        // Get current locale
        $locale = app()->getLocale();
        
        // Load loan details from language files
        $details = __("loan_{$loanType}.title") ? 
            [
                'title' => __("loan_{$loanType}.title"),
                'description' => __("loan_{$loanType}.description"),
                'details' => __("loan_{$loanType}.details"),
                'benefits' => __("loan_{$loanType}.benefits"),
                'advantages' => __("loan_{$loanType}.advantages"),
                'how_it_works' => __("loan_{$loanType}.how_it_works")
            ] : null;

        // Fallback to English if current locale doesn't exist
        if (!$details && $locale !== 'en') {
            app()->setLocale('en');
            $details = [
                'title' => __("loan_{$loanType}.title"),
                'description' => __("loan_{$loanType}.description"),
                'details' => __("loan_{$loanType}.details"),
                'benefits' => __("loan_{$loanType}.benefits"),
                'advantages' => __("loan_{$loanType}.advantages"),
                'how_it_works' => __("loan_{$loanType}.how_it_works")
            ];
            app()->setLocale($locale); // Reset to original locale
        }

        if (!$details) {
            abort(404, 'Loan type not found');
        }

        return view('loan.loan-details', compact('details', 'loanType'));
    }
}

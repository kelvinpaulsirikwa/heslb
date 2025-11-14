<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class LoanRepaymentController extends Controller
{
     
    public function aboutLoanRepayment()
    {
        return view('loan.loanrepayment.partial.aboutloanrepayment');
    }

    public function obligation()
    {
        return view('loan.loanrepayment.partial.obligations');
    }
public function faqs()
{
    // Fetch from DB where qnstype is 'loan_application'
    $popular_questions = FAQ::where('qnstype', 'popular_questions')
                            ->where('type', 'loan_repayment')
                            ->orderBy('id', 'asc')
                            ->get()
                            ->map(function($faq) {
                                $steps = json_decode($faq->answer, true) ?? [];
                                if(count($steps)) {
                                    $faq->answer = '<ul>';
                                    foreach($steps as $step) {
                                        $faq->answer .= '<li>' . e($step) . '</li>';
                                    }
                                    $faq->answer .= '</ul>';
                                }
                                return $faq;
                            });

    $general_questions = FAQ::where('qnstype', 'general_questions')
                            ->where('type', 'loan_repayment')
                            ->orderBy('id', 'asc')
                            ->get()
                            ->map(function($faq) {
                                $steps = json_decode($faq->answer, true) ?? [];
                                if(count($steps)) {
                                    $faq->answer = '<ul>';
                                    foreach($steps as $step) {
                                        $faq->answer .= '<li>' . e($step) . '</li>';
                                    }
                                    $faq->answer .= '</ul>';
                                }
                                return $faq;
                            });

    return view('loan.loanrepayment.partial.faqs', compact('popular_questions', 'general_questions'));
}
  
}

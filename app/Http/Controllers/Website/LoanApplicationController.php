<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class LoanApplicationController extends Controller
{
    public function aboutLoanApplication()
    {
        return view('loan.loanapplication.partial.aboutloanapplication');
    }

    public function obligation()
    {
        return view('loan.loanapplication.partial.obligations');
    }
public function faqs()
{
    // Fetch from DB where qnstype is 'loan_application'
    $popular_questions = FAQ::where('qnstype', 'popular_questions')
                            ->where('type', 'loan_application')
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
                            ->where('type', 'loan_application')
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

    return view('loan.loanapplication.partial.faqs', compact('popular_questions', 'general_questions'));
}

    public function applicationGuideline()
    {
        // Get ALL current guidelines for the website
        $guidelines = \App\Models\ApplicationGuideline::published()
            ->current()
            ->with(['creator', 'updater', 'publication'])
            ->ordered()
            ->get();

        // For backward compatibility, set currentGuideline as the first current guideline
        $currentGuideline = $guidelines->first();

        return view('loan.loanapplication.partial.applicationguideline', compact('guidelines', 'currentGuideline'));
    }

    public function downloadGuideline($id)
    {
        $guideline = \App\Models\ApplicationGuideline::findOrFail($id);

        // Check if file is accessible using the model's helper method
        if (!$guideline->hasAccessibleFile()) {
            abort(404, 'File not found.');
        }

        $absolutePath = $guideline->absolute_file_path;
        $fileName = $guideline->file_original_name ?: $guideline->file_name;

        $guideline->increment('download_count');
        return response()->download($absolutePath, $fileName);
    }

    public function readGuideline($id)
    {
        $guideline = \App\Models\ApplicationGuideline::findOrFail($id);

        // Check if file is accessible using the model's helper method
        if (!$guideline->hasAccessibleFile()) {
            abort(404, 'File not found.');
        }

        $absolutePath = $guideline->absolute_file_path;
        $fileName = $guideline->file_original_name ?: $guideline->file_name;
        $mimeType = $guideline->file_type ?: (\Illuminate\Support\Facades\File::mimeType($absolutePath) ?? 'application/octet-stream');

        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    public function applicationLink()
    {
        return view('loan.loanapplication.partial.applicationlink');
    }

    public function repaymentLink()
    {
        return view('loan.loanrepayment.partial.repaymentlink');
    }

    public function samiaScholarship()
    {
        return view('loan.loanapplication.partial.samiascholarship');
    }
}

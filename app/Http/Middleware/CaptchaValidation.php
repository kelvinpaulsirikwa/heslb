<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CaptchaValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Only validate on POST requests
        if ($request->isMethod('post')) {
            $captchaCategory = $request->input('captcha_category');
            $captchaSelected = $request->input('captcha_selected');
            
            // Check if captcha data is present
            if (!$captchaCategory || !$captchaSelected) {
                return response()->json([
                    'error' => 'Captcha verification required',
                    'message' => 'Please complete the captcha verification'
                ], 422);
            }
            
            // Validate category
            $validCategories = ['waterbodies', 'mountain', 'car', 'forest'];
            if (!in_array($captchaCategory, $validCategories)) {
                return response()->json([
                    'error' => 'Invalid captcha category',
                    'message' => 'Captcha verification failed'
                ], 422);
            }
            
            // Get correct answers from session
            $correctAnswers = Session::get('captcha_correct_answers', []);
            $sessionCategory = Session::get('captcha_category');
            
            // Validate session data
            if (empty($correctAnswers) || $sessionCategory !== $captchaCategory) {
                return response()->json([
                    'error' => 'Captcha session expired',
                    'message' => 'Please refresh and try again'
                ], 422);
            }
            
            // Parse selected images
            $selectedImages = json_decode($captchaSelected, true);
            if (!is_array($selectedImages)) {
                return response()->json([
                    'error' => 'Invalid captcha selection',
                    'message' => 'Please select the correct images'
                ], 422);
            }
            
            // Validate selection
            $allCorrectSelected = collect($correctAnswers)->every(function ($image) use ($selectedImages) {
                return in_array($image, $selectedImages);
            });
            
            $noIncorrectSelected = collect($selectedImages)->every(function ($image) use ($correctAnswers) {
                return in_array($image, $correctAnswers);
            });
            
            // Debug logging (remove in production)
            Log::info('Captcha Validation', [
                'category' => $captchaCategory,
                'selected' => $selectedImages,
                'correct' => $correctAnswers,
                'allCorrectSelected' => $allCorrectSelected,
                'noIncorrectSelected' => $noIncorrectSelected
            ]);
            
            if (!$allCorrectSelected || !$noIncorrectSelected) {
                return response()->json([
                    'error' => 'Incorrect captcha selection',
                    'message' => 'Please select all correct images'
                ], 422);
            }
            
            // Clear captcha session after successful validation
            Session::forget(['captcha_category', 'captcha_correct_answers']);
        }
        
        return $next($request);
    }
}

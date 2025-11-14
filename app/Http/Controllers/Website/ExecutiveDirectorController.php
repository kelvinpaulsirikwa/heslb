<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ExecutiveDirector;
use Illuminate\Http\Request;

class ExecutiveDirectorController extends Controller
{
    /**
     * Display the executive directors page.
     * Orders: Active (current/newest) directors first at top,
     * then former directors by end_year descending (newest ended first, going down to oldest).
     * Handles data inconsistencies like end_year before start_year, NULL values, and collisions.
     */
    public function index()
    {
        // Get all directors and sort them properly in PHP for better control
        $executiveDirectors = ExecutiveDirector::get()->sort(function($a, $b) {
            // 1. Active directors come first (status priority)
            if ($a->status === 'Active' && $b->status !== 'Active') {
                return -1; // $a comes first
            }
            if ($a->status !== 'Active' && $b->status === 'Active') {
                return 1; // $b comes first
            }
            
            // 2. Within Active directors: sort by start_year DESC (newest active first)
            if ($a->status === 'Active' && $b->status === 'Active') {
                if ($a->start_year != $b->start_year) {
                    return $b->start_year <=> $a->start_year; // DESC
                }
                // Tiebreaker: created_at DESC (newest first)
                return $b->created_at <=> $a->created_at;
            }
            
            // 3. Within Former directors: sort by end_year DESC (newest ended first)
            if ($a->status === 'Former' && $b->status === 'Former') {
                // Handle invalid data: if end_year < start_year, use start_year
                $aEndYear = ($a->end_year && $a->end_year >= $a->start_year) 
                    ? $a->end_year 
                    : $a->start_year;
                $bEndYear = ($b->end_year && $b->end_year >= $b->start_year) 
                    ? $b->end_year 
                    : $b->start_year;
                
                // If no end_year or invalid, use start_year
                $aSortYear = $aEndYear ?? $a->start_year;
                $bSortYear = $bEndYear ?? $b->start_year;
                
                if ($aSortYear != $bSortYear) {
                    return $bSortYear <=> $aSortYear; // DESC - newest first
                }
                
                // Tiebreaker 1: start_year DESC
                if ($a->start_year != $b->start_year) {
                    return $b->start_year <=> $a->start_year;
                }
                
                // Tiebreaker 2: created_at DESC (most recently added first)
                return $b->created_at <=> $a->created_at;
            }
            
            // Fallback: by created_at
            return $b->created_at <=> $a->created_at;
        })->values(); // Re-index the collection
        
        return view('aboutus.partials.executivedirector', compact('executiveDirectors'));
    }
}

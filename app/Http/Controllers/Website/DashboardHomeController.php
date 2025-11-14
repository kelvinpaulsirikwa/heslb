<?php

namespace App\Http\Controllers\Website;

use App\Models\Category;
use App\Models\Visit;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\News;
use App\Models\Partner;
use App\Models\WindowApplication;
use App\Models\ApplicationGuideline;
use Illuminate\Http\Request;

class DashboardHomeController extends Controller
{
    public function showdashboard()
    {
        // Call the modular method to get visitor stats
        $stats = $this->getVisitorStats();
        $windowapplications = $this->getWindowApplications();
        $shortcutlinks = $this->getShortcutLinks();
        $strategicPartners = $this->getStrategicPartners();
        $publicationlist = $this->getPublicationList();
        $latestNews = $this->getHomePageNews();
        $currentGuideline = $this->getCurrentGuideline();

   
        return view('home', compact('stats','windowapplications', 'shortcutlinks' , 'strategicPartners','publicationlist', 'latestNews', 'currentGuideline'));
        }

    // This method only returns the visitor stats array
  public function getVisitorStats()
{
    $allVisits = Visit::all();
    logger('All Visits:', $allVisits->toArray()); // Check Laravel logs for records

    $todayCount = Visit::whereDate('visited_at', Carbon::today())->count();
    $yesterdayCount = Visit::whereDate('visited_at', Carbon::yesterday())->count();
    $thisWeekCount = Visit::whereBetween('visited_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    $thisMonthCount = Visit::whereBetween('visited_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
    $allTimeCount = Visit::count();

 
    return [
        ['label' => 'Today', 'value' => $todayCount, 'icon' => 'bi-calendar-day'],
        ['label' => 'Yesterday', 'value' => $yesterdayCount, 'icon' => 'bi-calendar-minus'],
        ['label' => 'This Week', 'value' => $thisWeekCount, 'icon' => 'bi-calendar-week'],
        ['label' => 'This Month', 'value' => $thisMonthCount, 'icon' => 'bi-calendar-month'],
     ];
}

//get window applicaiton 
public function getWindowApplications()
{
    $applications = WindowApplication::latest()->get();

    if ($applications->isEmpty()) {
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        $academicYear = "{$currentYear}/{$nextYear}";

        $programTypes = [
            'Diploma',
            'Bachelor\'s Degree',
            'Laws School PGDL',
            'Master\'s Degree',
            'PhD',
            'Samia Scholarship'
        ];

        $openingDate = Carbon::createFromDate($currentYear, 8, 1);
        $closingDate = Carbon::createFromDate($currentYear, 8, 31);
        $window = 'Application Window';

        $now = Carbon::now();
        $isOpen = $now->between($openingDate, $closingDate);
        $countdownDate = $isOpen ? $closingDate : $openingDate;
        $nextOpeningDate = Carbon::createFromDate($currentYear + 1, 8, 1);

        return [
            'window' => $window,
            'academic_year' => $academicYear,
            'programs' => $programTypes,
            'opening_date' => $openingDate,
            'closing_date' => $closingDate,
            'is_open' => $isOpen,
            'countdown_date' => $countdownDate,
            'next_opening_date' => $nextOpeningDate,
        ];
    }

    $application = $applications->first();
    $openingDate = Carbon::parse($application->starting_date);
    $closingDate = Carbon::parse($application->ending_date);
    $now = Carbon::now();

    $isOpen = $now->between($openingDate, $closingDate);
    $countdownDate = $isOpen ? $closingDate : $openingDate;
    $nextOpeningDate = Carbon::createFromDate($openingDate->year + 1, 8, 1);

    return [
        'window' => $application->window ?? 'Application Window',
        'academic_year' => $application->academic_year,
        'programs' => explode(', ', $application->program_type),
        'opening_date' => $openingDate,
        'closing_date' => $closingDate,
        'is_open' => $isOpen,
        'countdown_date' => $countdownDate,
        'next_opening_date' => $nextOpeningDate,
    ];
}

//fetch shortcut links
public function getShortcutLinks()
{
    return Link::select('link_name', 'link', 'is_file')
        ->take(15) // Limit to 15 items
        ->get();
}

//fetch all shortcut links for the dedicated page
public function getAllShortcutLinks()
{
    return Link::select('link_name', 'link', 'is_file')
        ->get();
}

// Display all shortcut links page
public function showAllShortcutLinks()
{
    $allLinks = $this->getAllShortcutLinks();
    $totalCount = Link::count();
    
    return view('ceoremarks.allshortcutlinks', compact('allLinks', 'totalCount'));
}


//fetch partners

public function getStrategicPartners()
{
    // Fetch all partners from database (without user relationship)
    return Partner::orderBy('name')
        ->get()
        ->map(function ($partner) {
            return [
                'id' => $partner->id,
                'name' => $partner->name,
                'acronym_name' => $partner->acronym_name,
                'logo' => $partner->image_path 
                    ? asset('images/storage/partner_image/' . $partner->image_path) 
                    : null,
                'url' => $partner->link,
                'created_at' => $partner->created_at,
            ];
        })
        ->toArray(); // make sure it’s an array
}


//Publication for footer
//Publication for footer
public function getpublicationlist()
{
    $categories = Category::active()
        ->ordered()
        ->take(15)
        ->get()
        ->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'display_order' => $category->display_order,
            ];
        })
        ->toArray();

    return $categories;
}

 
public function getHomePageNews()
{
    return News::where('date_expire', '>=', Carbon::now()) // only not expired
        ->orderBy('created_at', 'desc') // order by newest first
        ->take(10) // limit to 10
        ->get();
}

//fetch current application guideline
public function getCurrentGuideline()
{
    return ApplicationGuideline::current()->first();
}

}


 

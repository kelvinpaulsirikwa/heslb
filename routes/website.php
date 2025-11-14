<?php

use App\Http\Controllers\Website\ContactUsController;
use App\Http\Controllers\Website\ExecutiveDirectorController;
use App\Http\Controllers\Website\NewsAndEvent;
use App\Http\Controllers\Website\NewsPageController;
use App\Http\Controllers\Website\PhotoGallery;
use App\Http\Controllers\Website\PublicationController;
use App\Http\Controllers\Website\StrategicPartner;
use App\Http\Controllers\Website\DashboardHomeController;
use App\Http\Controllers\Website\UsersStoriesController;
use App\Http\Controllers\Website\VideoPodcastController;
use App\Http\Controllers\Website\BoardOfDirectorController;
use App\Http\Controllers\Website\ScholarshipController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\LoanApplicationController;
use App\Http\Controllers\Website\LoanRepaymentController;

// ------------------------------
// Website Routes
// ------------------------------

Route::get('/', [DashboardHomeController::class, 'showdashboard'])->name('home');

// About Us Subpages

Route::get('/about', function () {
    return view('aboutus.aboutus');
})->name('about');

Route::prefix('about-us')->name('aboutus.')->group(function () {
    
    // Static Views
    Route::view('/vision-mission', 'aboutus.partials.visionandmission')->name('visionmission');
    Route::view('/function', 'aboutus.partials.functionsection')->name('function');
    Route::view('/organogram', 'aboutus.partials.organogram')->name('organogram');

    // Dynamic Pages
    Route::get('/boardofdirector', [BoardOfDirectorController::class, 'index'])->name('boardofdirector');
    Route::get('/strategicpartners', [StrategicPartner::class, 'strategicPartners'])->name('strategicpartners');
    Route::get('/videopodcast', [VideoPodcastController::class, 'fetchvideolinks'])->name('videopodcast');
    Route::get('/photogallery', [PhotoGallery::class, 'allFileManage'])->name('photogallery');
    Route::get('/executivedirector', [ExecutiveDirectorController::class, 'index'])->name('executivedirector');
});

Route::get('/photogallery/{folderName}', [PhotoGallery::class, 'viewFolder'])->name('folder.viewimage');

//publications
Route::get('/publications', [PublicationController::class, 'allpublication'])->name('publications.all');
Route::get('/category/{choice}', [PublicationController::class, 'show'])->name('publications.category');

// Shortcut Links - View All
Route::get('/all-shortcut-links', [DashboardHomeController::class, 'showAllShortcutLinks'])->name('shortcutlinks.all');
Route::get('/contact-us', [ContactUsController::class, 'showContactByRegion'])->name('contactus.formandregion');

//user stories 
Route::get('/give-us-your-stories', [UsersStoriesController::class, 'showstoryform'])->name('story.giveusstories');
Route::get('/show-user-stories', [UsersStoriesController::class, 'index'])->name('story.getallstories');
Route::post('/success-stories', [UsersStoriesController::class, 'store'])->name('successstories.store');
Route::get('/stories/search', [UsersStoriesController::class, 'search'])->name('story.search');
Route::get('/stories/category/{category}', [UsersStoriesController::class, 'showByCategory'])->name('story.category');
Route::get('/stories/{choice}', [UsersStoriesController::class, 'show'])->name('story.showspecific');

//loan navlinks 

Route::prefix('loanapplication')->group(function () {
    Route::get('/about-loan-application', [LoanApplicationController::class, 'aboutLoanApplication'])->name('loanapplication.about');
    Route::get('/obligation', [LoanApplicationController::class, 'obligation'])->name('loanapplication.obligation');
    Route::get('/faqs', [LoanApplicationController::class, 'faqs'])->name('loanapplication.faqs');
    Route::get('/application-guideline', [LoanApplicationController::class, 'applicationGuideline'])->name('loanapplication.guideline');
    Route::get('/application-guideline/download/{id}', [LoanApplicationController::class, 'downloadGuideline'])->name('loanapplication.guideline.download');
    Route::get('/application-guideline/read/{id}', [LoanApplicationController::class, 'readGuideline'])->name('loanapplication.guideline.read');
    Route::get('/application-link', [LoanApplicationController::class, 'applicationLink'])->name('loanapplication.applicationlink');
    Route::get('/repayment-link', [LoanApplicationController::class, 'repaymentLink'])->name('loanrepayment.repaymentlink');
    Route::get('/scholarship', [ScholarshipController::class, 'index'])->name('scholarships.index');
});

// Scholarships public pages
Route::get('/scholarships/{slug}', [ScholarshipController::class, 'show'])->name('scholarships.show');

Route::prefix('loanrepayment')->group(function () {
    Route::get('/about-loan-repayment', [LoanRepaymentController::class, 'aboutLoanRepayment'])->name('loanrepayment.about');
    Route::get('/obligation', [LoanRepaymentController::class, 'obligation'])->name('loanrepayment.obligation');
    Route::get('/faqs', [LoanRepaymentController::class, 'faqs'])->name('loanrepayment.faqs');
});

 
Route::prefix('news')->group(function () {
    Route::get('/', [NewsPageController::class, 'showAllNews'])->name('newscenter.fetchnews');
    Route::get('/search', [NewsPageController::class, 'search'])->name('newscenter.searching');
    Route::get('/{id}', [NewsPageController::class, 'showSpecificNews'])->name('newscenter.singlenews');
    Route::get('/category/{category}', [NewsPageController::class, 'newsByCategory'])->name('newscenter.category');
});
  
//contact us 
Route::get('/get-us-intouch', [ContactUsController::class, 'complainsform'])->name('contactus.getusintouch');
Route::post('/contact', [ContactUsController::class, 'store'])->name('contact.store');

//E-Mrejesho 
Route::get('/e-mrejesho', function () {
    return redirect('https://emrejesho.gov.go.tz/tenganisha_aina_za_walalamikaji?7xXbgfZOgbbuydR9ZWVk6XSPkRPs5nDfp8TAxno0t32NrGMX5ES4RkQFAfFAVbzW&to_this_inst=65$7xXbgfZOgbbuydR9ZWVk6XSPkRPs5nDfp8TAxno0t32NrGMX5ES4RkQFAfFAVbzW');
})->name('menu.emrejesho');
  



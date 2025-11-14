<?php

use App\Http\Controllers\AdminPages\AuthController;
use App\Http\Controllers\AdminPages\DashboardController;
use App\Http\Controllers\AdminPages\NewsAndEventController;
use App\Http\Controllers\AdminPages\PhotoGalleryController;
use App\Http\Controllers\AdminPages\ProfileManagementController;
use App\Http\Controllers\AdminPages\AdminContactUsController;
use App\Http\Controllers\AdminPages\FeedBackController;
use App\Http\Controllers\AdminPages\UserManagementController;
use App\Http\Controllers\AdminPages\FAQController;
use App\Http\Controllers\AdminPages\LoanApplicationFAQController;
use App\Http\Controllers\AdminPages\LoanRepaymentFAQController;
use App\Http\Controllers\AdminPages\ValidationDocumentationController;
use App\Http\Controllers\AdminPages\PartnerManageController;
use App\Http\Controllers\AdminPages\UserStoriesController;
use App\Http\Controllers\AdminPages\VideoPodcastsController;
use App\Http\Controllers\AdminPages\WindowApplicationController;
use App\Http\Controllers\AdminPages\ShortCutLinksController;
use App\Http\Controllers\AdminPages\NewsPagePublishController;
use App\Http\Controllers\AdminPages\ApplicationGuidelineController;
use App\Http\Controllers\AdminPages\BoardOfDirectorController;
use App\Http\Controllers\AdminPages\ExecutiveDirectorAdminController;
use App\Http\Controllers\AdminPages\PublicationAdminController;
use App\Http\Controllers\AdminPages\ScholarshipAdminController;
use App\Http\Controllers\AdminPages\LoginAttemptsController;
use Illuminate\Support\Facades\Route;


// ------------------------------
// Admin Page Routes
// ------------------------------

//auth and login ones
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change.submit');
});

//login and logout ones
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form')->middleware(['guest', 'cache.headers:private,no-store,must-revalidate', 'prevent.back.button']);
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware(['guest', 'cache.headers:private,no-store,must-revalidate', 'prevent.back.button', 'login.attempt.limiter']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Login Attempts
Route::prefix('admin')->name('admin.')->middleware(['auth', 'check.user.status'])->group(function () {
    Route::get('login-attempts', [LoginAttemptsController::class, 'index'])->name('login-attempts.index');
    Route::post('login-attempts/clear-all', [LoginAttemptsController::class, 'clearAll'])->name('login-attempts.clear-all');
    Route::post('login-attempts/clear-old', [LoginAttemptsController::class, 'clearOld'])->name('login-attempts.clear-old');
});

//Admin pages
Route::middleware(['auth','prevent.blocked.actions', 'check.user.status'])->group(function () {
    
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'showdashboard'])->name('dashboard');

    // News page management system
    Route::get('/newsdashboard', [NewsAndEventController::class, 'showallnews']) ->name('news.dashboard');

    // Video podcasts
    Route::resource('videopodcasts', VideoPodcastsController::class);

    // Window applications
    Route::resource('admin/window-applications', WindowApplicationController::class)->names('admin.window_applications');

    // Shortcut links
    Route::resource('shortcut-links', ShortCutLinksController::class);

    // News page
    Route::resource('admin/news', NewsPagePublishController::class, ['as' => 'admin']);

    // Strategic partners
    Route::resource('admin/partners', PartnerManageController::class)->names('admin.partners');

    // Feedback by type
    Route::get('adminpages/feedback/type/{type}', [FeedBackController::class, 'byType'])->name('feedback.byType');

    // Admin contact feedback
    Route::get('admin/contact-feedback', [AdminContactUsController::class, 'index']) ->name('admin.feedback');

    // Application guidelines
    Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('application-guidelines', ApplicationGuidelineController::class));

    // Application guidelines download
     Route::get('admin/application-guidelines/{id}/download', [ApplicationGuidelineController::class, 'download'])->name('admin.application-guidelines.download');

    // Application guidelines set current
    Route::post('admin/application-guidelines/{id}/set-current', [ApplicationGuidelineController::class, 'setCurrent'])->name('admin.application-guidelines.set-current');

    // Publications categories (must come before resource routes to avoid conflicts)
    Route::prefix('admin')->name('admin.')->controller(PublicationAdminController::class)->group(function () {
        Route::get('publications/categories', 'categoriesIndex')->name('publications.categories.index');
        Route::get('publications/categories/create', 'categoriesCreate')->name('publications.categories.create');
        Route::post('publications/categories', 'categoriesStore')->name('publications.categories.store');
        Route::get('publications/categories/{category}/edit', 'categoriesEdit')->name('publications.categories.edit');
        Route::put('publications/categories/{category}', 'categoriesUpdate')->name('publications.categories.update');
        Route::delete('publications/categories/{category}', 'categoriesDestroy')->name('publications.categories.destroy');
        Route::post('publications/categories/{category}/toggle-status', 'toggleCategoryStatus')->name('publications.categories.toggle-status');
        Route::post('publications/categories/update-order', 'updateCategoryOrder')->name('publications.categories.update-order');
        Route::get('publications/search/results', 'search')->name('publications.search');
        Route::post('publications/{publication}/toggle-status', 'toggleStatus')->name('publications.toggle-status');
        Route::post('publications/{publication}/toggle-direct-guideline', 'toggleDirectGuideline')->name('publications.toggle-direct-guideline');
        Route::post('publications/bulk-delete', 'bulkDelete')->name('publications.bulk-delete');

    });

    // Publications
    Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('publications', PublicationAdminController::class));

    // Scholarships
    Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('scholarships', ScholarshipAdminController::class));
    
    //Profile Management
    Route::get('/profile/edit', [ProfileManagementController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [ProfileManagementController::class, 'updateProfile'])->name('profile.update');

    //Board of Directors Management
    Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('board-of-directors', BoardOfDirectorController::class));

    //Executive Directors Management
    Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('executive-directors', ExecutiveDirectorAdminController::class));

    // User Stories Management
    Route::prefix('admin/user-stories')->name('admin.user-stories.')->group(function () {
        Route::get('/', [UserStoriesController::class, 'index'])->name('index');
        Route::get('/pending', [UserStoriesController::class, 'pending'])->name('pending');
        Route::get('/approved', [UserStoriesController::class, 'approved'])->name('approved');
        Route::get('/rejected', [UserStoriesController::class, 'rejected'])->name('rejected');
        Route::get('/{id}/edit', [UserStoriesController::class, 'edit'])->name('edit');
        Route::get('/{id}', [UserStoriesController::class, 'show'])->name('show');
        Route::put('/{id}', [UserStoriesController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserStoriesController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [UserStoriesController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [UserStoriesController::class, 'reject'])->name('reject');
        Route::post('/{id}/post', [UserStoriesController::class, 'post'])->name('post');
        Route::post('/{id}/unpost', [UserStoriesController::class, 'unpost'])->name('unpost');
    });

    // Loan Application FAQ Routes
    Route::prefix('admin/loan-application-faqs')->name('loan-application-faqs.')->group(fn() => Route::resource('/', LoanApplicationFAQController::class)->parameters(['' => 'faq']));

    // Loan Repayment FAQ Routes
    Route::prefix('admin/loan-repayment-faqs')->name('loan-repayment-faqs.')->group(fn() => Route::resource('/', LoanRepaymentFAQController::class)->parameters(['' => 'faq']));

    // FAQ Routes
    Route::prefix('admin')->group(fn() => Route::resource('faq', FAQController::class));

    // Validation Documentation
    Route::get('admin/validation-documentation', [ValidationDocumentationController::class, 'index'])->name('validation-documentation');

    // Feed back Management
    Route::prefix('adminpages')->name('adminpages.')->group(function () {
        Route::get('feedback', [FeedBackController::class, 'index'])->name('feedback.index');
        Route::get('feedback/seen', [FeedBackController::class, 'seen'])->name('feedback.seen');
        Route::get('feedback/deleted', [FeedBackController::class, 'deleted'])->name('feedback.deleted');
        Route::get('feedback/{id}', [FeedBackController::class, 'show'])->name('feedback.show');
        Route::get('feedback/{id}/print', [FeedBackController::class, 'print'])->name('feedback.print');
        Route::patch('feedback/{id}/mark-as-seen', [FeedBackController::class, 'markAsSeen'])->name('feedback.markAsSeen');
        Route::delete('feedback/{id}', [FeedBackController::class, 'destroy'])->name('feedback.destroy');

    }); 
 
    // Event Management
   Route::prefix('admin/taasisevents')->name('admin.taasisevents.')->group(function () {
        Route::get('/', [PhotoGalleryController::class, 'index'])->name('index');
        Route::get('/create', [PhotoGalleryController::class, 'create'])->name('create');
        Route::post('/', [PhotoGalleryController::class, 'store'])->name('store');
        Route::get('/{id}', [PhotoGalleryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PhotoGalleryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PhotoGalleryController::class, 'update'])->name('update');
        Route::delete('/{id}', [PhotoGalleryController::class, 'destroy'])->name('destroy');
    });

    // Image routes under the event prefix
    Route::prefix('admin/taasisevents')->name('admin.taasisevents.')->group(function () {
        Route::get('/{eventId}/images/add', [PhotoGalleryController::class, 'addImageForm'])->name('images.add');
        Route::post('/{eventId}/images/store', [PhotoGalleryController::class, 'storeImage'])->name('images.store');
        Route::get('/images/{id}/edit', [PhotoGalleryController::class, 'editImage'])->name('images.edit');
        Route::put('/images/{id}', [PhotoGalleryController::class, 'updateImage'])->name('images.update');
        Route::delete('/images/{id}', [PhotoGalleryController::class, 'destroyImage'])->name('images.destroy');
    });

    //Reset password
    Route::get('admin/users/{user}/reset-password', [UserManagementController::class, 'showResetPasswordForm'])->name('admin.users.reset-password.form');
    Route::post('admin/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('admin.users.reset-password');

    // User Management - resource + custom routes
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'check.user.status', 'prevent.blocked.actions'])->group(function () {
        Route::resource('users', UserManagementController::class)->names([
           'index' => 'users.index',
           'create' => 'users.create',
           'store' => 'users.store',
           'show' => 'users.show',
           'edit' => 'users.edit',
           'update' => 'users.update',
           'destroy' => 'users.destroy',
        ]);

    });

});


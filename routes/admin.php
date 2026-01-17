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
Route::middleware(['web'])->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login.form')
        ->middleware(['guest', 'cache.headers:private,no-store,must-revalidate', 'prevent.back.button']);

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit')
        ->middleware(['guest', 'cache.headers:private,no-store,must-revalidate', 'prevent.back.button', 'login.attempt.limiter']);

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});


//Admin pages
Route::middleware(['auth','prevent.blocked.actions', 'check.user.status'])->group(function () {
    
    //Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'showdashboard'])
        ->name('dashboard');

    // News page management system - requires manage_news permission
    Route::get('/newsdashboard', [NewsAndEventController::class, 'showallnews'])
        ->name('news.dashboard')
        ->middleware('permission:manage_news');

    // Video podcasts - requires manage_video_podcasts permission
    Route::middleware('permission:manage_video_podcasts')->group(function () {
        Route::resource('videopodcasts', VideoPodcastsController::class);
    });

    // Window applications - requires manage_applications permission
    Route::middleware('permission:manage_applications')->group(function () {
        Route::resource('admin/window-applications', WindowApplicationController::class)->names('admin.window_applications');
    });

    // Shortcut links - requires manage_shortcut_links permission
    Route::middleware('permission:manage_shortcut_links')->group(function () {
        Route::resource('shortcut-links', ShortCutLinksController::class);
    });

    // News page - requires manage_news permission
    Route::middleware('permission:manage_news')->group(function () {
        Route::resource('admin/news', NewsPagePublishController::class, ['as' => 'admin']);
    });

    // Strategic partners - requires manage_partners permission
    Route::middleware('permission:manage_partners')->group(function () {
        Route::resource('admin/partners', PartnerManageController::class)->names('admin.partners');
    });

    // Feedback - requires manage_feedback permission
    Route::middleware('permission:manage_feedback')->group(function () {
        Route::get('adminpages/feedback/type/{type}', [FeedBackController::class, 'byType'])->name('feedback.byType');
        Route::get('admin/contact-feedback', [AdminContactUsController::class, 'index'])->name('admin.feedback');
        Route::prefix('adminpages')->name('adminpages.')->group(function () {
            Route::get('feedback', [FeedBackController::class, 'index'])->name('feedback.index');
            Route::get('feedback/seen', [FeedBackController::class, 'seen'])->name('feedback.seen');
            Route::get('feedback/deleted', [FeedBackController::class, 'deleted'])->name('feedback.deleted');
            Route::get('feedback/{id}', [FeedBackController::class, 'show'])->name('feedback.show');
            Route::get('feedback/{id}/print', [FeedBackController::class, 'print'])->name('feedback.print');
            Route::patch('feedback/{id}/mark-as-seen', [FeedBackController::class, 'markAsSeen'])->name('feedback.markAsSeen');
            Route::delete('feedback/{id}', [FeedBackController::class, 'destroy'])->name('feedback.destroy');
        });
    });

    // Application guidelines - requires manage_application_guidelines permission
    Route::middleware('permission:manage_application_guidelines')->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('application-guidelines', ApplicationGuidelineController::class);
            Route::get('application-guidelines/{id}/download', [ApplicationGuidelineController::class, 'download'])->name('application-guidelines.download');
            Route::post('application-guidelines/{id}/set-current', [ApplicationGuidelineController::class, 'setCurrent'])->name('application-guidelines.set-current');
        });
    });

    // Publications - requires manage_publications permission
    Route::middleware('permission:manage_publications')->group(function () {
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
        Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('publications', PublicationAdminController::class));
    });

    // Scholarships - requires manage_scholarships permission
    Route::middleware('permission:manage_scholarships')->group(function () {
        Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('scholarships', ScholarshipAdminController::class));
    });
    
    //Profile Management - no permission required (users can manage their own profile)
    Route::get('/profile/edit', [ProfileManagementController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [ProfileManagementController::class, 'updateProfile'])->name('profile.update');

    //Board of Directors - requires manage_board_directors permission
    Route::middleware('permission:manage_board_directors')->group(function () {
        Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('board-of-directors', BoardOfDirectorController::class));
    });

    //Executive Directors - requires manage_executive_directors permission
    Route::middleware('permission:manage_executive_directors')->group(function () {
        Route::prefix('admin')->name('admin.')->group(fn() => Route::resource('executive-directors', ExecutiveDirectorAdminController::class));
    });

    // User Stories - requires manage_user_stories permission
    Route::middleware('permission:manage_user_stories')->group(function () {
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
    });

    // Loan Application FAQ - requires manage_loan_application_faqs permission
    Route::middleware('permission:manage_loan_application_faqs')->group(function () {
        Route::prefix('admin/loan-application-faqs')->name('loan-application-faqs.')->group(fn() => Route::resource('/', LoanApplicationFAQController::class)->parameters(['' => 'faq']));
    });

    // Loan Repayment FAQ - requires manage_loan_repayment_faqs permission
    Route::middleware('permission:manage_loan_repayment_faqs')->group(function () {
        Route::prefix('admin/loan-repayment-faqs')->name('loan-repayment-faqs.')->group(fn() => Route::resource('/', LoanRepaymentFAQController::class)->parameters(['' => 'faq']));
    });

    // FAQ - requires manage_faqs permission
    Route::middleware('permission:manage_faqs')->group(function () {
        Route::prefix('admin')->group(fn() => Route::resource('faq', FAQController::class));
    });

    // Validation Documentation - requires view_validation_documentation permission
    Route::get('admin/validation-documentation', [ValidationDocumentationController::class, 'index'])
        ->name('validation-documentation')
        ->middleware('permission:view_validation_documentation');

    // Event Management - requires manage_events permission
    Route::middleware('permission:manage_events')->group(function () {
        Route::prefix('admin/taasisevents')->name('admin.taasisevents.')->group(function () {
            Route::get('/', [PhotoGalleryController::class, 'index'])->name('index');
            Route::get('/create', [PhotoGalleryController::class, 'create'])->name('create');
            Route::post('/', [PhotoGalleryController::class, 'store'])->name('store');
            Route::get('/{id}', [PhotoGalleryController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PhotoGalleryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PhotoGalleryController::class, 'update'])->name('update');
            Route::delete('/{id}', [PhotoGalleryController::class, 'destroy'])->name('destroy');
            Route::get('/{eventId}/images/add', [PhotoGalleryController::class, 'addImageForm'])->name('images.add');
            Route::post('/{eventId}/images/store', [PhotoGalleryController::class, 'storeImage'])->name('images.store');
            Route::get('/images/{id}/edit', [PhotoGalleryController::class, 'editImage'])->name('images.edit');
            Route::put('/images/{id}', [PhotoGalleryController::class, 'updateImage'])->name('images.update');
            Route::delete('/images/{id}', [PhotoGalleryController::class, 'destroyImage'])->name('images.destroy');
        });
    });

    // Audit Logs - requires view_audit_logs permission
    Route::prefix('admin')->name('admin.')->middleware(['permission:view_audit_logs'])->group(function () {
        Route::get('audit-logs', [\App\Http\Controllers\AdminPages\AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('audit-logs/{auditLog}', [\App\Http\Controllers\AdminPages\AuditLogController::class, 'show'])->name('audit-logs.show');
    });

    // User Management - requires view_users permission for index/show, specific permissions for actions
    Route::prefix('admin')->name('admin.')->middleware(['permission:view_users'])->group(function () {
        // View routes - only need view_users permission
        Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
        
        // Create routes - require create_users permission (MUST come before {user} route to avoid conflicts)
        Route::middleware('permission:create_users')->group(function () {
            Route::get('users/create', [UserManagementController::class, 'create'])->name('users.create');
            Route::post('users', [UserManagementController::class, 'store'])->name('users.store');
        });
        
        // Show user details - must come after create
        Route::get('users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        
        // Edit routes - require edit_users permission
        Route::middleware('permission:edit_users')->group(function () {
            Route::get('users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        });
        
        // Block/Unblock - require manage_users permission
        Route::middleware('permission:manage_users')->group(function () {
            Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        });
        
        // Reset password - requires reset_user_password permission
        Route::middleware('permission:reset_user_password')->group(function () {
            Route::get('users/{user}/reset-password', [UserManagementController::class, 'showResetPasswordForm'])->name('users.reset-password.form');
            Route::post('users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
        });

        // Delete user (only if no data uploaded) - requires delete_users permission
        Route::middleware('permission:delete_users')->group(function () {
            Route::delete('users/{user}/delete', [UserManagementController::class, 'deleteUser'])->name('users.delete');
        });
    });
});


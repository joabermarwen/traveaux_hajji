<?php

use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::controller(SiteController::class)->group(function () {
    // Contact Routes
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit');

    //subscriptions
    Route::get('/subscriptions/all', 'subscriptions')->name('subscription.index');
    Route::get('/subscriptions/filter', 'filter_subscriptions')->name('subscription.filter');
    Route::post('/buy-subscription', 'buy_subscription')->name('subscription.buy');

    // Language Change Route
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    // Cookie Policy Routes
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    // Category Routes
    Route::get('category/list', 'categories')->name('category.list');
    Route::get('category/job/{id}/{name}', 'categoryJobs')->name('category.jobs');

    // Subcategory Routes
    Route::get('subcategory/{id}/{title}', 'subcategories')->name('subcategory.list');
    Route::get('subcategory/job/{id}/{name}', 'subcategoryJobs')->name('subcategory.jobs');

    // Blog Routes
    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');

    // Policy Pages Route
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    // Job Routes
    Route::get('job/list', 'allJobs')->name('job.list');
    Route::get('job-details/{id}/{title}', 'jobDetails')->name('job.details');
    Route::get('job/sort', 'sortJob')->name('job.sort');
    Route::get('job/pagination', 'sortJob')->name('job.pagination');
    Route::get('job/search', 'jobSearch')->name('job.search');

    // Placeholder Image and Maintenance Mode Routes
    Route::get('placeholder-image/{size}', 'placeholderImage')
        ->withoutMiddleware('maintenance') // Exclude maintenance middleware
        ->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')
        ->withoutMiddleware('maintenance') // Exclude maintenance middleware
        ->name('maintenance');

    // Dynamic Page and Home Routes
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
Route::get('/payment/success/{deposit_id}', [PaymentController::class,'success'])->name('payment.success');
Route::get('/payment/cancel/{deposit_id}', [PaymentController::class,'cancel'])->name('payment.cancel');

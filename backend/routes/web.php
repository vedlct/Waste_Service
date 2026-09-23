<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ContactEnquiryController;
use App\Http\Controllers\Admin\CoverageAreaController;
use App\Http\Controllers\Admin\CoverageRegionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExtraChargeController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LoadPackageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PriceCategoryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ServiceBlockItemController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceContentBlockController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceItemController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Auth::routes(['register' => false]);

Route::redirect('/home', '/admin')->name('home');

/*
 * Every admin route belongs to a module in config/admin_access.php, except the dashboard
 * and the user's own profile. AdminAccessTest fails if a route is left ungated.
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::middleware("can:access-module,'media'")->group(function () {
        Route::get('media/data', [MediaController::class, 'data'])->name('media.data');
        Route::get('media/options', [MediaController::class, 'options'])->name('media.options');
        Route::get('media/create', [MediaController::class, 'create'])->name('media.create');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::get('media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
        Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    });

    Route::middleware("can:access-module,'services'")->group(function () {
        Route::get('service-categories/data', [ServiceCategoryController::class, 'data'])->name('service-categories.data');
        Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
        Route::get('services/data', [ServiceController::class, 'data'])->name('services.data');
        Route::resource('services', ServiceController::class)->except(['show']);

        Route::prefix('services/{service}/blocks')->name('services.blocks.')->group(function () {
            Route::get('/', [ServiceContentBlockController::class, 'index'])->name('index');
            Route::get('create', [ServiceContentBlockController::class, 'create'])->name('create');
            Route::post('/', [ServiceContentBlockController::class, 'store'])->name('store');
            Route::get('{block}/edit', [ServiceContentBlockController::class, 'edit'])->name('edit');
            Route::put('{block}', [ServiceContentBlockController::class, 'update'])->name('update');
            Route::delete('{block}', [ServiceContentBlockController::class, 'destroy'])->name('destroy');

            Route::prefix('{block}/items')->name('items.')->group(function () {
                Route::get('create', [ServiceBlockItemController::class, 'create'])->name('create');
                Route::post('/', [ServiceBlockItemController::class, 'store'])->name('store');
                Route::get('{item}/edit', [ServiceBlockItemController::class, 'edit'])->name('edit');
                Route::put('{item}', [ServiceBlockItemController::class, 'update'])->name('update');
                Route::delete('{item}', [ServiceBlockItemController::class, 'destroy'])->name('destroy');
            });
        });
    });

    Route::middleware("can:access-module,'pricing'")->group(function () {
        Route::get('price-categories/data', [PriceCategoryController::class, 'data'])->name('price-categories.data');
        Route::resource('price-categories', PriceCategoryController::class)->except(['show']);
        Route::get('service-items/data', [ServiceItemController::class, 'data'])->name('service-items.data');
        Route::resource('service-items', ServiceItemController::class)->except(['show']);
        Route::get('load-packages/data', [LoadPackageController::class, 'data'])->name('load-packages.data');
        Route::resource('load-packages', LoadPackageController::class)->except(['show']);
        Route::get('extra-charges/data', [ExtraChargeController::class, 'data'])->name('extra-charges.data');
        Route::resource('extra-charges', ExtraChargeController::class)->except(['show']);
    });

    Route::middleware("can:access-module,'faqs'")->group(function () {
        Route::get('faqs/data', [FaqController::class, 'data'])->name('faqs.data');
        Route::resource('faqs', FaqController::class)->except(['show']);
    });

    Route::middleware("can:access-module,'reviews'")->group(function () {
        Route::get('reviews/data', [ReviewController::class, 'data'])->name('reviews.data');
        Route::patch('reviews/{review}/moderate', [ReviewController::class, 'moderate'])->name('reviews.moderate');
        Route::resource('reviews', ReviewController::class)->except(['show']);
    });

    Route::middleware("can:access-module,'coverage'")->group(function () {
        Route::get('coverage-regions/data', [CoverageRegionController::class, 'data'])->name('coverage-regions.data');
        Route::resource('coverage-regions', CoverageRegionController::class)->except(['show']);
        Route::get('coverage-areas/data', [CoverageAreaController::class, 'data'])->name('coverage-areas.data');
        Route::resource('coverage-areas', CoverageAreaController::class)->except(['show']);
    });

    Route::middleware("can:access-module,'pages'")->group(function () {
        // Edit only: the website's routes are built in code, so pages cannot be added here.
        Route::get('pages/data', [PageController::class, 'data'])->name('pages.data');
        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');
    });

    Route::middleware("can:access-module,'bookings'")->group(function () {
        Route::get('bookings/data', [BookingController::class, 'data'])->name('bookings.data');
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        // Declared before bookings/{booking}, or these paths would be read as a booking id.
        Route::get('bookings/day-sheet', [BookingController::class, 'daySheet'])->name('bookings.day-sheet');
        Route::get('bookings/export', [BookingController::class, 'export'])->name('bookings.export');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::put('bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
        Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    });

    Route::middleware("can:access-module,'enquiries'")->group(function () {
        Route::get('enquiries/data', [ContactEnquiryController::class, 'data'])->name('enquiries.data');
        Route::get('enquiries', [ContactEnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/{enquiry}', [ContactEnquiryController::class, 'show'])->name('enquiries.show');
        Route::put('enquiries/{enquiry}', [ContactEnquiryController::class, 'update'])->name('enquiries.update');
        Route::delete('enquiries/{enquiry}', [ContactEnquiryController::class, 'destroy'])->name('enquiries.destroy');
    });

    Route::middleware("can:access-module,'settings'")->group(function () {
        Route::get('settings/{group?}', [SiteSettingController::class, 'index'])->name('settings.index')->where('group', '[a-z0-9_-]+');
        Route::put('settings/{group}', [SiteSettingController::class, 'update'])->name('settings.update')->where('group', '[a-z0-9_-]+');
    });

    Route::middleware("can:access-module,'users'")->group(function () {
        Route::get('users/data', [UserController::class, 'data'])->name('users.data');
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::middleware("can:access-module,'activity'")->group(function () {
        Route::get('activity/data', [ActivityLogController::class, 'data'])->name('activity.data');
        Route::get('activity', [ActivityLogController::class, 'index'])->name('activity.index');
    });
});

<?php

use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\ContactEnquiryController;
use App\Http\Controllers\Api\V1\CoverageController;
use App\Http\Controllers\Api\V1\ExtraChargeController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\LoadPackageController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PriceCategoryController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SettingController;
use Illuminate\Support\Facades\Route;

/*
 * Versioned public API consumed by the Next.js frontend.
 *
 * Read endpoints only ever expose published/active records. Submit endpoints carry their
 * own rate limiter and the `public-form` middleware. The contract lives in `docs/api.md`.
 */
Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('health', fn () => response()->json([
        'ok' => true,
        'service' => config('app.name'),
        'time' => now()->toIso8601String(),
    ]))->name('health');

    Route::middleware('throttle:api')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');

        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('pages/{slug}', [PageController::class, 'show'])->name('pages.show');

        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('services/{slug}', [ServiceController::class, 'show'])->name('services.show');

        Route::get('price-categories', [PriceCategoryController::class, 'index'])->name('price-categories.index');
        Route::get('price-categories/{slug}/items', [PriceCategoryController::class, 'items'])->name('price-categories.items');

        Route::get('load-packages', [LoadPackageController::class, 'index'])->name('load-packages.index');
        Route::get('extra-charges', [ExtraChargeController::class, 'index'])->name('extra-charges.index');

        Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('coverage', [CoverageController::class, 'index'])->name('coverage.index');
    });

    Route::post('enquiries', [ContactEnquiryController::class, 'store'])
        ->middleware(['throttle:enquiries', 'public-form'])
        ->name('enquiries.store');

    Route::post('reviews', [ReviewController::class, 'store'])
        ->middleware(['throttle:reviews', 'public-form:'.config('reviews.submit.honeypot_field')])
        ->name('reviews.store');

    Route::post('bookings', [BookingController::class, 'store'])
        ->middleware(['throttle:bookings', 'public-form:'.config('bookings.submit.honeypot_field')])
        ->name('bookings.store');
});

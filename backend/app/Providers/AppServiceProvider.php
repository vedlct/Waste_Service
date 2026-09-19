<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        // `<script @nonce>` prints the request's Content-Security-Policy nonce. Every inline
        // script needs it, or the browser refuses to run it.
        Blade::directive('nonce', fn (): string => '<?php echo \'nonce="\'.e(\Illuminate\Support\Facades\Vite::cspNonce()).\'"\'; ?>');

        // Role-based module access. Unknown modules are denied, so new modules fail closed.
        Gate::define('access-module', fn (User $user, string $module): bool => in_array(
            $user->role,
            config("admin_access.modules.{$module}", []),
            true,
        ));

        // The Users list shows a "Last Login" column; nothing recorded it before.
        Event::listen(Login::class, function (Login $event): void {
            // An inactive account is signed back out by LoginController, so it does not count.
            if ($event->user instanceof User && $event->user->status === 'active') {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
            }
        });
    }

    /**
     * Public endpoints are limited per IP so a single client cannot flood them. The Next.js
     * server renders every visitor's page from one IP, so it identifies itself with the
     * shared FRONTEND_API_KEY and gets its own, much larger allowance.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $key = (string) config('services.frontend.api_key');

            if ($key !== '' && hash_equals($key, (string) $request->header('X-Frontend-Key'))) {
                return Limit::perMinute((int) config('services.frontend.rate_limit_per_minute', 1200))->by('frontend-server');
            }

            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('enquiries', fn (Request $request) => Limit::perMinute(
            (int) config('enquiries.submit.rate_limit_per_minute', 5)
        )->by($request->ip()));

        RateLimiter::for('reviews', fn (Request $request) => Limit::perMinute(
            (int) config('reviews.submit.rate_limit_per_minute', 3)
        )->by($request->ip()));

        RateLimiter::for('bookings', fn (Request $request) => Limit::perMinute(
            (int) config('bookings.submit.rate_limit_per_minute', 10)
        )->by($request->ip()));
    }
}

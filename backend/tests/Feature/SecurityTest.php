<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    private function user(string $status = 'active'): User
    {
        return User::query()->create([
            'name' => 'Security Test',
            'email' => 'security-'.Str::uuid()->toString().'@example.com',
            'password' => 'correct-password',
            'role' => 'admin',
            'status' => $status,
        ]);
    }

    public function test_an_inactive_account_cannot_sign_in(): void
    {
        $user = $this->user('inactive');

        $this->from(route('login'))
            ->post(route('login'), ['email' => $user->email, 'password' => 'correct-password'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'This account is inactive. Ask a super admin to reactivate it.']);

        $this->assertGuest();
        $this->assertNull($user->refresh()->last_login_at);
    }

    public function test_an_active_account_signs_in(): void
    {
        $user = $this->user();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'correct-password'])
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
    }

    public function test_repeated_failed_logins_are_throttled(): void
    {
        $user = $this->user();

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->from(route('login'))
                ->post(route('login'), ['email' => $user->email, 'password' => 'wrong-password'])
                ->assertSessionHasErrors('email');
        }

        // The sixth try is refused even with the right password.
        $this->from(route('login'))
            ->post(route('login'), ['email' => $user->email, 'password' => 'correct-password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
    }

    public function test_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_responses_carry_the_security_headers(): void
    {
        foreach ([route('login'), route('api.v1.health')] as $url) {
            $this->get($url)
                ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
                ->assertHeader('X-Content-Type-Options', 'nosniff')
                ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        }
    }

    public function test_the_admin_requires_a_signed_in_user(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->getJson(route('admin.bookings.data'))->assertUnauthorized();
    }

    public function test_private_settings_never_reach_the_public_api(): void
    {
        $this->getJson(route('api.v1.settings.index'))
            ->assertOk()
            ->assertJsonMissingPath('data.notifications');
    }
}

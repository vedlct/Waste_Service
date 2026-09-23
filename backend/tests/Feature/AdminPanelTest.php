<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    public function test_admin_pages_render_for_authenticated_users(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'admin-panel-test@example.com'],
            ['name' => 'Admin Panel Test', 'password' => 'password', 'role' => 'super_admin', 'status' => 'active'],
        );

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSee('Dashboard');

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Users')
            ->assertSee('Manage panel users');

        $this->actingAs($user)
            ->getJson(route('admin.users.data'))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee('status-pill', false);
    }

    public function test_admin_routes_require_active_admin_user(): void
    {
        $token = Str::uuid()->toString();

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $inactiveUser = User::query()->updateOrCreate(
            ['email' => "inactive-admin-{$token}@example.com"],
            ['name' => 'Inactive Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'inactive'],
        );

        $this->actingAs($inactiveUser)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $nonAdminUser = User::query()->updateOrCreate(
            ['email' => "non-admin-{$token}@example.com"],
            ['name' => 'Non Admin Test', 'password' => 'password', 'role' => 'customer', 'status' => 'active'],
        );

        $this->actingAs($nonAdminUser)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_authenticated_user_can_update_profile_and_password(): void
    {
        $token = Str::uuid()->toString();

        $user = User::query()->updateOrCreate(
            ['email' => "profile-test-{$token}@example.com"],
            ['name' => 'Profile Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        $this->actingAs($user)
            ->get(route('admin.profile.edit'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('My Profile')
            ->assertSee('Your admin account');

        $this->actingAs($user)
            ->put(route('admin.profile.update'), [
                'name' => 'Updated Profile Test',
                'email' => "profile-test-updated-{$token}@example.com",
            ])
            ->assertRedirect(route('admin.profile.edit'))
            ->assertSessionHas('success', 'Profile updated successfully.');

        $user->refresh();

        $this->assertSame('Updated Profile Test', $user->name);
        $this->assertSame("profile-test-updated-{$token}@example.com", $user->email);

        $this->actingAs($user)
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertRedirect(route('admin.profile.edit'))
            ->assertSessionHas('success', 'Password updated successfully.');

        $this->assertTrue(Hash::check('new-secure-password', $user->refresh()->password));
    }

    public function test_admin_can_create_and_update_users(): void
    {
        $token = Str::uuid()->toString();

        $admin = User::query()->updateOrCreate(
            ['email' => "crud-admin-{$token}@example.com"],
            ['name' => 'CRUD Admin Test', 'password' => 'password', 'role' => 'super_admin', 'status' => 'active'],
        );

        $userEmail = "created-user-{$token}@example.com";
        $updatedEmail = "updated-user-{$token}@example.com";

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Created User Test',
                'email' => $userEmail,
                'role' => 'editor',
                'status' => 'active',
                'password' => 'created-password',
                'password_confirmation' => 'created-password',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User created successfully.');

        $createdUser = User::query()->where('email', $userEmail)->firstOrFail();

        $this->assertSame('Created User Test', $createdUser->name);
        $this->assertSame('editor', $createdUser->role);
        $this->assertTrue(Hash::check('created-password', $createdUser->password));

        $this->actingAs($admin)
            ->put(route('admin.users.update', $createdUser), [
                'name' => 'Updated User Test',
                'email' => $updatedEmail,
                'role' => 'admin',
                'status' => 'inactive',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User updated successfully.');

        $createdUser->refresh();

        $this->assertSame('Updated User Test', $createdUser->name);
        $this->assertSame($updatedEmail, $createdUser->email);
        $this->assertSame('admin', $createdUser->role);
        $this->assertSame('inactive', $createdUser->status);
        $this->assertTrue(Hash::check('created-password', $createdUser->password));
    }

    public function test_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_password_recovery_views_use_admin_auth_shell(): void
    {
        $token = Str::uuid()->toString();

        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Reset your password')
            ->assertSee('Back to login');

        $this->get(route('password.reset', ['token' => 'test-token']))
            ->assertOk()
            ->assertSee('Set a new password')
            ->assertSee('Reset password');

        $user = User::query()->updateOrCreate(
            ['email' => "confirm-password-{$token}@example.com"],
            ['name' => 'Confirm Password Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        $this->actingAs($user)
            ->get(route('password.confirm'))
            ->assertOk()
            ->assertSee('Confirm your password');
    }
}

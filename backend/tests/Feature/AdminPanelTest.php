<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    private function useMysqlDatabase(): void
    {
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'mr_tee',
        ]);
    }

    public function test_admin_pages_render_for_authenticated_users(): void
    {
        $this->useMysqlDatabase();

        $user = User::query()->updateOrCreate(
            ['email' => 'admin-panel-test@example.com'],
            ['name' => 'Admin Panel Test', 'password' => 'password', 'role' => 'super_admin', 'status' => 'active'],
        );

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Manage panel users');

        $this->actingAs($user)
            ->getJson(route('admin.users.data'))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }

    public function test_authenticated_user_can_update_profile_and_password(): void
    {
        $this->useMysqlDatabase();

        $user = User::query()->updateOrCreate(
            ['email' => 'profile-test@example.com'],
            ['name' => 'Profile Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );

        $this->actingAs($user)
            ->get(route('admin.profile.edit'))
            ->assertOk()
            ->assertSee('Your admin account');

        $this->actingAs($user)
            ->put(route('admin.profile.update'), [
                'name' => 'Updated Profile Test',
                'email' => 'profile-test-updated@example.com',
            ])
            ->assertRedirect(route('admin.profile.edit'));

        $user->refresh();

        $this->assertSame('Updated Profile Test', $user->name);
        $this->assertSame('profile-test-updated@example.com', $user->email);

        $this->actingAs($user)
            ->put(route('admin.profile.password.update'), [
                'current_password' => 'password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertRedirect(route('admin.profile.edit'));

        $this->assertTrue(Hash::check('new-secure-password', $user->refresh()->password));
    }

    public function test_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }
}

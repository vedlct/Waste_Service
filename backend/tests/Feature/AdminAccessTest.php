<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\PriceCategory;
use App\Models\ServiceItem;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    private function userWithRole(string $role, string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "{$role}-access-{$token}@example.com"],
            ['name' => Str::headline($role).' Access Test', 'password' => 'password', 'role' => $role, 'status' => 'active'],
        );
    }

    public function test_every_admin_route_is_gated_by_a_known_module(): void
    {
        $open = config('admin_access.open_routes');
        $modules = array_keys(config('admin_access.modules'));
        $ungated = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            if (! $name || ! str_starts_with($name, 'admin.') || in_array($name, $open, true)) {
                continue;
            }

            $gate = collect($route->gatherMiddleware())
                ->first(fn ($middleware) => is_string($middleware) && str_starts_with($middleware, 'can:access-module,'));

            // The module name must be quoted, or Laravel resolves it as a route parameter.
            $module = Str::after((string) $gate, 'can:access-module,');
            $quoted = preg_match("/^'([a-z_]+)'$/", $module, $matches) === 1;

            if (! $gate || ! $quoted || ! in_array($matches[1], $modules, true)) {
                $ungated[] = $name;
            }
        }

        $this->assertSame([], $ungated, 'Every admin route needs a module in config/admin_access.php.');
    }

    public function test_super_admin_can_open_every_module(): void
    {
        foreach (config('admin_access.modules') as $module => $roles) {
            $this->assertContains('super_admin', $roles, "super_admin is missing from the {$module} module.");
        }
    }

    public function test_editor_can_manage_content_but_not_money_customers_or_users(): void
    {
        $editor = $this->userWithRole('editor', Str::uuid()->toString());

        foreach (['admin.services.index', 'admin.faqs.index', 'admin.reviews.index', 'admin.coverage-areas.index', 'admin.media.index'] as $route) {
            $this->actingAs($editor)->get(route($route))->assertOk();
        }

        foreach ([
            'admin.service-items.index',
            'admin.load-packages.index',
            'admin.bookings.index',
            'admin.enquiries.index',
            'admin.settings.index',
            'admin.users.index',
            'admin.activity.index',
        ] as $route) {
            $this->actingAs($editor)->get(route($route))->assertForbidden();
        }

        $this->actingAs($editor)->get(route('admin.dashboard'))->assertOk()->assertDontSee('Service Items')->assertDontSee('Activity Log');

        $editor->delete();
    }

    public function test_admin_can_manage_operations_but_not_users_or_the_audit_trail(): void
    {
        $admin = $this->userWithRole('admin', Str::uuid()->toString());

        foreach (['admin.bookings.index', 'admin.enquiries.index', 'admin.service-items.index', 'admin.settings.index'] as $route) {
            // Settings redirects to its first group, so anything below 400 means allowed.
            $this->assertLessThan(400, $this->actingAs($admin)->get(route($route))->status(), $route);
        }

        $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.activity.index'))->assertForbidden();

        $admin->delete();
    }

    public function test_nobody_can_change_their_own_role_or_status(): void
    {
        $superAdmin = $this->userWithRole('super_admin', Str::uuid()->toString());

        $this->actingAs($superAdmin)
            ->from(route('admin.users.edit', $superAdmin))
            ->put(route('admin.users.update', $superAdmin), [
                'name' => $superAdmin->name,
                'email' => $superAdmin->email,
                'role' => 'editor',
                'status' => 'active',
            ])
            ->assertSessionHasErrors('role');

        $this->assertSame('super_admin', $superAdmin->refresh()->role);

        $superAdmin->delete();
    }

    public function test_the_last_active_super_admin_cannot_be_demoted_or_deleted(): void
    {
        $token = Str::uuid()->toString();

        // Today only super admins reach the users module, so there are always two when one
        // edits another. The guard exists for the day the module is opened to admins, so
        // open it here and act as an admin against the only active super admin.
        config(['admin_access.modules.users' => ['super_admin', 'admin']]);

        $others = User::query()->where('role', 'super_admin')->where('status', 'active')->get(['id']);
        User::query()->whereIn('id', $others->pluck('id'))->update(['status' => 'inactive']);

        $actor = $this->userWithRole('admin', $token);
        $last = User::query()->create([
            'name' => 'Last Super Admin',
            'email' => "last-super-{$token}@example.com",
            'password' => 'password',
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        try {
            $this->assertSame(1, User::activeSuperAdminCount());

            $this->actingAs($actor)
                ->from(route('admin.users.edit', $last))
                ->put(route('admin.users.update', $last), [
                    'name' => $last->name,
                    'email' => $last->email,
                    'role' => 'admin',
                    'status' => 'active',
                ])
                ->assertSessionHasErrors('role');

            $this->actingAs($actor)
                ->from(route('admin.users.index'))
                ->delete(route('admin.users.destroy', $last))
                ->assertSessionHas('error');

            $this->assertDatabaseHas('users', ['id' => $last->id, 'role' => 'super_admin']);
        } finally {
            $last->delete();
            $actor->delete();
            User::query()->whereIn('id', $others->pluck('id'))->update(['status' => 'active']);
        }
    }

    public function test_admin_changes_are_written_to_the_activity_log(): void
    {
        $token = Str::uuid()->toString();
        $superAdmin = $this->userWithRole('super_admin', $token);

        $item = ServiceItem::query()->create([
            'price_category_id' => PriceCategory::query()->firstOrFail()->id,
            'sku' => "AUDIT-{$token}",
            'slug' => "audit-item-{$token}",
            'name' => "Audit Item {$token}",
            'price_pence' => 5000,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'placeholder',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        // Created outside a request with nobody signed in: not part of the audit trail.
        $this->assertSame(0, ActivityLog::query()->where('subject_type', $item->getMorphClass())->where('subject_id', $item->id)->count());

        $this->actingAs($superAdmin)
            ->put(route('admin.service-items.update', $item), [
                'price_category_id' => $item->price_category_id,
                'name' => $item->name,
                'slug' => $item->slug,
                'sku' => $item->sku,
                'price' => '62.50',
                'vat_rate_percent' => '20',
                'pricing_status' => 'confirmed',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertRedirect(route('admin.service-items.index'));

        $log = ActivityLog::query()
            ->where('subject_type', $item->getMorphClass())
            ->where('subject_id', $item->id)
            ->where('action', 'updated')
            ->latest('id')
            ->firstOrFail();

        $this->assertSame($superAdmin->id, $log->user_id);
        // MySQL normalises JSON key order, so compare without caring about it.
        $this->assertEquals(['old' => 5000, 'new' => 6250], $log->changes['price_pence']);
        $this->assertEquals(['old' => 'placeholder', 'new' => 'confirmed'], $log->changes['pricing_status']);
        $this->assertStringContainsString("Audit Item {$token}", $log->description);

        $this->actingAs($superAdmin)
            ->get(route('admin.activity.index'))
            ->assertOk()
            ->assertSee('Admin activity');

        $this->actingAs($superAdmin)
            ->getJson(route('admin.activity.data', ['search' => ['value' => $token]]))
            ->assertOk()
            ->assertSee('Price Pence');

        ActivityLog::query()->where('subject_type', $item->getMorphClass())->where('subject_id', $item->id)->delete();
        $item->forceDelete();
        $superAdmin->delete();
    }

    public function test_password_changes_never_reach_the_activity_log(): void
    {
        $token = Str::uuid()->toString();
        $superAdmin = $this->userWithRole('super_admin', $token);
        $target = $this->userWithRole('editor', $token);

        $this->actingAs($superAdmin)
            ->put(route('admin.users.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'role' => 'editor',
                'status' => 'active',
                'password' => 'a-new-password',
                'password_confirmation' => 'a-new-password',
            ])
            ->assertRedirect(route('admin.users.index'));

        $logs = ActivityLog::query()->where('subject_type', $target->getMorphClass())->where('subject_id', $target->id)->get();

        foreach ($logs as $log) {
            $this->assertArrayNotHasKey('password', $log->changes ?? []);
            $this->assertStringNotContainsString('a-new-password', json_encode($log->changes));
        }

        ActivityLog::query()->where('subject_type', $target->getMorphClass())->where('subject_id', $target->id)->delete();
        $target->delete();
        $superAdmin->delete();
    }
}

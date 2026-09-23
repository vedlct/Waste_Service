<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ContactEnquiry;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    private function userWithRole(string $role): User
    {
        return User::query()->create([
            'name' => Str::headline($role).' Dashboard',
            'email' => $role.'-dashboard-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => $role,
            'status' => 'active',
        ]);
    }

    public function test_the_dashboard_shows_the_work_waiting_on_the_office(): void
    {
        $admin = $this->userWithRole('admin');

        Booking::query()->create([
            'reference' => 'MT-TEST-0001',
            'status' => 'submitted',
            'payment_status' => 'unpaid',
            'payment_option' => 'pay_on_arrival',
            'collection_date' => now()->addDays(3)->toDateString(),
            'total_pence' => 12500,
        ]);

        ContactEnquiry::query()->create([
            'name' => 'Waiting Customer',
            'email' => 'waiting@example.com',
            'phone' => '02012345678',
            'service_label' => 'House clearance',
            'message' => 'Please call me back about a clearance.',
            'status' => 'new',
            'source' => 'website',
        ]);

        Review::query()->create([
            'reviewer_name' => 'Pending Visitor',
            'rating' => 5,
            'body' => 'Waiting to be moderated.',
            'source' => 'website',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Waiting on you')
            ->assertSee('Bookings to confirm')
            ->assertSee('Unassigned enquiries')
            ->assertSee('Reviews to moderate')
            ->assertSee('Placeholder prices')
            ->assertSee('Upcoming collections')
            ->assertSee('MT-TEST-0001')
            ->assertSee('Waiting Customer')
            ->assertSee(route('admin.bookings.index', ['status' => 'submitted']), false)
            ->assertDontSee('Recent users');
    }

    public function test_an_editor_only_sees_content_sections(): void
    {
        $editor = $this->userWithRole('editor');

        $this->actingAs($editor)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Reviews to moderate')
            ->assertSee('Pages missing SEO')
            ->assertDontSee('Bookings to confirm')
            ->assertDontSee('Unassigned enquiries')
            ->assertDontSee('Placeholder prices')
            ->assertDontSee('Upcoming collections')
            ->assertDontSee('Open enquiries');
    }

    public function test_dashboard_links_open_the_list_with_the_filter_preselected(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.bookings.index', ['status' => 'submitted']))
            ->assertOk()
            ->assertSee('<option value="submitted" selected', false);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index', ['scope' => 'unassigned']))
            ->assertOk()
            ->assertSee('<option value="unassigned" selected', false);

        $this->actingAs($admin)
            ->get(route('admin.service-items.index', ['pricing_status' => 'placeholder']))
            ->assertOk()
            ->assertSee('<option value="placeholder" selected', false);
    }
}

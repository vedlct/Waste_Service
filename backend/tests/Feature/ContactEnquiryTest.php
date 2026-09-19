<?php

namespace Tests\Feature;

use App\Models\ContactEnquiry;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactEnquiryTest extends TestCase
{
    private function admin(string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "enquiry-admin-{$token}@example.com"],
            ['name' => 'Enquiry Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );
    }

    /**
     * @return array<string, string>
     */
    private function payload(string $token, array $overrides = []): array
    {
        return array_merge([
            'name' => "Test Customer {$token}",
            'phone' => '02012345678',
            'email' => "customer-{$token}@example.com",
            'service' => 'house',
            'message' => 'I need a full house clearance next week, please call me back.',
        ], $overrides);
    }

    public function test_api_health_endpoint_responds(): void
    {
        $this->getJson(route('api.v1.health'))
            ->assertOk()
            ->assertJson(['ok' => true])
            ->assertJsonStructure(['ok', 'service', 'time']);
    }

    public function test_public_submit_stores_the_enquiry_and_links_the_service(): void
    {
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.enquiries.store'), $this->payload($token))
            ->assertCreated()
            ->assertJson(['ok' => true])
            ->assertJsonStructure(['ok', 'message', 'reference']);

        $enquiry = ContactEnquiry::query()->where('email', "customer-{$token}@example.com")->firstOrFail();

        $this->assertSame('new', $enquiry->status);
        $this->assertSame('website', $enquiry->source);
        $this->assertSame('House clearance', $enquiry->service_label);
        $this->assertNotNull($enquiry->ip_address);
        $this->assertNull($enquiry->responded_at);

        // The picked option resolves to the seeded service record.
        $houseClearance = Service::query()->where('slug', 'house-clearance')->first();
        $this->assertSame($houseClearance?->id, $enquiry->service_id);

        $enquiry->forceDelete();
    }

    public function test_other_service_option_stores_a_label_without_a_service_link(): void
    {
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.enquiries.store'), $this->payload($token, ['service' => 'other']))
            ->assertCreated();

        $enquiry = ContactEnquiry::query()->where('email', "customer-{$token}@example.com")->firstOrFail();

        $this->assertNull($enquiry->service_id);
        $this->assertSame('Other service', $enquiry->service_label);

        $enquiry->forceDelete();
    }

    public function test_honeypot_submissions_are_accepted_silently_and_stored_nowhere(): void
    {
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.enquiries.store'), $this->payload($token, ['company' => 'Spam Bot Ltd']))
            ->assertOk()
            ->assertExactJson(['ok' => true]);

        $this->assertDatabaseMissing('contact_enquiries', ['email' => "customer-{$token}@example.com"]);
    }

    public function test_public_submit_validates_the_same_rules_as_the_website_form(): void
    {
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.enquiries.store'), $this->payload($token, ['email' => 'not-an-email']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->postJson(route('api.v1.enquiries.store'), $this->payload($token, ['message' => 'too short']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('message');

        $this->postJson(route('api.v1.enquiries.store'), $this->payload($token, ['service' => 'nope']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('service');

        $this->assertDatabaseMissing('contact_enquiries', ['email' => "customer-{$token}@example.com"]);
    }

    public function test_oversized_submissions_are_refused(): void
    {
        $token = Str::uuid()->toString();

        $this->postJson(
            route('api.v1.enquiries.store'),
            $this->payload($token),
            ['Content-Length' => '30000'],
        )->assertStatus(413);

        $this->assertDatabaseMissing('contact_enquiries', ['email' => "customer-{$token}@example.com"]);
    }

    public function test_public_submit_is_rate_limited_per_ip(): void
    {
        $limit = (int) config('enquiries.submit.rate_limit_per_minute');

        for ($attempt = 1; $attempt <= $limit; $attempt++) {
            $token = Str::uuid()->toString();

            $this->postJson(route('api.v1.enquiries.store'), $this->payload($token))
                ->assertCreated();

            ContactEnquiry::query()->where('email', "customer-{$token}@example.com")->forceDelete();
        }

        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.enquiries.store'), $this->payload($token))
            ->assertStatus(429);

        $this->assertDatabaseMissing('contact_enquiries', ['email' => "customer-{$token}@example.com"]);
    }

    public function test_admin_can_view_the_enquiry_list_and_data(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $enquiry = ContactEnquiry::query()->create([
            'name' => "List Customer {$token}",
            'email' => "list-{$token}@example.com",
            'phone' => '02012345678',
            'service_label' => 'House clearance',
            'message' => 'Please quote for a two bedroom flat clearance.',
            'status' => 'new',
            'source' => 'website',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.index'))
            ->assertOk()
            ->assertSee('Manage enquiries');

        $this->actingAs($admin)
            ->getJson(route('admin.enquiries.data', ['scope' => 'unassigned', 'search' => ['value' => $token]]))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee("List Customer {$token}");

        $enquiry->forceDelete();
    }

    public function test_status_drives_the_responded_timestamp_and_assignment_is_saved(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $enquiry = ContactEnquiry::query()->create([
            'name' => "Workflow Customer {$token}",
            'email' => "workflow-{$token}@example.com",
            'phone' => '02012345678',
            'service_label' => 'Garden clearance',
            'message' => 'Garden waste needs collecting this weekend.',
            'status' => 'new',
            'source' => 'website',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.enquiries.show', $enquiry))
            ->assertOk()
            ->assertSee("Workflow Customer {$token}")
            ->assertSee('Garden waste needs collecting this weekend.')
            ->assertSee('Not yet');

        $this->actingAs($admin)
            ->put(route('admin.enquiries.update', $enquiry), [
                'status' => 'responded',
                'assigned_to' => $admin->id,
            ])
            ->assertRedirect(route('admin.enquiries.show', $enquiry))
            ->assertSessionHas('success', 'Enquiry updated successfully.');

        $enquiry->refresh();

        $this->assertSame('responded', $enquiry->status);
        $this->assertSame($admin->id, $enquiry->assigned_to);
        $this->assertNotNull($enquiry->responded_at);

        // Sending it back to an open status clears the stamp again.
        $this->actingAs($admin)
            ->put(route('admin.enquiries.update', $enquiry), [
                'status' => 'in_progress',
                'assigned_to' => '',
            ])
            ->assertRedirect(route('admin.enquiries.show', $enquiry));

        $enquiry->refresh();

        $this->assertSame('in_progress', $enquiry->status);
        $this->assertNull($enquiry->assigned_to);
        $this->assertNull($enquiry->responded_at);

        $enquiry->forceDelete();
    }

    public function test_enquiry_delete_is_a_soft_delete(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $enquiry = ContactEnquiry::query()->create([
            'name' => "Deletable Customer {$token}",
            'email' => "deletable-{$token}@example.com",
            'phone' => '02012345678',
            'message' => 'Spam looking message.',
            'status' => 'spam',
            'source' => 'website',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.enquiries.destroy', $enquiry))
            ->assertRedirect(route('admin.enquiries.index'));

        $this->assertSoftDeleted('contact_enquiries', ['id' => $enquiry->id]);

        $enquiry->forceDelete();
    }

    public function test_open_scope_excludes_responded_closed_and_spam(): void
    {
        $this->assertSame(['new', 'in_progress'], ContactEnquiry::openStatuses());
    }
}

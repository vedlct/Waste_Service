<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class FaqReviewTest extends TestCase
{
    private function admin(string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "content-admin-{$token}@example.com"],
            ['name' => 'Content Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );
    }

    public function test_admin_can_view_faq_and_review_lists_and_data(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $this->actingAs($admin)->get(route('admin.faqs.index'))->assertOk()->assertSee('Manage FAQs');
        $this->actingAs($admin)->get(route('admin.reviews.index'))->assertOk()->assertSee('Moderate reviews');

        $this->actingAs($admin)
            ->getJson(route('admin.faqs.data'))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee('status-pill', false);

        $this->actingAs($admin)
            ->getJson(route('admin.reviews.data'))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee('bi-star', false);
    }

    public function test_admin_can_create_a_general_faq_and_assign_it_to_a_service(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $service = Service::query()->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.faqs.store'), [
                'service_id' => '',
                'question' => "Do you recycle? {$token}",
                'answer' => 'Yes, we recycle wherever possible.',
                'is_active' => '1',
                'sort_order' => '42',
            ])
            ->assertRedirect(route('admin.faqs.index'))
            ->assertSessionHas('success', 'FAQ created successfully.');

        $faq = Faq::query()->where('question', "Do you recycle? {$token}")->firstOrFail();

        $this->assertNull($faq->service_id);
        $this->assertTrue($faq->is_active);

        $this->actingAs($admin)
            ->put(route('admin.faqs.update', $faq), [
                'service_id' => $service->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'is_active' => '0',
                'sort_order' => '1',
            ])
            ->assertRedirect(route('admin.faqs.index'));

        $faq->refresh();

        $this->assertSame($service->id, $faq->service_id);
        $this->assertFalse($faq->is_active);

        $faq->delete();
    }

    public function test_faq_list_can_be_filtered_to_general_questions_only(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $service = Service::query()->firstOrFail();

        $general = Faq::query()->create([
            'question' => "General question {$token}",
            'answer' => 'General answer.',
            'is_active' => true,
            'sort_order' => 900,
        ]);

        $assigned = Faq::query()->create([
            'service_id' => $service->id,
            'question' => "Service question {$token}",
            'answer' => 'Service answer.',
            'is_active' => true,
            'sort_order' => 901,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.faqs.data', ['scope' => 'general', 'search' => ['value' => $token]]))
            ->assertOk()
            ->assertSee("General question {$token}")
            ->assertDontSee("Service question {$token}");

        $this->actingAs($admin)
            ->getJson(route('admin.faqs.data', ['service_id' => $service->id, 'search' => ['value' => $token]]))
            ->assertOk()
            ->assertSee("Service question {$token}")
            ->assertDontSee("General question {$token}");

        $general->delete();
        $assigned->delete();
    }

    public function test_deleting_a_service_removes_its_faqs(): void
    {
        $token = Str::uuid()->toString();

        $service = Service::query()->create([
            'name' => "FAQ Owner Service {$token}",
            'slug' => "faq-owner-service-{$token}",
            'route_path' => "/faq-owner-service-{$token}",
            'status' => 'draft',
            'is_featured' => false,
            'is_bookable' => true,
            'sort_order' => 0,
        ]);

        $faq = Faq::query()->create([
            'service_id' => $service->id,
            'question' => "Cascade question {$token}",
            'answer' => 'Cascade answer.',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        // Soft deleting the service leaves the FAQ alone; the cascade only fires on a hard delete.
        $service->delete();
        $this->assertDatabaseHas('faqs', ['id' => $faq->id]);

        $service->forceDelete();
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_review_status_drives_the_moderation_timestamps(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $this->actingAs($admin)
            ->post(route('admin.reviews.store'), [
                'service_id' => '',
                'reviewer_name' => "Test Reviewer {$token}",
                'reviewer_email' => 'reviewer@example.com',
                'rating' => '5',
                'body' => 'Great service from start to finish.',
                'source' => 'manual',
                'status' => 'pending',
            ])
            ->assertRedirect(route('admin.reviews.index'))
            ->assertSessionHas('success', 'Review created successfully.');

        $review = Review::query()->where('reviewer_name', "Test Reviewer {$token}")->firstOrFail();

        $this->assertSame('pending', $review->status);
        $this->assertNull($review->reviewed_at);
        $this->assertNull($review->published_at);
        $this->assertFalse($review->is_published);

        // Publishing stamps both timestamps.
        $this->actingAs($admin)
            ->patch(route('admin.reviews.moderate', $review), ['status' => 'published'])
            ->assertRedirect(route('admin.reviews.index'))
            ->assertSessionHas('success', 'Review marked as Published.');

        $review->refresh();

        $this->assertSame('published', $review->status);
        $this->assertNotNull($review->reviewed_at);
        $this->assertNotNull($review->published_at);
        $this->assertTrue($review->is_published);

        // Rejecting keeps the moderation stamp but clears the publish stamp.
        $this->actingAs($admin)
            ->patch(route('admin.reviews.moderate', $review), ['status' => 'rejected'])
            ->assertRedirect(route('admin.reviews.index'));

        $review->refresh();

        $this->assertSame('rejected', $review->status);
        $this->assertNotNull($review->reviewed_at);
        $this->assertNull($review->published_at);

        // Sending it back to pending clears both.
        $this->actingAs($admin)
            ->patch(route('admin.reviews.moderate', $review), ['status' => 'pending'])
            ->assertRedirect(route('admin.reviews.index'));

        $review->refresh();

        $this->assertNull($review->reviewed_at);
        $this->assertNull($review->published_at);

        $review->delete();
    }

    public function test_published_scope_only_returns_published_reviews(): void
    {
        $token = Str::uuid()->toString();

        $published = Review::query()->create([
            'reviewer_name' => "Published Reviewer {$token}",
            'rating' => 5,
            'body' => 'Published body.',
            'source' => 'website',
            'status' => 'published',
            'reviewed_at' => now(),
            'published_at' => now(),
        ]);

        $pending = Review::query()->create([
            'reviewer_name' => "Pending Reviewer {$token}",
            'rating' => 4,
            'body' => 'Pending body.',
            'source' => 'website',
            'status' => 'pending',
        ]);

        $ids = Review::query()->published()->pluck('id');

        $this->assertTrue($ids->contains($published->id));
        $this->assertFalse($ids->contains($pending->id));
        $this->assertSame('published', Review::publishedStatus());

        $published->delete();
        $pending->delete();
    }

    public function test_review_validation_rejects_an_out_of_range_rating(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $this->actingAs($admin)
            ->from(route('admin.reviews.create'))
            ->post(route('admin.reviews.store'), [
                'reviewer_name' => "Bad Rating {$token}",
                'rating' => '9',
                'body' => 'Rating is out of range.',
                'source' => 'manual',
                'status' => 'published',
            ])
            ->assertSessionHasErrors('rating');

        $this->assertDatabaseMissing('reviews', ['reviewer_name' => "Bad Rating {$token}"]);
    }

    public function test_faq_and_review_form_screens_render(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $faq = Faq::query()->create([
            'question' => "Form question {$token}",
            'answer' => 'Form answer.',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $review = Review::query()->create([
            'reviewer_name' => "Form Reviewer {$token}",
            'rating' => 4,
            'body' => 'Form body.',
            'source' => 'website',
            'status' => 'pending',
        ]);

        $screens = [
            route('admin.faqs.create') => 'FAQ details',
            route('admin.faqs.edit', $faq) => 'FAQ details',
            route('admin.reviews.create') => 'Review details',
            route('admin.reviews.edit', $review) => 'Review details',
        ];

        foreach ($screens as $url => $expected) {
            $this->actingAs($admin)->get($url)->assertOk()->assertSee($expected);
        }

        $this->actingAs($admin)
            ->get(route('admin.reviews.edit', $review))
            ->assertSee('Moderation')
            ->assertSee('Not yet');

        $faq->delete();
        $review->delete();
    }
}

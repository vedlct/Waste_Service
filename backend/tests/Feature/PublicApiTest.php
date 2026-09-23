<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    public function test_the_frontend_server_key_lifts_the_per_ip_rate_limit(): void
    {
        config(['services.frontend.api_key' => 'test-frontend-key', 'services.frontend.rate_limit_per_minute' => 1200]);

        $limitFor = function (?string $key) {
            $request = Request::create('/api/v1/settings', 'GET', server: ['REMOTE_ADDR' => '10.0.0.9']);

            if ($key !== null) {
                $request->headers->set('X-Frontend-Key', $key);
            }

            return RateLimiter::limiter('api')($request);
        };

        $this->assertSame(1200, $limitFor('test-frontend-key')->maxAttempts);
        $this->assertSame('frontend-server', $limitFor('test-frontend-key')->key);
        $this->assertSame(60, $limitFor('wrong-key')->maxAttempts);
        $this->assertSame(60, $limitFor(null)->maxAttempts);

        // With no key configured, an empty header must not match.
        config(['services.frontend.api_key' => '']);
        $this->assertSame(60, $limitFor('')->maxAttempts);
    }

    public function test_settings_endpoint_exposes_only_public_settings(): void
    {
        $setting = SiteSetting::query()->where('group', 'contact')->where('key', 'phone')->firstOrFail();
        $wasPublic = $setting->is_public;

        $this->getJson(route('api.v1.settings.index'))
            ->assertOk()
            ->assertJsonPath('data.contact.phone', $setting->value)
            ->assertJsonPath('data.general.site_name', 'MR. TEE Removals');

        $setting->update(['is_public' => false]);

        $this->getJson(route('api.v1.settings.index'))
            ->assertOk()
            ->assertJsonMissingPath('data.contact.phone');

        $setting->update(['is_public' => $wasPublic]);
    }

    public function test_services_index_returns_published_services_with_a_stable_shape(): void
    {
        $this->getJson(route('api.v1.services.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [[
                    'slug', 'route_path', 'name', 'short_name', 'headline', 'summary',
                    'is_featured', 'is_bookable', 'sort_order', 'category', 'hero_image',
                ]],
            ]);

        $this->getJson(route('api.v1.services.index', ['featured' => 1]))
            ->assertOk()
            ->assertJsonPath('data.0.is_featured', true);
    }

    public function test_unpublished_services_are_hidden_from_the_api(): void
    {
        $token = Str::uuid()->toString();

        $service = Service::query()->create([
            'name' => "Hidden Service {$token}",
            'slug' => "hidden-service-{$token}",
            'route_path' => "/hidden-service-{$token}",
            'status' => 'draft',
            'is_featured' => false,
            'is_bookable' => true,
            'sort_order' => 999,
        ]);

        $this->getJson(route('api.v1.services.index'))
            ->assertOk()
            ->assertDontSee("hidden-service-{$token}");

        $this->getJson(route('api.v1.services.show', ['slug' => $service->slug]))
            ->assertNotFound();

        $service->forceDelete();
    }

    public function test_service_detail_includes_blocks_related_services_and_faqs(): void
    {
        $service = Service::query()->published()->firstOrFail();

        $this->getJson(route('api.v1.services.show', ['slug' => $service->slug]))
            ->assertOk()
            ->assertJsonPath('data.slug', $service->slug)
            ->assertJsonStructure([
                'data' => [
                    'slug', 'name', 'headline', 'summary', 'description',
                    'category', 'seo', 'hero_image', 'blocks', 'related', 'faqs',
                ],
            ]);
    }

    public function test_price_catalogue_endpoints_return_money_in_pence_and_formatted(): void
    {
        $this->getJson(route('api.v1.price-categories.index'))
            ->assertOk()
            ->assertJsonStructure(['data' => [['slug', 'name', 'eyebrow', 'item_count', 'image']]]);

        $category = \App\Models\PriceCategory::query()->active()->firstOrFail();

        $response = $this->getJson(route('api.v1.price-categories.items', ['slug' => $category->slug]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [[
                    'id', 'sku', 'slug', 'name', 'price' => ['pence', 'formatted', 'currency'],
                    'price_ex_vat', 'vat_rate', 'pricing_status', 'requires_quote', 'is_provisional',
                ]],
            ]);

        $first = $response->json('data.0');
        $this->assertIsInt($first['price']['pence']);
        $this->assertStringStartsWith('£', $first['price']['formatted']);
        $this->assertSame('GBP', $first['price']['currency']);
    }

    public function test_load_packages_and_extra_charges_expose_their_pricing_status(): void
    {
        $this->getJson(route('api.v1.load-packages.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'slug', 'name', 'price', 'capacity', 'pricing_status', 'is_provisional', 'is_popular']],
            ]);

        $charges = $this->getJson(route('api.v1.extra-charges.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['slug', 'name', 'charge_type', 'is_variable', 'amount', 'pricing_status']],
            ])
            ->json('data');

        // A variable charge is quoted per job, so it must not carry an amount.
        foreach ($charges as $charge) {
            if ($charge['is_variable']) {
                $this->assertNull($charge['amount']);
            }
        }
    }

    public function test_faqs_endpoint_defaults_to_general_questions(): void
    {
        $token = Str::uuid()->toString();
        $service = Service::query()->published()->firstOrFail();

        $serviceFaq = Faq::query()->create([
            'service_id' => $service->id,
            'question' => "Service only question {$token}",
            'answer' => 'Service answer.',
            'is_active' => true,
            'sort_order' => 900,
        ]);

        $this->getJson(route('api.v1.faqs.index'))
            ->assertOk()
            ->assertDontSee("Service only question {$token}");

        $this->getJson(route('api.v1.faqs.index', ['service' => $service->slug]))
            ->assertOk()
            ->assertSee("Service only question {$token}");

        $serviceFaq->delete();
    }

    public function test_inactive_faqs_are_hidden(): void
    {
        $token = Str::uuid()->toString();

        $faq = Faq::query()->create([
            'question' => "Inactive question {$token}",
            'answer' => 'Inactive answer.',
            'is_active' => false,
            'sort_order' => 901,
        ]);

        $this->getJson(route('api.v1.faqs.index'))
            ->assertOk()
            ->assertDontSee("Inactive question {$token}");

        $faq->delete();
    }

    public function test_reviews_endpoint_only_returns_published_reviews_and_hides_emails(): void
    {
        $token = Str::uuid()->toString();

        $pending = Review::query()->create([
            'reviewer_name' => "Pending Public {$token}",
            'reviewer_email' => "pending-{$token}@example.com",
            'rating' => 5,
            'body' => 'Pending review body.',
            'source' => 'website',
            'status' => 'pending',
        ]);

        $published = Review::query()->create([
            'reviewer_name' => "Published Public {$token}",
            'reviewer_email' => "published-{$token}@example.com",
            'rating' => 5,
            'body' => 'Published review body.',
            'source' => 'website',
            'status' => 'published',
            'reviewed_at' => now(),
            'published_at' => now(),
        ]);

        $this->getJson(route('api.v1.reviews.index', ['limit' => 50]))
            ->assertOk()
            ->assertSee("Published Public {$token}")
            ->assertDontSee("Pending Public {$token}")
            ->assertDontSee("published-{$token}@example.com");

        $pending->delete();
        $published->delete();
    }

    public function test_coverage_endpoint_nests_active_areas_under_active_regions(): void
    {
        $this->getJson(route('api.v1.coverage.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [[
                    'slug', 'name', 'sort_order',
                    'areas' => [['slug', 'name', 'postcode_prefix', 'is_featured', 'coordinates']],
                ]],
            ]);
    }

    public function test_page_endpoint_returns_a_published_page_and_404s_on_a_draft(): void
    {
        $this->getJson(route('api.v1.pages.show', ['slug' => 'home']))
            ->assertOk()
            ->assertJsonPath('data.slug', 'home')
            ->assertJsonStructure(['data' => ['slug', 'route_path', 'title', 'template', 'seo', 'sections']]);

        $this->getJson(route('api.v1.pages.show', ['slug' => 'this-page-does-not-exist']))
            ->assertNotFound();
    }

    public function test_inactive_service_items_are_hidden_from_the_catalogue(): void
    {
        $token = Str::uuid()->toString();
        $category = \App\Models\PriceCategory::query()->active()->firstOrFail();

        $item = ServiceItem::query()->create([
            'price_category_id' => $category->id,
            'sku' => "HIDDEN-{$token}",
            'slug' => "hidden-item-{$token}",
            'name' => "Hidden Item {$token}",
            'price_pence' => 1000,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'confirmed',
            'is_active' => false,
            'sort_order' => 999,
        ]);

        $this->getJson(route('api.v1.price-categories.items', ['slug' => $category->slug]))
            ->assertOk()
            ->assertDontSee("Hidden Item {$token}");

        $item->forceDelete();
    }

    public function test_price_categories_can_embed_their_items_in_one_request(): void
    {
        $this->getJson(route('api.v1.price-categories.index', ['with_items' => 1]))
            ->assertOk()
            ->assertJsonStructure(['data' => [['slug', 'name', 'items' => [['id', 'slug', 'name', 'price']]]]]);

        // Without the flag the items stay out of the payload.
        $this->getJson(route('api.v1.price-categories.index'))
            ->assertOk()
            ->assertJsonMissingPath('data.0.items');
    }

    public function test_frontend_bundled_images_are_returned_as_relative_paths(): void
    {
        $category = \App\Models\PriceCategory::query()->active()->whereNotNull('image_id')->with('image')->firstOrFail();
        $this->assertStringStartsWith('/', $category->image->path);

        $data = $this->getJson(route('api.v1.price-categories.index'))->assertOk()->json('data');
        $payload = collect($data)->firstWhere('slug', $category->slug);

        // Served by the Next.js public folder, so it must not be rewritten to the API origin.
        $this->assertSame($category->image->path, $payload['image']['url']);
    }

    public function test_visitor_reviews_are_stored_as_pending_and_not_published(): void
    {
        Cache::flush();
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.reviews.store'), [
            'reviewer_name' => "Visitor {$token}",
            'rating' => 5,
            'body' => 'The team was quick and tidy, would book again.',
            'website' => '',
            // Status is not accepted from the request.
            'status' => 'published',
        ])
            ->assertCreated()
            ->assertJson(['ok' => true]);

        $review = Review::query()->where('reviewer_name', "Visitor {$token}")->firstOrFail();

        $this->assertSame('pending', $review->status);
        $this->assertSame('website', $review->source);
        $this->assertNull($review->published_at);

        $this->getJson(route('api.v1.reviews.index', ['limit' => 50]))
            ->assertOk()
            ->assertDontSee("Visitor {$token}");

        $review->delete();
    }

    public function test_visitor_review_validation_and_honeypot(): void
    {
        Cache::flush();
        $token = Str::uuid()->toString();

        $this->postJson(route('api.v1.reviews.store'), [
            'reviewer_name' => "Short {$token}",
            'rating' => 9,
            'body' => 'short',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['rating', 'body']);

        $this->postJson(route('api.v1.reviews.store'), [
            'reviewer_name' => "Bot {$token}",
            'rating' => 5,
            'body' => 'Automated spam submission text here.',
            'website' => 'https://spam.example.com',
        ])
            ->assertOk()
            ->assertExactJson(['ok' => true]);

        $this->assertDatabaseMissing('reviews', ['reviewer_name' => "Bot {$token}"]);
        $this->assertDatabaseMissing('reviews', ['reviewer_name' => "Short {$token}"]);
    }

    public function test_pages_index_lists_published_pages_with_their_seo(): void
    {
        $token = Str::uuid()->toString();

        $draft = \App\Models\Page::query()->create([
            'slug' => "draft-page-{$token}",
            'route_path' => "/draft-page-{$token}",
            'title' => "Draft Page {$token}",
            'template' => 'default',
            'status' => 'draft',
            'is_indexable' => true,
            'sort_order' => 999,
        ]);

        $pages = $this->getJson(route('api.v1.pages.index'))
            ->assertOk()
            ->assertJsonStructure(['data' => [['slug', 'route_path', 'title', 'seo' => ['meta_title', 'meta_description', 'is_indexable']]]])
            ->assertJsonMissingPath('data.0.sections')
            ->json('data');

        $routes = collect($pages)->pluck('route_path');
        $this->assertTrue($routes->contains('/houseClearance'));
        $this->assertFalse($routes->contains("/draft-page-{$token}"));

        // Checkout and payment are published but must stay out of search results.
        $checkout = collect($pages)->firstWhere('route_path', '/checkout');
        $this->assertFalse($checkout['seo']['is_indexable']);
    }

    public function test_service_seo_edited_in_admin_reaches_the_pages_index(): void
    {
        $token = Str::uuid()->toString();
        $admin = \App\Models\User::query()->create([
            'name' => 'SEO Admin',
            'email' => "seo-admin-{$token}@example.com",
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $service = Service::query()->where('slug', 'house-clearance')->with('page')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.services.update', $service), [
            'name' => $service->name,
            'short_name' => $service->short_name,
            'slug' => $service->slug,
            'route_path' => $service->route_path,
            'service_category_id' => $service->service_category_id,
            'page_id' => $service->page_id,
            'hero_media_id' => $service->hero_media_id,
            'headline' => $service->headline,
            'summary' => $service->summary,
            'description' => $service->description,
            'status' => 'published',
            'is_featured' => $service->is_featured ? '1' : '0',
            'is_bookable' => $service->is_bookable ? '1' : '0',
            'sort_order' => (string) $service->sort_order,
            'meta_title' => "House clearance in Portsmouth {$token}",
            'meta_description' => 'Fast, tidy house clearance.',
        ])->assertRedirect(route('admin.services.index'));

        $page = collect($this->getJson(route('api.v1.pages.index'))->json('data'))->firstWhere('route_path', '/houseClearance');

        $this->assertSame("House clearance in Portsmouth {$token}", $page['seo']['meta_title']);
        $this->assertSame('Fast, tidy house clearance.', $page['seo']['meta_description']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceBlockItem;
use App\Models\ServiceCategory;
use App\Models\ServiceContentBlock;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ServiceCatalogTest extends TestCase
{
    private function admin(string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "catalog-admin-{$token}@example.com"],
            ['name' => 'Catalog Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function servicePayload(string $token, array $overrides = []): array
    {
        return array_merge([
            'name' => "Test Service {$token}",
            'short_name' => 'Test Service',
            'slug' => "test-service-{$token}",
            'route_path' => "/test-service-{$token}",
            'service_category_id' => null,
            'page_id' => null,
            'hero_media_id' => null,
            'headline' => 'Fast and tidy collections',
            'summary' => 'A short summary.',
            'description' => 'A longer description.',
            'status' => 'draft',
            'is_featured' => '0',
            'is_bookable' => '1',
            'sort_order' => '10',
        ], $overrides);
    }

    public function test_admin_can_list_service_categories(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $this->actingAs($admin)
            ->get(route('admin.service-categories.index'))
            ->assertOk()
            ->assertSee('Manage service categories');

        $this->actingAs($admin)
            ->getJson(route('admin.service-categories.data'))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee('status-pill', false);
    }

    public function test_admin_can_create_and_update_a_service_category(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $this->actingAs($admin)
            ->post(route('admin.service-categories.store'), [
                'name' => "Test Category {$token}",
                'slug' => '',
                'parent_id' => '',
                'description' => 'Created by a test.',
                'icon' => 'trash3',
                'is_active' => '1',
                'sort_order' => '5',
            ])
            ->assertRedirect(route('admin.service-categories.index'))
            ->assertSessionHas('success', 'Service category created successfully.');

        $category = ServiceCategory::query()->where('name', "Test Category {$token}")->firstOrFail();

        $this->assertSame(Str::slug("Test Category {$token}"), $category->slug);
        $this->assertTrue($category->is_active);

        $this->actingAs($admin)
            ->put(route('admin.service-categories.update', $category), [
                'name' => "Renamed Category {$token}",
                'slug' => $category->slug,
                'parent_id' => '',
                'description' => '',
                'icon' => '',
                'is_active' => '0',
                'sort_order' => '7',
            ])
            ->assertRedirect(route('admin.service-categories.index'));

        $category->refresh();

        $this->assertSame("Renamed Category {$token}", $category->name);
        $this->assertFalse($category->is_active);

        $category->delete();
    }

    public function test_category_cannot_be_its_own_parent(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $category = ServiceCategory::query()->create([
            'name' => "Self Parent {$token}",
            'slug' => "self-parent-{$token}",
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.service-categories.edit', $category))
            ->put(route('admin.service-categories.update', $category), [
                'name' => $category->name,
                'slug' => $category->slug,
                'parent_id' => $category->id,
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('parent_id');

        $category->delete();
    }

    public function test_category_with_services_cannot_be_deleted(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $category = ServiceCategory::query()->create([
            'name' => "Busy Category {$token}",
            'slug' => "busy-category-{$token}",
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $service = Service::query()->create($this->servicePayload($token, [
            'service_category_id' => $category->id,
            'is_featured' => false,
            'is_bookable' => true,
            'sort_order' => 1,
        ]));

        $this->actingAs($admin)
            ->from(route('admin.service-categories.index'))
            ->delete(route('admin.service-categories.destroy', $category))
            ->assertRedirect(route('admin.service-categories.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('service_categories', ['id' => $category->id]);

        $service->forceDelete();
        $category->delete();
    }

    public function test_admin_can_create_a_service_with_related_services_and_page_seo(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $page = Page::query()->create([
            'slug' => "test-page-{$token}",
            'route_path' => "/test-page-{$token}",
            'title' => "Test Page {$token}",
            'template' => 'service',
            'status' => 'draft',
            'is_indexable' => true,
            'sort_order' => 99,
        ]);

        $related = Service::query()->published()->first();
        $this->assertNotNull($related, 'Seeded services are required; run db:seed first.');

        $this->actingAs($admin)
            ->post(route('admin.services.store'), $this->servicePayload($token, [
                'page_id' => $page->id,
                'status' => 'published',
                'related_service_ids' => [$related->id],
                'meta_title' => 'Test meta title',
                'meta_description' => 'Test meta description',
            ]))
            ->assertSessionHas('success', 'Service created successfully.');

        $service = Service::query()->where('slug', "test-service-{$token}")->firstOrFail();

        $this->assertSame('published', $service->status);
        $this->assertNotNull($service->published_at);
        $this->assertSame([$related->id], $service->relatedServices()->pluck('services.id')->all());

        $page->refresh();
        $this->assertSame('Test meta title', $page->meta_title);
        $this->assertSame('Test meta description', $page->meta_description);

        // Moving back to draft clears the publish stamp.
        $this->actingAs($admin)
            ->put(route('admin.services.update', $service), $this->servicePayload($token, [
                'page_id' => $page->id,
                'status' => 'draft',
                'related_service_ids' => [],
                'meta_title' => 'Updated meta title',
                'meta_description' => '',
            ]))
            ->assertRedirect(route('admin.services.index'));

        $service->refresh();

        $this->assertSame('draft', $service->status);
        $this->assertNull($service->published_at);
        $this->assertSame([], $service->relatedServices()->pluck('services.id')->all());
        $this->assertSame('Updated meta title', $page->refresh()->meta_title);

        $service->forceDelete();
        $page->forceDelete();
    }

    public function test_service_cannot_be_related_to_itself(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $service = Service::query()->create($this->servicePayload($token, [
            'is_featured' => false,
            'is_bookable' => true,
            'sort_order' => 1,
        ]));

        $this->actingAs($admin)
            ->from(route('admin.services.edit', $service))
            ->put(route('admin.services.update', $service), $this->servicePayload($token, [
                'related_service_ids' => [$service->id],
            ]))
            ->assertSessionHasErrors('related_service_ids.0');

        $service->forceDelete();
    }

    public function test_admin_can_view_the_services_list_and_data(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $this->actingAs($admin)
            ->get(route('admin.services.index'))
            ->assertOk()
            ->assertSee('Manage services');

        $this->actingAs($admin)
            ->getJson(route('admin.services.data'))
            ->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertSee('status-pill', false);
    }

    public function test_admin_can_manage_content_blocks_and_items(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $service = Service::query()->create($this->servicePayload($token, [
            'is_featured' => false,
            'is_bookable' => true,
            'sort_order' => 1,
        ]));

        $this->actingAs($admin)
            ->get(route('admin.services.blocks.index', $service))
            ->assertOk()
            ->assertSee('No content blocks yet');

        $this->actingAs($admin)
            ->post(route('admin.services.blocks.store', $service), [
                'block_key' => '',
                'heading' => 'What we take',
                'component' => 'feature_list',
                'eyebrow' => 'Overview',
                'subheading' => '',
                'body' => 'Everything a standard clearance covers.',
                'media_id' => '',
                'is_enabled' => '1',
                'sort_order' => '1',
            ])
            ->assertSessionHas('success', 'Content block created successfully.');

        $block = ServiceContentBlock::query()->where('service_id', $service->id)->firstOrFail();

        $this->assertSame('what_we_take', $block->block_key);
        $this->assertSame('feature_list', $block->component);

        $this->actingAs($admin)
            ->post(route('admin.services.blocks.items.store', [$service, $block]), [
                'title' => 'Sofas and armchairs',
                'subtitle' => '',
                'body' => '',
                'icon' => 'check2-circle',
                'url' => '',
                'media_id' => '',
                'is_enabled' => '1',
                'sort_order' => '1',
            ])
            ->assertRedirect(route('admin.services.blocks.edit', [$service, $block]))
            ->assertSessionHas('success', 'Block item created successfully.');

        $item = ServiceBlockItem::query()->where('service_content_block_id', $block->id)->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.services.blocks.edit', [$service, $block]))
            ->assertOk()
            ->assertSee('Sofas and armchairs');

        // Deleting the block cascades to its items.
        $this->actingAs($admin)
            ->delete(route('admin.services.blocks.destroy', [$service, $block]))
            ->assertRedirect(route('admin.services.blocks.index', $service));

        $this->assertDatabaseMissing('service_content_blocks', ['id' => $block->id]);
        $this->assertDatabaseMissing('service_block_items', ['id' => $item->id]);

        $service->forceDelete();
    }

    public function test_block_of_another_service_returns_not_found(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $serviceA = Service::query()->create($this->servicePayload($token.'-a', ['sort_order' => 1]));
        $serviceB = Service::query()->create($this->servicePayload($token.'-b', ['sort_order' => 2]));

        $block = $serviceA->contentBlocks()->create([
            'block_key' => 'overview',
            'component' => 'content_block',
            'heading' => 'Overview',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.services.blocks.edit', [$serviceB, $block]))
            ->assertNotFound();

        $serviceA->forceDelete();
        $serviceB->forceDelete();
    }

    public function test_media_picker_options_endpoint_returns_select2_payload(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $media = MediaAsset::query()->firstOrFail();

        $this->actingAs($admin)
            ->getJson(route('admin.media.options'))
            ->assertOk()
            ->assertJsonStructure(['results' => [['id', 'text', 'url', 'is_image']], 'pagination' => ['more']]);

        $this->actingAs($admin)
            ->getJson(route('admin.media.options', ['q' => $media->original_name]))
            ->assertOk()
            ->assertJsonFragment(['id' => $media->id]);
    }

    public function test_all_services_cms_form_screens_render(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $category = ServiceCategory::query()->create([
            'name' => "Form Screen Category {$token}",
            'slug' => "form-screen-category-{$token}",
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $service = Service::query()->create($this->servicePayload($token, [
            'service_category_id' => $category->id,
            'hero_media_id' => MediaAsset::query()->value('id'),
            'sort_order' => 1,
        ]));

        $block = $service->contentBlocks()->create([
            'block_key' => 'overview',
            'component' => 'content_block',
            'heading' => 'Overview',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $item = $block->items()->create([
            'title' => 'First item',
            'is_enabled' => true,
            'sort_order' => 1,
        ]);

        $screens = [
            route('admin.service-categories.create') => 'Category details',
            route('admin.service-categories.edit', $category) => 'Category details',
            route('admin.services.create') => 'Service details',
            route('admin.services.edit', $service) => 'Service details',
            route('admin.services.blocks.create', $service) => 'Block details',
            route('admin.services.blocks.edit', [$service, $block]) => 'Block details',
            route('admin.services.blocks.items.create', [$service, $block]) => 'Item details',
            route('admin.services.blocks.items.edit', [$service, $block, $item]) => 'Item details',
        ];

        foreach ($screens as $url => $expected) {
            $this->actingAs($admin)
                ->get($url)
                ->assertOk()
                ->assertSee($expected);
        }

        // The hero picker and related services controls come from the shared partials.
        $this->actingAs($admin)
            ->get(route('admin.services.edit', $service))
            ->assertSee('media-picker', false)
            ->assertSee('related_service_ids[]', false)
            ->assertSee('Search metadata');

        $service->forceDelete();
        $category->delete();
    }
}

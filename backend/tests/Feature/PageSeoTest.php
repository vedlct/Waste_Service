<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class PageSeoTest extends TestCase
{
    private function editor(): User
    {
        return User::query()->create([
            'name' => 'SEO Editor',
            'email' => 'seo-editor-'.Str::uuid()->toString().'@example.com',
            'password' => 'password',
            'role' => 'editor',
            'status' => 'active',
        ]);
    }

    public function test_editors_can_list_pages_and_see_placeholder_warnings(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)
            ->get(route('admin.pages.index'))
            ->assertOk()
            ->assertSee('Manage page titles and search metadata')
            ->assertSee('placeholder meta description');

        $this->actingAs($editor)
            ->getJson(route('admin.pages.data', ['search' => ['value' => 'FAQ']]))
            ->assertOk()
            ->assertSee('Needs a real description')
            ->assertSee('/faq');
    }

    public function test_editing_a_page_updates_its_seo_and_the_public_api(): void
    {
        $editor = $this->editor();
        $page = Page::query()->where('slug', 'faq')->firstOrFail();

        $this->actingAs($editor)
            ->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('Search result')
            ->assertSee('/faq');

        $this->actingAs($editor)
            ->put(route('admin.pages.update', $page), [
                'title' => 'Frequently Asked Questions',
                'navigation_label' => 'FAQ',
                'meta_title' => 'Rubbish removal FAQs | MR. TEE Removals',
                'meta_description' => 'Answers about collection times, pricing, and what we take.',
                'is_indexable' => '1',
                'is_followable' => '1',
            ])
            ->assertRedirect(route('admin.pages.index'))
            ->assertSessionHas('success', 'Page updated successfully.');

        $page->refresh();
        $this->assertSame('Rubbish removal FAQs | MR. TEE Removals', $page->meta_title);
        $this->assertSame($editor->id, $page->updated_by);

        $public = collect($this->getJson(route('api.v1.pages.index'))->json('data'))->firstWhere('slug', 'faq');
        $this->assertSame('Answers about collection times, pricing, and what we take.', $public['seo']['meta_description']);

        $this->assertTrue(ActivityLog::query()
            ->where('subject_type', $page->getMorphClass())
            ->where('subject_id', $page->id)
            ->where('action', 'updated')
            ->exists());
    }

    public function test_hiding_a_page_from_search_marks_it_not_indexable(): void
    {
        $editor = $this->editor();
        $page = Page::query()->where('slug', 'areas')->firstOrFail();

        $this->actingAs($editor)
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'is_indexable' => '0',
                'is_followable' => '1',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $public = collect($this->getJson(route('api.v1.pages.index'))->json('data'))->firstWhere('slug', 'areas');
        $this->assertFalse($public['seo']['is_indexable']);
    }

    public function test_share_card_canonical_and_nofollow_reach_the_public_api(): void
    {
        $editor = $this->editor();
        $page = Page::query()->where('slug', 'house-clearance')->firstOrFail();
        $image = MediaAsset::query()->create([
            'uploaded_by' => $editor->id,
            'disk' => 'public',
            'path' => 'media/library/share-'.Str::uuid()->toString().'.jpg',
            'original_name' => 'share.jpg',
            'mime_type' => 'image/jpeg',
            'alt_text' => 'A cleared living room',
        ]);

        $this->actingAs($editor)
            ->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('Social sharing')
            ->assertSee('Canonical URL')
            ->assertSee('Let search engines follow links');

        $this->actingAs($editor)
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'og_title' => 'House clearance in Portsmouth',
                'og_description' => 'Booked online, cleared in a day.',
                'og_image_id' => $image->id,
                'canonical_url' => '/houseClearance',
                'is_indexable' => '1',
                'is_followable' => '0',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $seo = collect($this->getJson(route('api.v1.pages.index'))->json('data'))->firstWhere('slug', 'house-clearance')['seo'];

        $this->assertSame('House clearance in Portsmouth', $seo['og_title']);
        $this->assertSame('Booked online, cleared in a day.', $seo['og_description']);
        $this->assertSame($image->id, $seo['og_image']['id']);
        $this->assertSame('A cleared living room', $seo['og_image']['alt']);
        $this->assertSame('/houseClearance', $seo['canonical_url']);
        $this->assertTrue($seo['is_indexable']);
        $this->assertFalse($seo['is_followable']);

        $this->assertSame(
            'house-clearance',
            $this->getJson(route('api.v1.pages.show', 'house-clearance'))->json('data.slug'),
        );

        // The image is now in use, so the Media Library refuses to delete it.
        $this->assertContains('Page share images', array_column($image->fresh()->usageBreakdown(), 'label'));
    }

    public function test_canonical_url_and_share_image_are_validated(): void
    {
        $editor = $this->editor();
        $page = Page::query()->where('slug', 'faq')->firstOrFail();
        $pdf = MediaAsset::query()->create([
            'uploaded_by' => $editor->id,
            'disk' => 'public',
            'path' => 'media/library/price-list-'.Str::uuid()->toString().'.pdf',
            'original_name' => 'price-list.pdf',
            'mime_type' => 'application/pdf',
        ]);

        foreach (['javascript:alert(1)', 'faq', '//evil.example/faq', 'https://a b.com'] as $bad) {
            $this->actingAs($editor)
                ->from(route('admin.pages.edit', $page))
                ->put(route('admin.pages.update', $page), [
                    'title' => $page->title,
                    'canonical_url' => $bad,
                    'is_indexable' => '1',
                    'is_followable' => '1',
                ])
                ->assertSessionHasErrors('canonical_url');
        }

        $this->actingAs($editor)
            ->from(route('admin.pages.edit', $page))
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'og_image_id' => $pdf->id,
                'is_indexable' => '1',
                'is_followable' => '1',
            ])
            ->assertSessionHasErrors('og_image_id');

        foreach (['/', '/faq', 'https://www.example.co.uk/faq'] as $good) {
            $this->actingAs($editor)
                ->put(route('admin.pages.update', $page), [
                    'title' => $page->title,
                    'canonical_url' => $good,
                    'is_indexable' => '1',
                    'is_followable' => '1',
                ])
                ->assertSessionHasNoErrors();
        }
    }

    public function test_checkout_pages_are_seeded_noindex_nofollow(): void
    {
        foreach (['checkout', 'payment'] as $slug) {
            $page = Page::query()->where('slug', $slug)->firstOrFail();
            $this->assertFalse($page->is_indexable);
            $this->assertFalse($page->is_followable);
        }

        $this->assertTrue(Page::query()->where('slug', 'home')->value('is_followable'));
    }

    public function test_site_wide_seo_and_business_settings_are_public(): void
    {
        $settings = $this->getJson(route('api.v1.settings.index'))->assertOk()->json('data');

        $this->assertArrayHasKey('google_site_verification', $settings['seo']);
        $this->assertArrayHasKey('default_share_image_url', $settings['seo']);
        $this->assertSame('Portsmouth', $settings['business']['locality']);
        $this->assertSame('Hampshire', $settings['business']['region']);
        $this->assertSame('Mo-Sa 07:00-19:00', $settings['business']['opening_hours_spec']);
    }

    public function test_route_path_and_status_cannot_be_changed_from_the_admin(): void
    {
        $editor = $this->editor();
        $page = Page::query()->where('slug', 'faq')->firstOrFail();

        $this->actingAs($editor)
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'is_indexable' => '1',
                'is_followable' => '1',
                'route_path' => '/hijacked',
                'status' => 'draft',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $page->refresh();
        $this->assertSame('/faq', $page->route_path);
        $this->assertSame('published', $page->status);
    }
}

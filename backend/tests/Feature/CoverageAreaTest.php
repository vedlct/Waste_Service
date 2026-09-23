<?php

namespace Tests\Feature;

use App\Models\CoverageArea;
use App\Models\CoverageRegion;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class CoverageAreaTest extends TestCase
{
    private function admin(string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "coverage-admin-{$token}@example.com"],
            ['name' => 'Coverage Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );
    }

    private function region(string $token): CoverageRegion
    {
        return CoverageRegion::query()->create([
            'name' => "Test Region {$token}",
            'slug' => "test-region-{$token}",
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }

    public function test_admin_can_view_coverage_lists_and_data(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $this->actingAs($admin)->get(route('admin.coverage-regions.index'))->assertOk()->assertSee('Manage coverage regions');
        $this->actingAs($admin)->get(route('admin.coverage-areas.index'))->assertOk()->assertSee('Manage coverage areas');

        foreach ([route('admin.coverage-regions.data'), route('admin.coverage-areas.data')] as $url) {
            $this->actingAs($admin)
                ->getJson($url)
                ->assertOk()
                ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
                ->assertSee('status-pill', false);
        }
    }

    public function test_admin_can_create_a_region_and_an_area(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $this->actingAs($admin)
            ->post(route('admin.coverage-regions.store'), [
                'name' => "New Region {$token}",
                'slug' => '',
                'description' => 'Created by a test.',
                'is_active' => '1',
                'sort_order' => '7',
            ])
            ->assertRedirect(route('admin.coverage-regions.index'))
            ->assertSessionHas('success', 'Coverage region created successfully.');

        $region = CoverageRegion::query()->where('name', "New Region {$token}")->firstOrFail();
        $this->assertSame(Str::slug("New Region {$token}"), $region->slug);

        $this->actingAs($admin)
            ->post(route('admin.coverage-areas.store'), [
                'coverage_region_id' => $region->id,
                'name' => "New Area {$token}",
                'slug' => '',
                'postcode_prefix' => 'po1',
                'latitude' => '50.8058000',
                'longitude' => '-1.0872000',
                'is_featured' => '1',
                'is_active' => '1',
                'sort_order' => '2',
            ])
            ->assertRedirect(route('admin.coverage-areas.index'))
            ->assertSessionHas('success', 'Coverage area created successfully.');

        $area = CoverageArea::query()->where('coverage_region_id', $region->id)->firstOrFail();

        // The prefix is normalised to uppercase with spaces stripped.
        $this->assertSame('PO1', $area->postcode_prefix);
        $this->assertTrue($area->is_featured);
        $this->assertTrue($area->has_coordinates);
        $this->assertStringContainsString('50.8058000,-1.0872000', $area->map_url);

        $area->delete();
        $region->delete();
    }

    public function test_postcode_prefix_must_look_like_a_uk_outward_code(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $region = $this->region($token);

        $this->actingAs($admin)
            ->from(route('admin.coverage-areas.create'))
            ->post(route('admin.coverage-areas.store'), [
                'coverage_region_id' => $region->id,
                'name' => "Bad Postcode {$token}",
                'slug' => '',
                'postcode_prefix' => 'NOT-A-POSTCODE',
                'is_featured' => '0',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('postcode_prefix');

        $region->delete();
    }

    public function test_coordinates_must_be_set_as_a_pair(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $region = $this->region($token);

        $this->actingAs($admin)
            ->from(route('admin.coverage-areas.create'))
            ->post(route('admin.coverage-areas.store'), [
                'coverage_region_id' => $region->id,
                'name' => "Half Coordinates {$token}",
                'slug' => '',
                'latitude' => '51.5',
                'longitude' => '',
                'is_featured' => '0',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('latitude');

        $region->delete();
    }

    public function test_region_with_areas_cannot_be_deleted(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $region = $this->region($token);

        $area = CoverageArea::query()->create([
            'coverage_region_id' => $region->id,
            'name' => "Blocking Area {$token}",
            'slug' => "blocking-area-{$token}",
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.coverage-regions.index'))
            ->delete(route('admin.coverage-regions.destroy', $region))
            ->assertRedirect(route('admin.coverage-regions.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('coverage_regions', ['id' => $region->id]);

        $area->delete();

        $this->actingAs($admin)
            ->delete(route('admin.coverage-regions.destroy', $region))
            ->assertRedirect(route('admin.coverage-regions.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('coverage_regions', ['id' => $region->id]);
    }

    public function test_area_list_can_be_filtered_by_region_and_flags(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $region = $this->region($token);

        $featured = CoverageArea::query()->create([
            'coverage_region_id' => $region->id,
            'name' => "Featured Area {$token}",
            'slug' => "featured-area-{$token}",
            'postcode_prefix' => 'E14',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $plain = CoverageArea::query()->create([
            'coverage_region_id' => $region->id,
            'name' => "Plain Area {$token}",
            'slug' => "plain-area-{$token}",
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.coverage-areas.data', [
                'coverage_region_id' => $region->id,
                'featured' => '1',
                'search' => ['value' => $token],
            ]))
            ->assertOk()
            ->assertSee("Featured Area {$token}")
            ->assertDontSee("Plain Area {$token}");

        $this->actingAs($admin)
            ->getJson(route('admin.coverage-areas.data', [
                'coverage_region_id' => $region->id,
                'missing_postcode' => '1',
                'search' => ['value' => $token],
            ]))
            ->assertOk()
            ->assertSee("Plain Area {$token}")
            ->assertDontSee("Featured Area {$token}");

        $featured->delete();
        $plain->delete();
        $region->delete();
    }

    public function test_postcode_matching_scope_finds_the_right_area(): void
    {
        $token = Str::uuid()->toString();
        $region = $this->region($token);

        $area = CoverageArea::query()->create([
            'coverage_region_id' => $region->id,
            'name' => "Matching Area {$token}",
            'slug' => "matching-area-{$token}",
            'postcode_prefix' => 'PO1',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $matched = CoverageArea::query()->matchingPostcode('po1 3ax')->pluck('id');
        $this->assertTrue($matched->contains($area->id));

        $missed = CoverageArea::query()->matchingPostcode('SW1A 1AA')->pluck('id');
        $this->assertFalse($missed->contains($area->id));

        $area->delete();
        $region->delete();
    }

    public function test_coverage_form_screens_render(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $region = $this->region($token);

        $area = CoverageArea::query()->create([
            'coverage_region_id' => $region->id,
            'name' => "Form Area {$token}",
            'slug' => "form-area-{$token}",
            'postcode_prefix' => 'SW1A',
            'latitude' => '51.5010000',
            'longitude' => '-0.1416000',
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $screens = [
            route('admin.coverage-regions.create') => 'Region details',
            route('admin.coverage-regions.edit', $region) => 'Region details',
            route('admin.coverage-areas.create') => 'Area details',
            route('admin.coverage-areas.edit', $area) => 'Area details',
        ];

        foreach ($screens as $url => $expected) {
            $this->actingAs($admin)->get($url)->assertOk()->assertSee($expected);
        }

        $this->actingAs($admin)
            ->get(route('admin.coverage-areas.edit', $area))
            ->assertSee('Check this location on the map')
            ->assertSee('value="SW1A"', false);

        $area->delete();
        $region->delete();
    }

    public function test_postcode_matching_uses_the_exact_district_not_a_prefix(): void
    {
        $token = Str::uuid()->toString();
        $region = $this->region($token);

        $po1 = CoverageArea::query()->create([
            'coverage_region_id' => $region->id, 'name' => "PO1 Area {$token}", 'slug' => "po1-area-{$token}",
            'postcode_prefix' => 'PO1', 'is_featured' => false, 'is_active' => true, 'sort_order' => 0,
        ]);

        // PO19 is Chichester: it must not be claimed by the PO1 area.
        $this->assertFalse(CoverageArea::query()->matchingPostcode('PO19 1AB')->whereKey($po1->id)->exists());
        $this->assertFalse(CoverageArea::query()->matchingPostcode('PO16 7AB')->whereKey($po1->id)->exists());
        $this->assertTrue(CoverageArea::query()->matchingPostcode('PO1 3AX')->whereKey($po1->id)->exists());
        $this->assertTrue(CoverageArea::query()->matchingPostcode('po13ax')->whereKey($po1->id)->exists());
        $this->assertTrue(CoverageArea::query()->matchingPostcode('PO1')->whereKey($po1->id)->exists());

        $this->assertSame('PO16', CoverageArea::outwardCode('po16 7ab'));
        $this->assertSame('SW1A', CoverageArea::outwardCode('SW1A 1AA'));
        $this->assertSame('M1', CoverageArea::outwardCode('M1 1AA'));
        $this->assertSame('E14', CoverageArea::outwardCode('E14'));
    }

    public function test_the_seeded_coverage_is_portsmouth_with_every_postcode_filled_in(): void
    {
        $this->assertFalse(CoverageRegion::query()->where('name', 'like', '%London%')->exists());
        $this->assertTrue(CoverageRegion::query()->where('slug', 'portsmouth')->exists());
        $this->assertSame(0, CoverageArea::query()->whereNull('postcode_prefix')->count());

        // A Southsea postcode is covered; a London one is not.
        $this->assertTrue(CoverageArea::query()->active()->matchingPostcode('PO5 2AB')->exists());
        $this->assertFalse(CoverageArea::query()->active()->matchingPostcode('SW1A 1AA')->exists());
    }
}

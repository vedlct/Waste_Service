<?php

namespace Tests\Feature;

use App\Models\ExtraCharge;
use App\Models\LoadPackage;
use App\Models\PriceCategory;
use App\Models\ServiceItem;
use App\Models\User;
use App\Support\Money;
use Illuminate\Support\Str;
use Tests\TestCase;

class PricingCatalogueTest extends TestCase
{
    private function admin(string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "pricing-admin-{$token}@example.com"],
            ['name' => 'Pricing Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );
    }

    private function category(string $token): PriceCategory
    {
        return PriceCategory::query()->create([
            'name' => "Test Price Category {$token}",
            'slug' => "test-price-category-{$token}",
            'is_active' => true,
            'sort_order' => 0,
        ]);
    }

    public function test_money_helper_round_trips_pounds_and_pence(): void
    {
        $this->assertSame(6250, Money::toPence('62.50'));
        $this->assertSame(3000, Money::toPence(30));
        $this->assertNull(Money::toPence(''));
        $this->assertSame('62.50', Money::toPounds(6250));
        $this->assertSame('£62.50', Money::format(6250));
        $this->assertSame('N/A', Money::format(null));
        $this->assertSame(2000, Money::percentToBasisPoints('20'));
        $this->assertSame('20', Money::basisPointsToPercent(2000));
        $this->assertSame('20%', Money::formatVatRate(2000));
        // 120.00 inc VAT at 20% is 100.00 ex VAT.
        $this->assertSame(10000, Money::exVatPence(12000, 2000));
    }

    public function test_admin_can_view_every_pricing_list_and_data_endpoint(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $screens = [
            route('admin.price-categories.index') => 'Manage price categories',
            route('admin.service-items.index') => 'Manage service items',
            route('admin.load-packages.index') => 'Manage load packages',
            route('admin.extra-charges.index') => 'Manage extra charges',
        ];

        foreach ($screens as $url => $expected) {
            $this->actingAs($admin)->get($url)->assertOk()->assertSee($expected);
        }

        foreach ([
            route('admin.price-categories.data'),
            route('admin.service-items.data'),
            route('admin.load-packages.data'),
            route('admin.extra-charges.data'),
        ] as $url) {
            $this->actingAs($admin)
                ->getJson($url)
                ->assertOk()
                ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
        }
    }

    public function test_placeholder_prices_are_flagged_on_the_lists(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        // The seeded catalogue carries placeholder prices from the old site.
        $this->assertGreaterThan(0, ServiceItem::query()->withPricingStatus('placeholder')->count());

        $this->actingAs($admin)
            ->get(route('admin.service-items.index'))
            ->assertOk()
            ->assertSee('still use placeholder prices');

        $this->actingAs($admin)
            ->getJson(route('admin.service-items.data', ['pricing_status' => 'placeholder']))
            ->assertOk()
            ->assertSee('Placeholder');
    }

    public function test_admin_can_create_a_service_item_with_pounds_stored_as_pence(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $category = $this->category($token);

        $this->actingAs($admin)
            ->post(route('admin.service-items.store'), [
                'price_category_id' => $category->id,
                'name' => "Test Item {$token}",
                'slug' => '',
                'sku' => '',
                'description' => 'Created by a test.',
                'image_id' => '',
                'price' => '75.50',
                'vat_rate_percent' => '20',
                'pricing_status' => 'confirmed',
                'is_active' => '1',
                'sort_order' => '3',
            ])
            ->assertRedirect(route('admin.service-items.index'))
            ->assertSessionHas('success', 'Service item created successfully.');

        $item = ServiceItem::query()->where('price_category_id', $category->id)->firstOrFail();

        $this->assertSame(7550, $item->price_pence);
        $this->assertSame(2000, $item->vat_rate_basis_points);
        $this->assertSame(6292, $item->price_ex_vat_pence);
        $this->assertSame(Str::slug("Test Item {$token}"), $item->slug);
        $this->assertFalse($item->needsPriceReview());

        $item->forceDelete();
        $category->delete();
    }

    public function test_quote_required_item_rejects_a_fixed_price(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $category = $this->category($token);

        $this->actingAs($admin)
            ->from(route('admin.service-items.create'))
            ->post(route('admin.service-items.store'), [
                'price_category_id' => $category->id,
                'name' => "Quoted Item {$token}",
                'slug' => '',
                'sku' => '',
                'price' => '50.00',
                'vat_rate_percent' => '20',
                'pricing_status' => 'quote_required',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('price');

        $category->delete();
    }

    public function test_price_category_with_items_cannot_be_deleted(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $category = $this->category($token);

        $item = ServiceItem::query()->create([
            'price_category_id' => $category->id,
            'sku' => "TEST-SKU-{$token}",
            'slug' => "test-item-{$token}",
            'name' => "Test Item {$token}",
            'price_pence' => 1000,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'confirmed',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.price-categories.index'))
            ->delete(route('admin.price-categories.destroy', $category))
            ->assertRedirect(route('admin.price-categories.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('price_categories', ['id' => $category->id]);

        $item->forceDelete();
        $category->delete();
    }

    public function test_service_item_delete_is_a_soft_delete(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $category = $this->category($token);

        $item = ServiceItem::query()->create([
            'price_category_id' => $category->id,
            'sku' => "SOFT-SKU-{$token}",
            'slug' => "soft-item-{$token}",
            'name' => "Soft Item {$token}",
            'price_pence' => 2500,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'confirmed',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.service-items.destroy', $item))
            ->assertRedirect(route('admin.service-items.index'));

        $this->assertSoftDeleted('service_items', ['id' => $item->id]);

        $item->forceDelete();
        $category->delete();
    }

    public function test_load_package_derives_the_ex_vat_price_when_left_blank(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        $this->actingAs($admin)
            ->post(route('admin.load-packages.store'), [
                'name' => "Test Load {$token}",
                'slug' => '',
                'price_inc_vat' => '120.00',
                'price_ex_vat' => '',
                'vat_rate_percent' => '20',
                'max_weight_kg' => '250',
                'volume_cubic_yards' => '4.5',
                'sack_equivalent' => '25',
                'loading_time_minutes' => '30',
                'pricing_status' => 'placeholder',
                'is_popular' => '0',
                'is_active' => '1',
                'sort_order' => '9',
            ])
            ->assertRedirect(route('admin.load-packages.index'))
            ->assertSessionHas('success', 'Load package created successfully.');

        $package = LoadPackage::query()->where('slug', Str::slug("Test Load {$token}"))->firstOrFail();

        $this->assertSame(12000, $package->price_inc_vat_pence);
        $this->assertSame(10000, $package->price_ex_vat_pence);
        $this->assertSame(2000, $package->vat_rate_basis_points);
        $this->assertTrue($package->needsPriceReview());

        $package->forceDelete();
    }

    public function test_extra_charge_amount_rules_follow_the_variable_flag(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);

        // A fixed charge needs an amount.
        $this->actingAs($admin)
            ->from(route('admin.extra-charges.create'))
            ->post(route('admin.extra-charges.store'), [
                'name' => "Fixed Charge {$token}",
                'slug' => '',
                'amount' => '',
                'is_variable' => '0',
                'charge_type' => 'surcharge',
                'pricing_status' => 'active',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('amount');

        // A variable charge must not carry one.
        $this->actingAs($admin)
            ->from(route('admin.extra-charges.create'))
            ->post(route('admin.extra-charges.store'), [
                'name' => "Variable Charge {$token}",
                'slug' => '',
                'amount' => '25.00',
                'is_variable' => '1',
                'charge_type' => 'variable',
                'pricing_status' => 'quote_required',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('amount');

        $this->actingAs($admin)
            ->post(route('admin.extra-charges.store'), [
                'name' => "Valid Charge {$token}",
                'slug' => '',
                'amount' => '45.00',
                'is_variable' => '0',
                'charge_type' => 'surcharge',
                'pricing_status' => 'active',
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertRedirect(route('admin.extra-charges.index'));

        $charge = ExtraCharge::query()->where('slug', Str::slug("Valid Charge {$token}"))->firstOrFail();

        $this->assertSame(4500, $charge->amount_pence);
        $this->assertFalse($charge->is_variable);

        $charge->delete();
    }

    public function test_all_pricing_form_screens_render(): void
    {
        $token = Str::uuid()->toString();
        $admin = $this->admin($token);
        $category = $this->category($token);

        $item = ServiceItem::query()->create([
            'price_category_id' => $category->id,
            'sku' => "FORM-SKU-{$token}",
            'slug' => "form-item-{$token}",
            'name' => "Form Item {$token}",
            'price_pence' => 9900,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'placeholder',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $package = LoadPackage::query()->create([
            'slug' => "form-load-{$token}",
            'name' => "Form Load {$token}",
            'price_inc_vat_pence' => 15000,
            'price_ex_vat_pence' => 12500,
            'vat_rate_basis_points' => 2000,
            'pricing_status' => 'confirmed',
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $charge = ExtraCharge::query()->create([
            'slug' => "form-charge-{$token}",
            'name' => "Form Charge {$token}",
            'amount_pence' => 2500,
            'is_variable' => false,
            'charge_type' => 'fixed',
            'pricing_status' => 'active',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $screens = [
            route('admin.price-categories.create') => 'Category details',
            route('admin.price-categories.edit', $category) => 'Category details',
            route('admin.service-items.create') => 'Item details',
            route('admin.service-items.edit', $item) => 'Item details',
            route('admin.load-packages.create') => 'Package details',
            route('admin.load-packages.edit', $package) => 'Package details',
            route('admin.extra-charges.create') => 'Charge details',
            route('admin.extra-charges.edit', $charge) => 'Charge details',
        ];

        foreach ($screens as $url => $expected) {
            $this->actingAs($admin)->get($url)->assertOk()->assertSee($expected);
        }

        // Prices are shown in pounds on the edit form even though they are stored in pence.
        $this->actingAs($admin)
            ->get(route('admin.service-items.edit', $item))
            ->assertSee('value="99.00"', false)
            ->assertSee('Confirm this price with the business');

        $item->forceDelete();
        $package->forceDelete();
        $charge->delete();
        $category->delete();
    }
}

<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    private function admin(string $token): User
    {
        return User::query()->updateOrCreate(
            ['email' => "settings-admin-{$token}@example.com"],
            ['name' => 'Settings Admin Test', 'password' => 'password', 'role' => 'admin', 'status' => 'active'],
        );
    }

    public function test_settings_index_redirects_to_the_first_group(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $this->actingAs($admin)
            ->get(route('admin.settings.index'))
            ->assertRedirect(route('admin.settings.index', ['group' => 'general']));
    }

    public function test_admin_can_view_a_settings_group(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $this->actingAs($admin)
            ->get(route('admin.settings.index', ['group' => 'contact']))
            ->assertOk()
            ->assertSee('Contact settings')
            ->assertSee('contact.phone')
            ->assertSee('Expose through the public API');
    }

    public function test_unknown_settings_group_returns_not_found(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $this->actingAs($admin)
            ->get(route('admin.settings.index', ['group' => 'does-not-exist']))
            ->assertNotFound();
    }

    public function test_admin_can_update_settings_and_money_is_stored_in_pence(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $surcharge = SiteSetting::query()->where('group', 'booking')->where('key', 'saturday_collection_surcharge_pence')->firstOrFail();
        $callout = SiteSetting::query()->where('group', 'booking')->where('key', 'pay_on_arrival_callout_fee_pence')->firstOrFail();
        $originalSurcharge = $surcharge->value;
        $originalCallout = $callout->value;
        $originalCalloutPublic = $callout->is_public;

        $this->assertSame('money', $surcharge->input_type);

        $this->actingAs($admin)
            ->put(route('admin.settings.update', ['group' => 'booking']), [
                'settings' => [
                    $surcharge->id => ['value' => '62.50', 'is_public' => '1'],
                    $callout->id => ['value' => '30', 'is_public' => '0'],
                ],
            ])
            ->assertRedirect(route('admin.settings.index', ['group' => 'booking']))
            ->assertSessionHas('success', 'Settings updated successfully.');

        $surcharge->refresh();
        $callout->refresh();

        $this->assertSame(6250, $surcharge->value);
        $this->assertSame('62.50', $surcharge->form_value);
        $this->assertSame(3000, $callout->value);
        $this->assertFalse($callout->is_public);
        $this->assertSame($admin->id, $surcharge->updated_by);

        $surcharge->update(['value' => $originalSurcharge]);
        $callout->update(['value' => $originalCallout, 'is_public' => $originalCalloutPublic]);
    }

    public function test_settings_update_validates_input_by_type(): void
    {
        $admin = $this->admin(Str::uuid()->toString());

        $email = SiteSetting::query()->where('group', 'contact')->where('key', 'email')->firstOrFail();
        $originalEmail = $email->value;

        $this->actingAs($admin)
            ->from(route('admin.settings.index', ['group' => 'contact']))
            ->put(route('admin.settings.update', ['group' => 'contact']), [
                'settings' => [
                    $email->id => ['value' => 'not-an-email', 'is_public' => '1'],
                ],
            ])
            ->assertRedirect(route('admin.settings.index', ['group' => 'contact']))
            ->assertSessionHasErrors("settings.{$email->id}.value");

        $this->assertSame($originalEmail, $email->refresh()->value);
    }
}

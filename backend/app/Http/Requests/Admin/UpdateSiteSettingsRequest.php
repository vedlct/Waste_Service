<?php

namespace App\Http\Requests\Admin;

use App\Models\SiteSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class UpdateSiteSettingsRequest extends FormRequest
{
    /**
     * @var Collection<int, SiteSetting>|null
     */
    private ?Collection $groupSettings = null;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'settings' => ['array'],
        ];

        foreach ($this->groupSettings() as $setting) {
            $rules["settings.{$setting->id}.value"] = $setting->inputRules();
            $rules["settings.{$setting->id}.is_public"] = ['nullable', 'boolean'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [];

        foreach ($this->groupSettings() as $setting) {
            $attributes["settings.{$setting->id}.value"] = strtolower($setting->display_label);
            $attributes["settings.{$setting->id}.is_public"] = strtolower($setting->display_label).' visibility';
        }

        return $attributes;
    }

    /**
     * Settings that belong to the group being edited. Input for any other setting is ignored.
     *
     * @return Collection<int, SiteSetting>
     */
    public function groupSettings(): Collection
    {
        return $this->groupSettings ??= SiteSetting::query()
            ->group((string) $this->route('group'))
            ->ordered()
            ->get();
    }
}

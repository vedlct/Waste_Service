<?php

namespace App\Http\Requests\Admin;

use App\Models\CoverageArea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCoverageAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $prefix = strtoupper(preg_replace('/\s+/', '', (string) $this->input('postcode_prefix')) ?? '');

        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name', '')),
            'postcode_prefix' => $prefix === '' ? null : $prefix,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->coverageAreaRules();
    }

    /**
     * @return array<string, mixed>
     */
    protected function coverageAreaRules(?CoverageArea $area = null): array
    {
        return [
            'coverage_region_id' => ['required', 'integer', Rule::exists('coverage_regions', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('coverage_areas', 'slug')->ignore($area?->id)],
            // UK outward code: one or two letters, a digit, then an optional letter or digit.
            'postcode_prefix' => ['nullable', 'string', 'max:8', 'regex:/^[A-Z]{1,2}[0-9][A-Z0-9]?$/'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_featured' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'postcode_prefix.regex' => 'Use the outward part of a UK postcode, such as PO1, SW1A, or E14.',
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $latitude = $this->input('latitude');
                $longitude = $this->input('longitude');
                $hasLatitude = $latitude !== null && $latitude !== '';
                $hasLongitude = $longitude !== null && $longitude !== '';

                if ($hasLatitude xor $hasLongitude) {
                    $validator->errors()->add('latitude', 'Set both latitude and longitude, or leave both blank.');
                }
            },
        ];
    }
}

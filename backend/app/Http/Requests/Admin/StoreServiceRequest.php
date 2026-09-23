<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name', '')),
            'route_path' => $this->normalisedRoutePath(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->serviceRules();
    }

    /**
     * Shared between the store and update requests.
     *
     * @return array<string, mixed>
     */
    protected function serviceRules(?Service $service = null): array
    {
        $relatedRules = ['integer', Rule::exists('services', 'id')->whereNull('deleted_at')];

        if ($service?->id) {
            $relatedRules[] = Rule::notIn([$service->id]);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('services', 'slug')->ignore($service?->id)],
            'route_path' => ['required', 'string', 'max:255', 'regex:/^\/[A-Za-z0-9\-_\/]*$/', Rule::unique('services', 'route_path')->ignore($service?->id)],
            'service_category_id' => ['nullable', 'integer', Rule::exists('service_categories', 'id')],
            'page_id' => ['nullable', 'integer', Rule::exists('pages', 'id')->whereNull('deleted_at')],
            'hero_media_id' => ['nullable', 'integer', Rule::exists('media_assets', 'id')],
            'headline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:20000'],
            'status' => ['required', Rule::in(Service::STATUSES)],
            'is_featured' => ['required', 'boolean'],
            'is_bookable' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'related_service_ids' => ['nullable', 'array'],
            'related_service_ids.*' => $relatedRules,
        ];
    }

    protected function normalisedRoutePath(): string
    {
        $path = trim((string) $this->input('route_path'));

        if ($path === '') {
            return '/'.Str::slug($this->input('slug') ?: $this->input('name', ''));
        }

        return '/'.ltrim($path, '/');
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'route_path.regex' => 'The route path must start with a slash and may only contain letters, numbers, dashes, underscores, and slashes.',
            'related_service_ids.*.not_in' => 'A service cannot be related to itself.',
        ];
    }
}

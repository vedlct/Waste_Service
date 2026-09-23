<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use App\Models\ServiceContentBlock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreServiceContentBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'block_key' => Str::slug($this->input('block_key') ?: $this->input('heading', ''), '_'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->blockRules();
    }

    /**
     * @return array<string, mixed>
     */
    protected function blockRules(?ServiceContentBlock $block = null): array
    {
        return [
            'block_key' => [
                'required',
                'string',
                'max:120',
                'alpha_dash',
                Rule::unique('service_content_blocks', 'block_key')
                    ->where('service_id', $this->serviceId())
                    ->ignore($block?->id),
            ],
            'component' => ['required', 'string', 'max:120', Rule::in(array_keys(config('service_cms.block_components', [])))],
            'media_id' => ['nullable', 'integer', Rule::exists('media_assets', 'id')],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'subheading' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:20000'],
            'is_enabled' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function serviceId(): ?int
    {
        $service = $this->route('service');

        return $service instanceof Service ? $service->id : null;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'block_key.unique' => 'This service already has a block with that key.',
        ];
    }
}

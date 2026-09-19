<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaAssetRequest extends FormRequest
{
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
            'alt_text' => ['nullable', 'string', 'max:255'],
        ];

        foreach (config('media.metadata_fields', []) as $key => $field) {
            $rules["metadata.{$key}"] = ['nullable', 'string', 'max:'.($field['max'] ?? 255)];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = ['alt_text' => 'alt text'];

        foreach (config('media.metadata_fields', []) as $key => $field) {
            $attributes["metadata.{$key}"] = strtolower($field['label'] ?? $key);
        }

        return $attributes;
    }

    /**
     * Blank metadata inputs are dropped so the stored JSON only keeps meaningful values.
     *
     * @return array<string, mixed>
     */
    public function mediaAttributes(): array
    {
        $validated = $this->validated();

        $metadata = collect($validated['metadata'] ?? [])
            ->map(fn (?string $value): ?string => is_string($value) ? trim($value) : $value)
            ->reject(fn (?string $value): bool => $value === null || $value === '')
            ->all();

        return [
            'alt_text' => filled($validated['alt_text'] ?? null) ? trim($validated['alt_text']) : null,
            'metadata' => $metadata === [] ? null : $metadata,
        ];
    }
}

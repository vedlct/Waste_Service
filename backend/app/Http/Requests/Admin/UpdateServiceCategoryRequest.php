<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name', '')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $category = $this->category();

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('service_categories', 'slug')->ignore($category?->id)],
            'parent_id' => ['nullable', 'integer', Rule::exists('service_categories', 'id')],
            'description' => ['nullable', 'string', 'max:2000'],
            'icon' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $category = $this->category();
                $parentId = $this->input('parent_id');

                if (! $category || ! $parentId) {
                    return;
                }

                if ((int) $parentId === (int) $category->id) {
                    $validator->errors()->add('parent_id', 'A category cannot be its own parent.');

                    return;
                }

                if ($category->descendantIds()->contains((int) $parentId)) {
                    $validator->errors()->add('parent_id', 'A category cannot be moved under one of its own sub categories.');
                }
            },
        ];
    }

    private function category(): ?ServiceCategory
    {
        $category = $this->route('service_category');

        return $category instanceof ServiceCategory ? $category : null;
    }
}

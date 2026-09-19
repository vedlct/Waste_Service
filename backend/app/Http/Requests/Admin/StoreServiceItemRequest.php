<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceItem;
use App\Support\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreServiceItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = $this->input('name', '');

        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $name),
            'sku' => Str::upper(Str::slug($this->input('sku') ?: $name, '-')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->serviceItemRules();
    }

    /**
     * @return array<string, mixed>
     */
    protected function serviceItemRules(?ServiceItem $item = null): array
    {
        return [
            'price_category_id' => ['required', 'integer', Rule::exists('price_categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('service_items', 'slug')->ignore($item?->id)],
            'sku' => ['required', 'string', 'max:255', Rule::unique('service_items', 'sku')->ignore($item?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'image_id' => ['nullable', 'integer', Rule::exists('media_assets', 'id')],
            'price' => ['required', 'numeric', 'min:0', 'max:100000'],
            'vat_rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'pricing_status' => ['required', Rule::in(ServiceItem::pricingStatuses())],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                // `price_pence` is an unsigned column, so a quoted item still needs a stored value of 0.
                if ($this->input('pricing_status') === 'quote_required' && (float) $this->input('price') > 0) {
                    $validator->errors()->add('price', 'A quote required item should not carry a fixed price. Set the price to 0.');
                }
            },
        ];
    }

    /**
     * Maps the pounds and percent inputs onto the stored pence and basis point columns.
     *
     * @return array<string, mixed>
     */
    public function serviceItemAttributes(): array
    {
        $validated = $this->validated();

        return Arr::except($validated, ['price', 'vat_rate_percent']) + [
            'price_pence' => Money::toPence($validated['price']) ?? 0,
            'vat_rate_basis_points' => Money::percentToBasisPoints($validated['vat_rate_percent'])
                ?? config('pricing.default_vat_basis_points', 2000),
        ];
    }
}

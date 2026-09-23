<?php

namespace App\Http\Requests\Admin;

use App\Models\LoadPackage;
use App\Support\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreLoadPackageRequest extends FormRequest
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
        return $this->loadPackageRules();
    }

    /**
     * @return array<string, mixed>
     */
    protected function loadPackageRules(?LoadPackage $package = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('load_packages', 'slug')->ignore($package?->id)],
            'price_inc_vat' => ['required', 'numeric', 'min:0', 'max:100000'],
            'price_ex_vat' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'vat_rate_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_weight_kg' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'volume_cubic_yards' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'sack_equivalent' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'loading_time_minutes' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'pricing_status' => ['required', Rule::in(LoadPackage::pricingStatuses())],
            'is_popular' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * The ex-VAT price is derived from the inc-VAT price when the admin leaves it blank.
     *
     * @return array<string, mixed>
     */
    public function loadPackageAttributes(): array
    {
        $validated = $this->validated();

        $incVat = Money::toPence($validated['price_inc_vat']) ?? 0;
        $basisPoints = Money::percentToBasisPoints($validated['vat_rate_percent'])
            ?? config('pricing.default_vat_basis_points', 2000);

        return Arr::except($validated, ['price_inc_vat', 'price_ex_vat', 'vat_rate_percent']) + [
            'price_inc_vat_pence' => $incVat,
            'price_ex_vat_pence' => Money::toPence($validated['price_ex_vat'] ?? null)
                ?? Money::exVatPence($incVat, $basisPoints),
            'vat_rate_basis_points' => $basisPoints,
        ];
    }
}

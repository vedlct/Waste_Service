<?php

namespace App\Http\Requests\Admin;

use App\Models\ExtraCharge;
use App\Support\Money;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreExtraChargeRequest extends FormRequest
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
        return $this->extraChargeRules();
    }

    /**
     * @return array<string, mixed>
     */
    protected function extraChargeRules(?ExtraCharge $charge = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('extra_charges', 'slug')->ignore($charge?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'amount' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'is_variable' => ['required', 'boolean'],
            'charge_type' => ['required', Rule::in(array_keys(config('pricing.charge_types', [])))],
            'pricing_status' => ['required', Rule::in(ExtraCharge::pricingStatuses())],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $isVariable = $this->boolean('is_variable');
                $amount = $this->input('amount');

                if (! $isVariable && ($amount === null || $amount === '')) {
                    $validator->errors()->add('amount', 'A fixed charge needs an amount. Mark it variable if the price is quoted instead.');
                }

                if ($isVariable && $amount !== null && $amount !== '' && (float) $amount > 0) {
                    $validator->errors()->add('amount', 'A variable charge is quoted per job, so leave the amount blank.');
                }
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function extraChargeAttributes(): array
    {
        $validated = $this->validated();

        return Arr::except($validated, ['amount']) + [
            'amount_pence' => $this->boolean('is_variable') ? null : Money::toPence($validated['amount'] ?? null),
        ];
    }
}

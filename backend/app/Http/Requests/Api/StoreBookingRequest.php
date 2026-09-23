<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $collection = (array) $this->input('collection', []);

        // The public form posts `now` / `arrival`; map them onto the stored values.
        $option = collect(config('bookings.payment_options'))
            ->filter(fn (array $config, string $key): bool => ($config['form_value'] ?? null) === ($collection['payment_option'] ?? null))
            ->keys()
            ->first();

        if ($option) {
            $collection['payment_option'] = $option;
            $this->merge(['collection' => $collection]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $limits = config('bookings.submit');

        return [
            'items' => ['required', 'array', 'min:1', 'max:'.$limits['max_items']],
            'items.*.type' => ['required', Rule::in(['service_item', 'load_package'])],
            'items.*.id' => ['required', 'integer', 'min:1'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:'.$limits['max_quantity_per_line']],

            'collection' => ['required', 'array'],
            'collection.collection_date' => ['required', 'date', 'after_or_equal:today'],
            'collection.saturday_collection' => ['required', 'boolean'],
            'collection.notice_minutes' => ['required', 'integer', Rule::in(config('bookings.notice_minutes_options'))],
            'collection.payment_option' => ['required', Rule::in(array_keys(config('bookings.payment_options')))],
            'collection.access_confirmed' => ['required', 'boolean'],
            'collection.restricted_access' => ['required', Rule::in(['yes', 'no'])],
            'collection.access_restrictions' => ['nullable', 'string', 'max:2000'],
            'collection.large_items' => ['nullable', 'string', 'max:2000'],
            'collection.collection_notes' => ['nullable', 'string', 'max:2000'],

            'billing' => ['required', 'array'],
            ...$this->addressRules('billing', required: true),

            'collection_address' => ['nullable', 'array'],
            ...$this->addressRules('collection_address', required: false),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function addressRules(string $prefix, bool $required): array
    {
        $req = $required ? 'required' : 'nullable';
        $requiredWith = $required ? 'required' : 'required_with:'.$prefix;

        return [
            "{$prefix}.first_name" => [$requiredWith, 'string', 'max:255'],
            "{$prefix}.last_name" => [$requiredWith, 'string', 'max:255'],
            "{$prefix}.company" => ['nullable', 'string', 'max:255'],
            "{$prefix}.phone" => [$requiredWith, 'string', 'max:40'],
            "{$prefix}.mobile" => ['nullable', 'string', 'max:40'],
            "{$prefix}.email" => [$req === 'required' ? 'required' : 'nullable', 'email', 'max:255'],
            "{$prefix}.address_line_1" => [$requiredWith, 'string', 'max:255'],
            "{$prefix}.address_line_2" => ['nullable', 'string', 'max:255'],
            "{$prefix}.city" => [$requiredWith, 'string', 'max:255'],
            "{$prefix}.county" => ['nullable', 'string', 'max:255'],
            "{$prefix}.postcode" => [$requiredWith, 'string', 'max:20'],
            "{$prefix}.country" => ['nullable', 'string', 'max:80'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $collection = (array) $this->input('collection', []);

                if (($collection['restricted_access'] ?? null) === 'yes' && blank($collection['access_restrictions'] ?? null)) {
                    $validator->errors()->add(
                        'collection.access_restrictions',
                        'Please describe the access restrictions at the property.',
                    );
                }

                if (($collection['saturday_collection'] ?? false) && filled($collection['collection_date'] ?? null)) {
                    $date = strtotime((string) $collection['collection_date']);

                    if ($date && date('N', $date) !== '6') {
                        $validator->errors()->add(
                            'collection.collection_date',
                            'A Saturday collection needs a collection date that falls on a Saturday.',
                        );
                    }
                }
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function bookingData(): array
    {
        $validated = $this->validated();
        $addressKeys = [
            'first_name', 'last_name', 'company', 'phone', 'mobile', 'email',
            'address_line_1', 'address_line_2', 'city', 'county', 'postcode', 'country',
        ];

        $billing = Arr::only($validated['billing'], $addressKeys);
        $billing['country'] = $billing['country'] ?? 'United Kingdom';

        $collectionAddress = null;

        if (! empty($validated['collection_address'])) {
            $collectionAddress = Arr::only($validated['collection_address'], $addressKeys);
            $collectionAddress['country'] = $collectionAddress['country'] ?? 'United Kingdom';
        }

        return [
            'items' => $validated['items'],
            'collection' => $validated['collection'],
            'billing' => $billing,
            'collection_address' => $collectionAddress,
        ];
    }
}

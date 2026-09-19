<?php

namespace App\Http\Requests\Api;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'phone' => trim((string) $this->input('phone')),
            'email' => strtolower(trim((string) $this->input('email'))),
            'service' => trim((string) $this->input('service')),
            'message' => trim((string) $this->input('message')),
        ]);
    }

    /**
     * Mirrors the validation the public contact form already applies in the browser.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $limits = config('enquiries.submit');

        return [
            'name' => ['required', 'string', 'min:'.$limits['name']['min'], 'max:'.$limits['name']['max']],
            'phone' => ['required', 'string', 'min:'.$limits['phone']['min'], 'max:'.$limits['phone']['max']],
            'email' => ['required', 'email', 'max:'.$limits['email']['max']],
            'service' => ['required', Rule::in(array_keys(config('enquiries.service_options', [])))],
            'message' => ['required', 'string', 'min:'.$limits['message']['min'], 'max:'.$limits['message']['max']],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.min' => 'Please enter a valid name.',
            'name.max' => 'Please enter a valid name.',
            'phone.min' => 'Please enter a valid phone number.',
            'phone.max' => 'Please enter a valid phone number.',
            'email.email' => 'Please enter a valid email address.',
            'service.in' => 'Please choose a service.',
            'message.min' => 'Please enter a message between 10 and 2,000 characters.',
            'message.max' => 'Please enter a message between 10 and 2,000 characters.',
        ];
    }

    /**
     * Builds the stored enquiry, resolving the picked option to a real service where one exists.
     *
     * @return array<string, mixed>
     */
    public function enquiryAttributes(): array
    {
        $validated = $this->validated();
        $option = config('enquiries.service_options.'.$validated['service'], []);
        $slug = $option['slug'] ?? null;

        return [
            'service_id' => $slug ? Service::query()->where('slug', $slug)->value('id') : null,
            'service_label' => $option['label'] ?? $validated['service'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'status' => 'new',
            'source' => 'website',
            'ip_address' => $this->ip(),
            'user_agent' => str($this->userAgent())->limit(500)->toString(),
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Models\ContactEnquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Only the workflow fields are editable. The customer's own message is kept as submitted.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(ContactEnquiry::statuses())],
            'assigned_to' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('status', 'active'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'assigned_to.exists' => 'Pick an active admin user, or leave the enquiry unassigned.',
        ];
    }
}

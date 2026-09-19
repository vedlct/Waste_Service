<?php

namespace App\Http\Requests\Admin;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Only workflow fields are editable. The customer's own details, addresses, and the
     * priced line snapshots are never rewritten from admin.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Booking::statuses())],
            'payment_status' => ['required', Rule::in(Booking::paymentStatuses())],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
            'notify_customer' => ['nullable', 'boolean'],
        ];
    }
}

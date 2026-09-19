<?php

namespace App\Http\Requests\Admin;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
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
        return [
            'service_id' => ['nullable', 'integer', Rule::exists('services', 'id')->whereNull('deleted_at')],
            'reviewer_name' => ['required', 'string', 'max:255'],
            'reviewer_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:'.config('reviews.max_rating', 5)],
            'body' => ['required', 'string', 'max:5000'],
            'source' => ['required', Rule::in(array_keys(config('reviews.sources', [])))],
            'status' => ['required', Rule::in(Review::statuses())],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service_id.exists' => 'Pick a service that still exists, or leave the review unassigned.',
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'reviewer_name' => trim((string) $this->input('reviewer_name')),
            'reviewer_email' => strtolower(trim((string) $this->input('reviewer_email'))) ?: null,
            'body' => trim((string) $this->input('body')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $limits = config('reviews.submit');

        return [
            'reviewer_name' => ['required', 'string', 'min:'.$limits['name']['min'], 'max:'.$limits['name']['max']],
            'reviewer_email' => ['nullable', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:'.config('reviews.max_rating', 5)],
            'body' => ['required', 'string', 'min:'.$limits['body']['min'], 'max:'.$limits['body']['max']],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reviewer_name.required' => 'Please enter your name.',
            'reviewer_name.min' => 'Please enter your name.',
            'body.required' => 'Please write a few words about your experience.',
            'body.min' => 'Please write at least 10 characters.',
            'rating.*' => 'Please choose a rating between 1 and 5 stars.',
        ];
    }

    /**
     * Visitor reviews always start as pending, whatever the request says.
     *
     * @return array<string, mixed>
     */
    public function reviewAttributes(): array
    {
        $validated = $this->validated();

        return [
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'] ?? null,
            'rating' => $validated['rating'],
            'body' => $validated['body'],
            'source' => 'website',
            'status' => 'pending',
            'reviewed_at' => null,
            'published_at' => null,
        ];
    }
}

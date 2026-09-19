<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Only the labels and SEO fields are editable. Routes, templates and publishing are fixed
     * by the frontend code: a page switched to draft here would still render on the site.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'navigation_label' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:1000'],
            'og_image_id' => ['nullable', 'integer', Rule::exists('media_assets', 'id')->where(fn ($query) => $query->where('mime_type', 'like', 'image/%'))],
            // A full https URL on another site, or a path on this one such as `/houseClearance`.
            'canonical_url' => ['nullable', 'string', 'max:2048', 'regex:#^(https?://[^\s]+|/[^\s/][^\s]*|/)$#i'],
            'is_indexable' => ['required', 'boolean'],
            'is_followable' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'canonical_url.regex' => 'The canonical URL must be a full http(s) address or a path starting with /.',
            'og_image_id.exists' => 'The share image must be an image from the Media Library.',
        ];
    }
}

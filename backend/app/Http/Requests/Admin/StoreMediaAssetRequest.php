<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class StoreMediaAssetRequest extends FormRequest
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
        $mimes = array_merge(config('media.images.mimes'), config('media.files.mimes'));
        $mimeTypes = array_merge(config('media.images.mime_types'), config('media.files.mime_types'));
        $maxSize = max(config('media.images.max_size_kb'), config('media.files.max_size_kb'));

        return [
            'file' => ['required', 'file', 'max:'.$maxSize, 'mimes:'.implode(',', $mimes), 'mimetypes:'.implode(',', $mimeTypes)],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $file = $this->file('file');

                if (! $file || ! in_array($file->getMimeType(), config('media.images.mime_types'), true)) {
                    return;
                }

                if (($file->getSize() / 1024) > config('media.images.max_size_kb')) {
                    $validator->errors()->add('file', 'Images may not be greater than '.config('media.images.max_size_kb').' kilobytes.');
                }

                $dimensions = @getimagesize($file->getRealPath());

                if (! $dimensions) {
                    $validator->errors()->add('file', 'The uploaded image could not be inspected.');

                    return;
                }

                [$width, $height] = $dimensions;

                if ($width > config('media.images.max_width') || $height > config('media.images.max_height')) {
                    $validator->errors()->add('file', 'Images may not be larger than '.config('media.images.max_width').' x '.config('media.images.max_height').' pixels.');
                }
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validatedWithFileMetadata(): array
    {
        return Arr::only($this->validated(), ['alt_text']);
    }
}

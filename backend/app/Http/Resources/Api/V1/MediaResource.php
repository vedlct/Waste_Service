<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // A path starting with a slash is a file bundled with the frontend itself, so it
            // is returned relative and resolves against the site's own origin.
            'url' => str_starts_with((string) $this->path, '/') ? $this->path : $this->url,
            'alt' => $this->alt_text,
            'width' => $this->width,
            'height' => $this->height,
            'mime_type' => $this->mime_type,
        ];
    }
}

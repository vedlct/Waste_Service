<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->item_key,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'body' => $this->body,
            'url' => $this->url,
            'icon' => $this->icon,
            'settings' => $this->settings,
            'image' => MediaResource::make($this->whenLoaded('media')),
        ];
    }
}

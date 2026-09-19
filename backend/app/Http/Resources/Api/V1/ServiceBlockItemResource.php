<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceBlockItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'body' => $this->body,
            'icon' => $this->icon,
            'url' => $this->url,
            'settings' => $this->settings,
            'image' => MediaResource::make($this->whenLoaded('media')),
        ];
    }
}

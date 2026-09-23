<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageSectionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->section_key,
            'component' => $this->component,
            'eyebrow' => $this->eyebrow,
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'body' => $this->body,
            'settings' => $this->settings,
            'sort_order' => $this->sort_order,
            'image' => MediaResource::make($this->whenLoaded('media')),
            'items' => SectionItemResource::collection($this->whenLoaded('items')),
        ];
    }
}

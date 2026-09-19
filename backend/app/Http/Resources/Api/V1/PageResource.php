<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'route_path' => $this->route_path,
            'title' => $this->title,
            'navigation_label' => $this->navigation_label,
            'template' => $this->template,
            'seo' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'og_title' => $this->og_title,
                'og_description' => $this->og_description,
                'og_image' => MediaResource::make($this->whenLoaded('ogImage')),
                'canonical_url' => $this->canonical_url,
                'is_indexable' => (bool) $this->is_indexable,
                'is_followable' => (bool) $this->is_followable,
            ],
            'published_at' => $this->published_at?->toIso8601String(),
            'hero_image' => MediaResource::make($this->whenLoaded('heroMedia')),
            'sections' => PageSectionResource::collection($this->whenLoaded('sections')),
        ];
    }
}

<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'route_path' => $this->route_path,
            'name' => $this->name,
            'short_name' => $this->short_name ?: $this->name,
            'headline' => $this->headline,
            'summary' => $this->summary,
            'description' => $this->description,
            'is_featured' => (bool) $this->is_featured,
            'is_bookable' => (bool) $this->is_bookable,
            'published_at' => $this->published_at?->toIso8601String(),
            'category' => $this->whenLoaded('category', fn () => [
                'slug' => $this->category?->slug,
                'name' => $this->category?->name,
            ]),
            // SEO lives on the linked page record, so it is absent when no page is linked.
            'seo' => $this->whenLoaded('page', fn () => [
                'meta_title' => $this->page?->meta_title,
                'meta_description' => $this->page?->meta_description,
            ]),
            'hero_image' => MediaResource::make($this->whenLoaded('heroMedia')),
            'blocks' => ServiceContentBlockResource::collection($this->whenLoaded('contentBlocks')),
            'related' => ServiceSummaryResource::collection($this->whenLoaded('relatedServices')),
            'faqs' => FaqResource::collection($this->whenLoaded('faqs')),
        ];
    }
}

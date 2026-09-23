<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The shape used in service listings and cross-links. The full page payload is
 * `ServiceResource`.
 */
class ServiceSummaryResource extends JsonResource
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
            'is_featured' => (bool) $this->is_featured,
            'is_bookable' => (bool) $this->is_bookable,
            'sort_order' => $this->sort_order,
            'category' => $this->whenLoaded('category', fn () => [
                'slug' => $this->category?->slug,
                'name' => $this->category?->name,
            ]),
            'hero_image' => MediaResource::make($this->whenLoaded('heroMedia')),
        ];
    }
}

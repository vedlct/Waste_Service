<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceCategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'eyebrow' => $this->eyebrow,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'item_count' => $this->whenCounted('items'),
            'image' => MediaResource::make($this->whenLoaded('image')),
            'items' => ServiceItemResource::collection($this->whenLoaded('items')),
        ];
    }
}

<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoverageAreaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'postcode_prefix' => $this->postcode_prefix,
            'is_featured' => (bool) $this->is_featured,
            'sort_order' => $this->sort_order,
            'coordinates' => $this->has_coordinates ? [
                'latitude' => (float) $this->latitude,
                'longitude' => (float) $this->longitude,
            ] : null,
        ];
    }
}

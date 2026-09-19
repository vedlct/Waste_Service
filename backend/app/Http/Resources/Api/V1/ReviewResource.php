<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * The reviewer's email address is deliberately never exposed.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reviewer_name' => $this->reviewer_name,
            'rating' => $this->rating,
            'body' => $this->body,
            'source' => $this->source,
            'published_at' => $this->published_at?->toIso8601String(),
            'service' => $this->whenLoaded('service', fn () => [
                'slug' => $this->service?->slug,
                'name' => $this->service?->name,
            ]),
        ];
    }
}

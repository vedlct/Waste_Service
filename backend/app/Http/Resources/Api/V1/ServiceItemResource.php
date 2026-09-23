<?php

namespace App\Http\Resources\Api\V1;

use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'price' => Money::toApi($this->price_pence),
            'price_ex_vat' => Money::toApi($this->price_ex_vat_pence),
            'vat_rate' => Money::formatVatRate($this->vat_rate_basis_points),
            // A placeholder price is provisional and a quote_required item has no fixed
            // price, so the frontend must label them rather than just showing a number.
            'pricing_status' => $this->pricing_status,
            'requires_quote' => $this->pricing_status === 'quote_required',
            'is_provisional' => $this->needsPriceReview(),
            'sort_order' => $this->sort_order,
            'image' => MediaResource::make($this->whenLoaded('image')),
            'category' => $this->whenLoaded('priceCategory', fn () => [
                'slug' => $this->priceCategory?->slug,
                'name' => $this->priceCategory?->name,
            ]),
        ];
    }
}

<?php

namespace App\Http\Resources\Api\V1;

use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoadPackageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'price' => Money::toApi($this->price_inc_vat_pence),
            'price_ex_vat' => Money::toApi($this->price_ex_vat_pence),
            'vat_rate' => Money::formatVatRate($this->vat_rate_basis_points),
            'pricing_status' => $this->pricing_status,
            'is_provisional' => $this->needsPriceReview(),
            'capacity' => [
                'max_weight_kg' => $this->max_weight_kg,
                'volume_cubic_yards' => $this->volume_cubic_yards,
                'sack_equivalent' => $this->sack_equivalent,
                'loading_time_minutes' => $this->loading_time_minutes,
            ],
            'is_popular' => (bool) $this->is_popular,
            'sort_order' => $this->sort_order,
        ];
    }
}

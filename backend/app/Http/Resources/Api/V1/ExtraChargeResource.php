<?php

namespace App\Http\Resources\Api\V1;

use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtraChargeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'charge_type' => $this->charge_type,
            // A variable charge is quoted per job, so it carries no amount.
            'is_variable' => (bool) $this->is_variable,
            'amount' => Money::toApi($this->is_variable ? null : $this->amount_pence),
            'pricing_status' => $this->pricing_status,
            'sort_order' => $this->sort_order,
        ];
    }
}

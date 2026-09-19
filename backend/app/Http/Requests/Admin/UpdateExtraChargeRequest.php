<?php

namespace App\Http\Requests\Admin;

use App\Models\ExtraCharge;

class UpdateExtraChargeRequest extends StoreExtraChargeRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $charge = $this->route('extra_charge');

        return $this->extraChargeRules($charge instanceof ExtraCharge ? $charge : null);
    }
}

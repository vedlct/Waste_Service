<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceItem;

class UpdateServiceItemRequest extends StoreServiceItemRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $item = $this->route('service_item');

        return $this->serviceItemRules($item instanceof ServiceItem ? $item : null);
    }
}

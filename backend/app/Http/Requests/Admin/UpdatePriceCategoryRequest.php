<?php

namespace App\Http\Requests\Admin;

use App\Models\PriceCategory;

class UpdatePriceCategoryRequest extends StorePriceCategoryRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $category = $this->route('price_category');

        return $this->priceCategoryRules($category instanceof PriceCategory ? $category : null);
    }
}

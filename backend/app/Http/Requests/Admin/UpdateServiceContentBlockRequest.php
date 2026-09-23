<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceContentBlock;

class UpdateServiceContentBlockRequest extends StoreServiceContentBlockRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $block = $this->route('block');

        return $this->blockRules($block instanceof ServiceContentBlock ? $block : null);
    }
}

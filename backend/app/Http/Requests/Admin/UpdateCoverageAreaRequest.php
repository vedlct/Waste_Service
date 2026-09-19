<?php

namespace App\Http\Requests\Admin;

use App\Models\CoverageArea;

class UpdateCoverageAreaRequest extends StoreCoverageAreaRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $area = $this->route('coverage_area');

        return $this->coverageAreaRules($area instanceof CoverageArea ? $area : null);
    }
}

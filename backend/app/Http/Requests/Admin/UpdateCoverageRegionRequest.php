<?php

namespace App\Http\Requests\Admin;

use App\Models\CoverageRegion;

class UpdateCoverageRegionRequest extends StoreCoverageRegionRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $region = $this->route('coverage_region');

        return $this->coverageRegionRules($region instanceof CoverageRegion ? $region : null);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CoverageRegionResource;
use App\Models\CoverageRegion;

class CoverageController extends Controller
{
    public function index()
    {
        $regions = CoverageRegion::query()
            ->active()
            ->with(['areas' => fn ($query) => $query->active()->ordered()])
            ->ordered()
            ->get();

        return CoverageRegionResource::collection($regions);
    }
}

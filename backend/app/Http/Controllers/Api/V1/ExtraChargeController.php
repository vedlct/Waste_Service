<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ExtraChargeResource;
use App\Models\ExtraCharge;

class ExtraChargeController extends Controller
{
    public function index()
    {
        return ExtraChargeResource::collection(
            ExtraCharge::query()->active()->ordered()->get(),
        );
    }
}

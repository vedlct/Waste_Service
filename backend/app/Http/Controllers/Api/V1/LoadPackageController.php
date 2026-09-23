<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LoadPackageResource;
use App\Models\LoadPackage;

class LoadPackageController extends Controller
{
    public function index()
    {
        return LoadPackageResource::collection(
            LoadPackage::query()->active()->ordered()->get(),
        );
    }
}

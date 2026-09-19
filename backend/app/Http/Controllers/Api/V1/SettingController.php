<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

class SettingController extends Controller
{
    /**
     * Public site settings only. Anything with `is_public` false never leaves the admin.
     */
    public function index()
    {
        $settings = SiteSetting::query()
            ->public()
            ->ordered()
            ->get(['group', 'key', 'type', 'value'])
            ->groupBy('group')
            ->map(fn ($group) => $group->mapWithKeys(fn (SiteSetting $setting) => [
                $setting->key => $setting->value,
            ]));

        return response()->json(['data' => $settings]);
    }
}

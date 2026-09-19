<?php

namespace App\Http\Requests\Admin;

use App\Models\LoadPackage;

class UpdateLoadPackageRequest extends StoreLoadPackageRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $package = $this->route('load_package');

        return $this->loadPackageRules($package instanceof LoadPackage ? $package : null);
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;

class UpdateServiceRequest extends StoreServiceRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->serviceRules($this->service());
    }

    private function service(): ?Service
    {
        $service = $this->route('service');

        return $service instanceof Service ? $service : null;
    }
}

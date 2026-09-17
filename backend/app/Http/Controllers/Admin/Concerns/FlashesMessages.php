<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\RedirectResponse;

trait FlashesMessages
{
    protected function success(RedirectResponse $response, string $message): RedirectResponse
    {
        return $response->with('success', $message);
    }

    protected function error(RedirectResponse $response, string $message): RedirectResponse
    {
        return $response->with('error', $message);
    }
}

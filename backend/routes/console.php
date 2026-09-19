<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Removes audit log entries older than admin_access.activity_retention_days.
// Needs the scheduler running in production: `php artisan schedule:run` every minute.
Schedule::command('model:prune')->daily();

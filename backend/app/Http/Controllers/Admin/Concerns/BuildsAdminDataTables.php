<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

trait BuildsAdminDataTables
{
    protected function adminDataTable(Builder $query): mixed
    {
        return DataTables::eloquent($query);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function renderAdminPartial(string $view, array $data = []): string
    {
        return view($view, $data)->render();
    }

    protected function renderStatusBadge(string $label, string $class = 'muted'): string
    {
        return $this->renderAdminPartial('admin.partials.status-badge', [
            'label' => $label,
            'class' => $class,
        ]);
    }

    protected function formatAdminDate(?Carbon $date, string $format = 'd M Y', string $fallback = 'Never'): string
    {
        return $date?->format($format) ?? $fallback;
    }

    protected function formatBytes(?int $bytes): string
    {
        if (! $bytes) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $value = (float) $bytes;
        $unitIndex = 0;

        while ($value >= 1024 && $unitIndex < count($units) - 1) {
            $value /= 1024;
            $unitIndex++;
        }

        return round($value, $unitIndex === 0 ? 0 : 1).' '.$units[$unitIndex];
    }
}

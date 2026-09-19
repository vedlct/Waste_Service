<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'service_content_block_id',
    'media_id',
    'title',
    'subtitle',
    'body',
    'icon',
    'url',
    'settings',
    'is_enabled',
    'sort_order',
])]
class ServiceBlockItem extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_enabled' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(ServiceContentBlock::class, 'service_content_block_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'media_id');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}

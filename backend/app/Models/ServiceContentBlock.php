<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'service_id',
    'media_id',
    'block_key',
    'component',
    'eyebrow',
    'heading',
    'subheading',
    'body',
    'settings',
    'is_enabled',
    'sort_order',
])]
class ServiceContentBlock extends Model
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'media_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceBlockItem::class)->orderBy('sort_order')->orderBy('id');
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

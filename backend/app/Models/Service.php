<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'service_category_id',
    'page_id',
    'hero_media_id',
    'slug',
    'name',
    'short_name',
    'route_path',
    'headline',
    'summary',
    'description',
    'status',
    'is_featured',
    'is_bookable',
    'sort_order',
    'published_at',
])]
class Service extends Model
{
    use LogsActivity;
    use SoftDeletes;

    public const STATUSES = ['draft', 'published', 'archived'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_bookable' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ServiceContentBlock::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Constrained to the `related` pivot type so syncing never removes other relation types.
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function relatedServices(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'service_relations', 'service_id', 'related_service_id')
            ->withPivotValue('relation_type', 'related')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('service_relations.sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    protected function isPublished(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status === 'published');
    }
}

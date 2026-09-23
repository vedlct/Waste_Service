<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'parent_id',
    'hero_media_id',
    'slug',
    'route_path',
    'title',
    'navigation_label',
    'template',
    'meta_title',
    'meta_description',
    'og_title',
    'og_description',
    'og_image_id',
    'canonical_url',
    'status',
    'is_indexable',
    'is_followable',
    'sort_order',
    'published_at',
    'created_by',
    'updated_by',
])]
class Page extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_indexable' => 'boolean',
            'is_followable' => 'boolean',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    /**
     * The image shown when a link to the page is shared. Falls back to the site default.
     */
    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'og_image_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * The service whose page this is, if any. Its SEO can also be edited from the service form.
     */
    public function service(): HasOne
    {
        return $this->hasOne(Service::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}

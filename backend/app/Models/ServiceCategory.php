<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[Fillable([
    'parent_id',
    'slug',
    'name',
    'description',
    'icon',
    'is_active',
    'sort_order',
])]
class ServiceCategory extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Categories that may be chosen as a parent, excluding the category itself and its descendants.
     *
     * @return Collection<int, ServiceCategory>
     */
    public static function parentOptions(?self $exclude = null): Collection
    {
        $categories = static::query()->ordered()->get();

        if ($exclude === null || ! $exclude->exists) {
            return $categories;
        }

        $blocked = $exclude->descendantIds()->push($exclude->getKey());

        return $categories->reject(fn (self $category): bool => $blocked->contains($category->getKey()))->values();
    }

    /**
     * @return Collection<int, int>
     */
    public function descendantIds(): Collection
    {
        $ids = collect();
        $queue = collect([$this->getKey()]);

        while ($queue->isNotEmpty()) {
            // `reject` also guards against a cycle created by bad data.
            $children = static::query()
                ->whereIn('parent_id', $queue->all())
                ->pluck('id')
                ->reject(fn ($id): bool => $ids->contains($id) || (int) $id === (int) $this->getKey())
                ->values();

            $ids = $ids->merge($children);
            $queue = $children;
        }

        return $ids->unique()->values();
    }
}

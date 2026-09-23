<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'uploaded_by',
    'disk',
    'path',
    'original_name',
    'mime_type',
    'size_bytes',
    'width',
    'height',
    'alt_text',
    'metadata',
])]
class MediaAsset extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'size_bytes' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    protected function url(): Attribute
    {
        return Attribute::get(function (): string {
            if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
                return $this->path;
            }

            if (str_starts_with($this->path, '/')) {
                return asset(ltrim($this->path, '/'));
            }

            return Storage::disk($this->disk)->url($this->path);
        });
    }

    protected function isImage(): Attribute
    {
        return Attribute::get(fn (): bool => str_starts_with((string) $this->mime_type, 'image/'));
    }

    protected function dimensions(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->width && $this->height ? "{$this->width} x {$this->height}" : null);
    }

    /**
     * Assets seeded from bundled public files or remote URLs are not managed on a storage disk.
     */
    protected function isStoredOnDisk(): Attribute
    {
        return Attribute::get(fn (): bool => ! str_starts_with($this->path, 'http://')
            && ! str_starts_with($this->path, 'https://')
            && ! str_starts_with($this->path, '/'));
    }

    public function metadataValue(string $key, mixed $default = null): mixed
    {
        return data_get($this->metadata, $key, $default);
    }

    /**
     * Adds a `usage_count` column summing every catalogue/CMS reference to the asset.
     */
    public function scopeWithUsageCount(Builder $query): Builder
    {
        $references = static::usageReferences();

        if ($references === []) {
            return $query->selectRaw('0 as usage_count');
        }

        $expression = collect($references)
            ->map(fn (array $reference): string => sprintf(
                '(select count(*) from `%s` where `%s`.`%s` = `media_assets`.`id`)',
                $reference['table'],
                $reference['table'],
                $reference['column'],
            ))
            ->implode(' + ');

        return $query->selectRaw("({$expression}) as usage_count");
    }

    /**
     * @return array<int, array{label: string, count: int}>
     */
    public function usageBreakdown(): array
    {
        if (! $this->exists) {
            return [];
        }

        $usage = [];

        foreach (static::usageReferences() as $reference) {
            $count = DB::table($reference['table'])->where($reference['column'], $this->getKey())->count();

            if ($count > 0) {
                $usage[] = ['label' => $reference['label'], 'count' => $count];
            }
        }

        return $usage;
    }

    public function usageCount(): int
    {
        if (array_key_exists('usage_count', $this->attributes)) {
            return (int) $this->attributes['usage_count'];
        }

        return array_sum(array_column($this->usageBreakdown(), 'count'));
    }

    public function isInUse(): bool
    {
        return $this->usageCount() > 0;
    }

    /**
     * Only references to tables that exist and use safe identifiers are queried.
     *
     * @return array<int, array{table: string, column: string, label: string}>
     */
    protected static function usageReferences(): array
    {
        return collect(config('media.usage_references', []))
            ->filter(function (array $reference): bool {
                $table = $reference['table'] ?? '';
                $column = $reference['column'] ?? '';

                return preg_match('/^[A-Za-z0-9_]+$/', $table) === 1
                    && preg_match('/^[A-Za-z0-9_]+$/', $column) === 1
                    && Schema::hasTable($table);
            })
            ->map(fn (array $reference): array => [
                'table' => $reference['table'],
                'column' => $reference['column'],
                'label' => $reference['label'] ?? $reference['table'],
            ])
            ->values()
            ->all();
    }
}

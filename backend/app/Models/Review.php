<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'service_id',
    'reviewer_name',
    'reviewer_email',
    'rating',
    'body',
    'source',
    'status',
    'reviewed_at',
    'published_at',
])]
class Review extends Model
{
    use LogsActivity;

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return array_keys(config('reviews.statuses', []));
    }

    /**
     * The one status that puts a review on the website.
     */
    public static function publishedStatus(): string
    {
        foreach (config('reviews.statuses', []) as $key => $status) {
            if (! empty($status['publishes'])) {
                return $key;
            }
        }

        return 'published';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', static::publishedStatus());
    }

    public function scopeAwaitingModeration(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    protected function isPublished(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status === static::publishedStatus());
    }

    public function statusLabel(): string
    {
        return config('reviews.statuses.'.$this->status.'.label')
            ?? str($this->status)->headline()->toString();
    }

    public function sourceLabel(): string
    {
        return config('reviews.sources.'.$this->source)
            ?? str($this->source)->headline()->toString();
    }

    /**
     * Moderation timestamps follow the status, so admins never maintain them by hand.
     *
     * @return array<string, mixed>
     */
    public function moderationTimestamps(string $status): array
    {
        if ($status === 'pending') {
            return ['reviewed_at' => null, 'published_at' => null];
        }

        $publishes = (bool) config('reviews.statuses.'.$status.'.publishes', false);

        return [
            'reviewed_at' => $this->reviewed_at ?? now(),
            'published_at' => $publishes ? ($this->published_at ?? now()) : null,
        ];
    }
}

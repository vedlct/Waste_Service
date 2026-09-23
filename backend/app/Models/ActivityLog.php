<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'user_id',
    'action',
    'subject_type',
    'subject_id',
    'description',
    'changes',
    'ip_address',
    'created_at',
])]
class ActivityLog extends Model
{
    use MassPrunable;

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Entries older than the retention window are removed by `model:prune`.
     */
    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<', now()->subDays((int) config('admin_access.activity_retention_days', 365)));
    }

    /**
     * Renders an old/new value from `changes` for the audit screen.
     */
    public static function displayValue(mixed $value): string
    {
        return match (true) {
            $value === null, $value === '' => 'empty',
            is_bool($value) => $value ? 'yes' : 'no',
            is_scalar($value) => (string) $value,
            default => (string) json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        };
    }

    public function subjectLabel(): string
    {
        return $this->subject_type ? class_basename($this->subject_type) : 'System';
    }
}

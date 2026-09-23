<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'service_id',
    'name',
    'email',
    'phone',
    'service_label',
    'message',
    'status',
    'source',
    'ip_address',
    'user_agent',
    'assigned_to',
    'responded_at',
])]
class ContactEnquiry extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return array_keys(config('enquiries.statuses', []));
    }

    /**
     * Statuses that still need someone to act.
     *
     * @return array<int, string>
     */
    public static function openStatuses(): array
    {
        return collect(config('enquiries.statuses', []))
            ->reject(fn (array $status): bool => ! empty($status['responds']))
            ->reject(fn (array $status, string $key): bool => $key === 'spam')
            ->keys()
            ->all();
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', static::openStatuses());
    }

    /**
     * Finds an enquiry by name, email, message, service, or phone number in any format.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';
        $digits = preg_replace('/\D+/', '', $term) ?? '';

        return $query->where(function (Builder $builder) use ($like, $digits): void {
            $builder->where('name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('service_label', 'like', $like)
                ->orWhere('message', 'like', $like);

            if (strlen($digits) >= 4) {
                $builder->orWhereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') LIKE ?", ['%'.$digits.'%']);
            }
        });
    }

    public function scopeUnassigned(Builder $query): Builder
    {
        return $query->whereNull('assigned_to');
    }

    public function statusLabel(): string
    {
        return config('enquiries.statuses.'.$this->status.'.label')
            ?? str($this->status)->headline()->toString();
    }

    public function sourceLabel(): string
    {
        return config('enquiries.sources.'.$this->source)
            ?? str($this->source)->headline()->toString();
    }

    /**
     * `responded_at` follows the status so admins never maintain it by hand.
     *
     * @return array<string, mixed>
     */
    public function respondedTimestamp(string $status): array
    {
        $responds = (bool) config('enquiries.statuses.'.$status.'.responds', false);

        return ['responded_at' => $responds ? ($this->responded_at ?? now()) : null];
    }
}

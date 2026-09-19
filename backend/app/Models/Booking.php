<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'reference',
    'user_id',
    'service_id',
    'status',
    'payment_status',
    'payment_option',
    'collection_date',
    'is_saturday_collection',
    'notice_minutes',
    'access_surcharge_acknowledged',
    'restricted_access',
    'access_restrictions',
    'large_items',
    'collection_notes',
    'currency',
    'subtotal_pence',
    'discount_pence',
    'extra_charges_pence',
    'vat_pence',
    'total_pence',
    'metadata',
    'submitted_at',
    'confirmed_at',
])]
class Booking extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'is_saturday_collection' => 'boolean',
            'access_surcharge_acknowledged' => 'boolean',
            'notice_minutes' => 'integer',
            'subtotal_pence' => 'integer',
            'discount_pence' => 'integer',
            'extra_charges_pence' => 'integer',
            'vat_pence' => 'integer',
            'total_pence' => 'integer',
            'metadata' => 'array',
            'submitted_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class)->orderBy('id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(BookingAddress::class);
    }

    public function billingAddress(): HasOne
    {
        return $this->hasOne(BookingAddress::class)->where('type', 'billing');
    }

    public function collectionAddress(): HasOne
    {
        return $this->hasOne(BookingAddress::class)->where('type', 'collection');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BookingPayment::class)->orderByDesc('id');
    }

    /**
     * @return array<int, string>
     */
    public static function statuses(): array
    {
        return array_keys(config('bookings.statuses', []));
    }

    /**
     * @return array<int, string>
     */
    public static function paymentStatuses(): array
    {
        return array_keys(config('bookings.payment_statuses', []));
    }

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Bookings created for online payment that never got paid.
     */
    public function scopeAwaitingPayment(Builder $query): Builder
    {
        return $query->where('status', 'draft')->where('payment_status', 'unpaid');
    }

    /**
     * Finds a booking from what a customer quotes on the phone: the reference, their name,
     * email, first address line, postcode in any spacing, or phone number in any format.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';
        $compactPostcode = '%'.str_replace(' ', '', $term).'%';
        $digits = preg_replace('/\D+/', '', $term) ?? '';

        return $query->where(function (Builder $builder) use ($like, $compactPostcode, $digits): void {
            $builder->where('reference', 'like', $like)
                ->orWhereHas('addresses', function (Builder $address) use ($like, $compactPostcode, $digits): void {
                    $address->where(function (Builder $fields) use ($like, $compactPostcode, $digits): void {
                        $fields->whereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", [$like])
                            ->orWhere('email', 'like', $like)
                            ->orWhere('address_line_1', 'like', $like)
                            ->orWhereRaw("REPLACE(postcode, ' ', '') LIKE ?", [$compactPostcode]);

                        // Only a run of digits long enough to mean a phone number.
                        if (strlen($digits) >= 4) {
                            $fields->orWhereRaw("REGEXP_REPLACE(COALESCE(phone, ''), '[^0-9]', '') LIKE ?", ['%'.$digits.'%'])
                                ->orWhereRaw("REGEXP_REPLACE(COALESCE(mobile, ''), '[^0-9]', '') LIKE ?", ['%'.$digits.'%']);
                        }
                    });
                });
        });
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereNotNull('collection_date')
            ->whereDate('collection_date', '>=', now()->toDateString())
            ->whereNotIn('status', ['cancelled', 'completed']);
    }

    public function statusLabel(): string
    {
        return config('bookings.statuses.'.$this->status.'.label')
            ?? str($this->status)->headline()->toString();
    }

    public function paymentStatusLabel(): string
    {
        return config('bookings.payment_statuses.'.$this->payment_status.'.label')
            ?? str($this->payment_status)->headline()->toString();
    }

    public function paymentOptionLabel(): string
    {
        return config('bookings.payment_options.'.$this->payment_option.'.label')
            ?? str($this->payment_option)->headline()->toString();
    }

    protected function customerName(): Attribute
    {
        return Attribute::get(function (): string {
            $address = $this->relationLoaded('billingAddress')
                ? $this->billingAddress
                : $this->billingAddress()->first();

            return trim(($address?->first_name ?? '').' '.($address?->last_name ?? '')) ?: 'Unknown customer';
        });
    }

    /**
     * What is still owed: nothing once marked paid, otherwise the total less any payments
     * recorded against the booking. With online payment deferred there are usually none.
     */
    public function outstandingPence(): int
    {
        if ($this->payment_status === 'paid') {
            return 0;
        }

        $paid = (int) $this->payments()->whereIn('status', ['paid', 'succeeded'])->sum('amount_pence');

        return max(0, $this->total_pence - $paid);
    }

    public function adminNotes(): ?string
    {
        return data_get($this->metadata, 'admin_notes');
    }

    /**
     * Submitted and confirmed timestamps follow the workflow stage, so admins never
     * maintain them by hand. Cancelled sits outside the ladder and keeps what it had.
     *
     * @return array<string, mixed>
     */
    public function workflowTimestamps(string $status): array
    {
        $stage = config('bookings.statuses.'.$status.'.stage');

        if ($stage === null) {
            return ['submitted_at' => $this->submitted_at, 'confirmed_at' => $this->confirmed_at];
        }

        $submittedStage = config('bookings.statuses.submitted.stage', 1);
        $confirmedStage = config('bookings.statuses.confirmed.stage', 2);

        return [
            'submitted_at' => $stage >= $submittedStage ? ($this->submitted_at ?? now()) : null,
            'confirmed_at' => $stage >= $confirmedStage ? ($this->confirmed_at ?? now()) : null,
        ];
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use App\Support\Money;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'group',
    'key',
    'type',
    'value',
    'label',
    'help_text',
    'is_public',
    'updated_by',
])]
class SiteSetting extends Model
{
    use LogsActivity;

    public const TYPES = ['string', 'text', 'integer', 'money', 'boolean', 'url', 'email'];

    protected function casts(): array
    {
        return [
            'value' => 'json',
            'is_public' => 'boolean',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Reads one stored setting value, falling back when the row is missing or empty.
     */
    public static function valueOf(string $group, string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('group', $group)->where('key', $key)->value('value');

        if ($value === null || $value === '') {
            return $default;
        }

        // `value()` skips the model cast, so decode the stored JSON here.
        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? ($decoded ?? $default) : $value;
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    public function scopeGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('group')->orderBy('key');
    }

    /**
     * Integer settings whose key ends in `_pence` are edited in pounds but stored in pence.
     */
    protected function inputType(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->type === 'integer' && str_ends_with($this->key, '_pence')) {
                return 'money';
            }

            return in_array($this->type, self::TYPES, true) ? $this->type : 'string';
        });
    }

    protected function displayLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->label ?: str($this->key)->headline()->toString());
    }

    /**
     * The value shown in admin form inputs.
     */
    protected function formValue(): Attribute
    {
        return Attribute::get(function (): string|bool|null {
            return match ($this->input_type) {
                'money' => Money::toPounds($this->value === null ? null : (int) $this->value),
                'boolean' => (bool) $this->value,
                default => $this->value === null ? null : (string) $this->value,
            };
        });
    }

    /**
     * Converts a submitted admin input back into the stored representation.
     */
    public function castInput(mixed $input): mixed
    {
        return match ($this->input_type) {
            'money' => Money::toPence($input),
            'integer' => $input === null || $input === '' ? null : (int) $input,
            'boolean' => (bool) $input,
            default => $input === null || $input === '' ? null : (string) $input,
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function inputRules(): array
    {
        return match ($this->input_type) {
            'money' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'integer' => ['nullable', 'integer', 'min:0'],
            'boolean' => ['nullable', 'boolean'],
            'url' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'text' => ['nullable', 'string', 'max:2000'],
            default => ['nullable', 'string', 'max:255'],
        };
    }
}

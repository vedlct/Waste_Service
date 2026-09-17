<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}

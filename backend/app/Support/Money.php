<?php

namespace App\Support;

/**
 * Money is stored in integer pence everywhere and only converted to pounds for admin forms
 * and display. Keep every conversion in this class so rounding stays consistent.
 */
class Money
{
    public static function toPence(mixed $pounds): ?int
    {
        if ($pounds === null || $pounds === '') {
            return null;
        }

        return (int) round(((float) $pounds) * 100);
    }

    public static function toPounds(?int $pence): ?string
    {
        if ($pence === null) {
            return null;
        }

        return number_format($pence / 100, 2, '.', '');
    }

    public static function format(?int $pence, string $fallback = 'N/A'): string
    {
        if ($pence === null) {
            return $fallback;
        }

        return '£'.number_format($pence / 100, 2);
    }

    /**
     * The shape every money field takes in the public API.
     *
     * Pence is the source of truth; the formatted string is included so the frontend never
     * has to re-implement rounding or the currency symbol.
     *
     * @return array{pence: int, formatted: string, currency: string}|null
     */
    public static function toApi(?int $pence, string $currency = 'GBP'): ?array
    {
        if ($pence === null) {
            return null;
        }

        return [
            'pence' => $pence,
            'formatted' => self::format($pence),
            'currency' => $currency,
        ];
    }

    /**
     * VAT rates are stored in basis points, so 2000 means 20%.
     */
    public static function formatVatRate(?int $basisPoints, string $fallback = 'N/A'): string
    {
        if ($basisPoints === null) {
            return $fallback;
        }

        return rtrim(rtrim(number_format($basisPoints / 100, 2, '.', ''), '0'), '.').'%';
    }

    public static function percentToBasisPoints(mixed $percent): ?int
    {
        if ($percent === null || $percent === '') {
            return null;
        }

        return (int) round(((float) $percent) * 100);
    }

    public static function basisPointsToPercent(?int $basisPoints): ?string
    {
        if ($basisPoints === null) {
            return null;
        }

        return rtrim(rtrim(number_format($basisPoints / 100, 2, '.', ''), '0'), '.');
    }

    public static function exVatPence(?int $incVatPence, ?int $basisPoints): ?int
    {
        if ($incVatPence === null) {
            return null;
        }

        $rate = ($basisPoints ?? 0) / 10000;

        return (int) round($incVatPence / (1 + $rate));
    }
}

<?php

namespace App\Support;

/**
 * Spreadsheet apps run a cell starting with =, +, -, @, tab or carriage return as a
 * formula. Customer-typed values (names, notes) go into exports, so a name such as
 * `=HYPERLINK("https://evil.example","Click")` must be neutralised first.
 */
class CsvCell
{
    public static function safe(mixed $value): string
    {
        $text = (string) ($value ?? '');

        if ($text !== '' && in_array($text[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$text;
        }

        return $text;
    }
}

<?php

namespace App\Support;

/**
 * Laravel's mail lines are rendered as Markdown. Anything a visitor typed must be escaped
 * first, or a message containing `[click here](https://...)` would arrive in the office
 * inbox as a real link.
 */
class MarkdownText
{
    public static function escape(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        // `<`, `>` and `&` are left alone: the mail template already HTML-escapes them.
        return preg_replace('/([\\\\`*_{}\[\]()#+\-.!|~])/', '\\\\$1', $text) ?? '';
    }
}

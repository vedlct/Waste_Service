<?php

namespace App\Http\Controllers\Admin\Concerns;

trait GuardsDeletions
{
    /**
     * Builds the standard "still in use" message, or null when nothing references the record.
     *
     * @param array<string, int> $counts Label to reference count.
     */
    protected function deletionBlockedMessage(string $subject, array $counts): ?string
    {
        $referenced = array_filter($counts, fn (int $count): bool => $count > 0);

        if ($referenced === []) {
            return null;
        }

        $summary = collect($referenced)
            ->map(fn (int $count, string $label): string => $label.' ('.$count.')')
            ->implode(', ');

        return $subject.' is still in use and cannot be deleted: '.$summary.'.';
    }
}

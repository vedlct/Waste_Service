<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Throwable;

/**
 * Records admin changes to the model in `activity_logs`.
 *
 * Only changes made by a signed-in user are logged, so public website submissions and
 * seeding stay out of the audit trail. A logging failure never blocks the change itself.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn (Model $model) => $model->recordActivity('created'));
        static::updated(fn (Model $model) => $model->recordActivity('updated'));
        static::deleted(fn (Model $model) => $model->recordActivity(
            method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting() ? 'archived' : 'deleted',
        ));
    }

    /**
     * A short human label for the audit screen, such as a booking reference.
     */
    public function activityLabel(): string
    {
        foreach (['reference', 'name', 'title', 'question', 'reviewer_name', 'original_name', 'email', 'key'] as $attribute) {
            if (filled($this->getAttribute($attribute))) {
                return (string) $this->getAttribute($attribute);
            }
        }

        return '#'.$this->getKey();
    }

    /**
     * Attributes never written to the log.
     *
     * @return array<int, string>
     */
    protected function activityHidden(): array
    {
        return ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];
    }

    public function recordActivity(string $action): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $changes = $action === 'updated' ? $this->activityChanges() : null;

        // An update that only touched hidden columns is not worth a log line.
        if ($action === 'updated' && $changes === []) {
            return;
        }

        try {
            ActivityLog::create([
                'user_id' => $user->getAuthIdentifier(),
                'action' => $action,
                'subject_type' => $this->getMorphClass(),
                'subject_id' => $this->getKey(),
                'description' => class_basename($this).' '.str($this->activityLabel())->limit(180),
                'changes' => $changes,
                'ip_address' => Request::ip(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * @return array<string, array{old: mixed, new: mixed}>
     */
    protected function activityChanges(): array
    {
        $changes = [];

        foreach (array_keys($this->getChanges()) as $attribute) {
            if (in_array($attribute, $this->activityHidden(), true)) {
                continue;
            }

            $changes[$attribute] = [
                'old' => $this->getOriginal($attribute),
                'new' => $this->getAttribute($attribute),
            ];
        }

        return $changes;
    }
}

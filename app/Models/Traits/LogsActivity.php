<?php

namespace App\Models\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            $original = [];
            foreach (array_keys($changes) as $key) {
                $original[$key] = $model->getOriginal($key);
            }
            if (! empty($original)) {
                $model->logActivity('updated', $original, $changes);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getAttributes());
        });
    }

    protected function logActivity(string $action, ?array $oldValues = null, ?array $newValues = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => static::class,
            'model_id' => $this->getKey(),
            'description' => $this->activityDescription($action),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
        ]);
    }

    protected function activityDescription(string $action): string
    {
        $label = class_basename(static::class);
        $identifier = $this->activityIdentifier();

        return match ($action) {
            'created' => "Membuat {$label} baru: {$identifier}",
            'updated' => "Mengubah {$label}: {$identifier}",
            'deleted' => "Menghapus {$label}: {$identifier}",
            default => "{$action} {$label}: {$identifier}",
        };
    }

    protected function activityIdentifier(): string
    {
        return $this->nama ?? $this->name ?? "ID #{$this->getKey()}";
    }
}

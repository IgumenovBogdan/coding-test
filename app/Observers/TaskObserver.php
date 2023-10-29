<?php

namespace App\Observers;

use App\Models\Task;
use Carbon\Carbon;

class TaskObserver
{
    public function updated(Task $task): void
    {
        if ($task->phase->is_completive) {
            $task->completed_at = Carbon::now();
            $task->saveQuietly();
        }
    }
}

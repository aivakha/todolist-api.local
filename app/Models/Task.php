<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function isDone(): bool
    {
        return $this->status === TaskStatus::DONE->value;
    }

    public function hasUncompletedSubtasks(): bool
    {
        if ($this->subtasks()->where('status', TaskStatus::TODO->value)->exists()) {
            return true;
        }

        foreach ($this->subtasks as $subtask) {
            if ($subtask->hasUncompletedSubtasks()) {
                return true;
            }
        }

        return false;
    }
}

<?php

namespace App\Services\Api\V1;

use App\Enums\TaskStatus;
use App\Exceptions\Api\V1\Task\TaskDeleteException;
use App\Exceptions\Api\V1\Task\TaskUpdateException;
use App\Models\Task;

class TaskService
{
    public function store(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        throw_if(
            (int) $data['status'] === TaskStatus::DONE->value && $task->hasUncompletedSubtasks(),
            TaskUpdateException::class
        );

        $task->update($data);

        return $task;
    }

    public function setCompleted(Task $task): Task
    {
        throw_if($task->hasUncompletedSubtasks(),TaskUpdateException::class);

        $task->update(['status' => TaskStatus::DONE->value]);

        return $task;
    }

    public function delete(Task $task): void
    {
        throw_if($task->isDone(),TaskDeleteException::class);

        $task->subtasks->each->delete();
        $task->delete();
    }
}

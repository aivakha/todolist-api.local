<?php

namespace App\Services\Api\V1;

use App\Enums\TaskStatus;
use App\Models\Task;

class TaskService
{
    public function store(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * @throws \Exception
     */
    public function update(Task $task, array $data): Task
    {
        if ((int) $data['status'] === TaskStatus::DONE->value && $task->hasUncompletedSubtasks()) {
            throw new \Exception('Cannot mark as "DONE" while subtasks have "TODO" status.');
        }

        $task->update($data);

        return $task;
    }

    /**
     * @throws \Exception
     */
    public function delete(Task $task): void
    {
        if ($task->isDone()) {
            throw new \Exception('Cannot delete task with status "DONE".');
        }

        $task->subtasks->each->delete();
        $task->delete();
    }
}

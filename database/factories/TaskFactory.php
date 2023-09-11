<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskStatusCases = TaskStatus::cases();
        $taskStatus = $taskStatusCases[array_rand($taskStatusCases)]->value;

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $taskStatus,
            'priority' => $this->faker->numberBetween(1, 5),
            'user_id' => User::inRandomOrder()->first()->id,
            'completed_at' => $taskStatus === 1 ? now()->toDateTimeString() : null
        ];
    }

    /**
     * Configure the factory to create subtasks after creating a task.
     *
     * @param int $totalTasks The total number of tasks to create
     * @return $this
     */
    public function configure(int $totalTasks = 1)
    {
        return $this->afterCreating(function (Task $task) use ($totalTasks) {
            $this->createSubtasks($task, 3, 2, $totalTasks, $task->user_id);
        });
    }

    /**
     * Recursively create subtasks up to a maximum level with a maximum number of subtasks at each level.
     *
     * @param Task $parentTask
     * @param int $maxLevels
     * @param int $maxSubtasks
     * @param int $totalTasks
     * @param int $parentUserId
     */
    private function createSubtasks(Task $parentTask, int $maxLevels, int $maxSubtasks, int &$totalTasks, int $parentUserId)
    {
        if ($maxLevels <= 0 || $totalTasks <= 0) {
            return;
        }

        $numSubtasks = rand(0, $maxSubtasks);

        for ($i = 0; $i < $numSubtasks && $totalTasks > 0; $i++) {
            Task::factory()->create([
                'parent_id' => $parentTask->id,
                'user_id' => $parentUserId,
            ])->each(function (Task $task) use ($maxLevels, $maxSubtasks, &$totalTasks, $parentUserId) {
                $totalTasks--;
                $this->createSubtasks($task, $maxLevels - 1, $maxSubtasks, $totalTasks, $parentUserId);
            });
        }
    }
}

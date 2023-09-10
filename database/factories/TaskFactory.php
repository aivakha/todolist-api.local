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
        $taskStatus = $taskStatusCases[array_rand($taskStatusCases)];

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $taskStatus,
            'priority' => $this->faker->numberBetween(1, 5),
            'user_id' => User::inRandomOrder()->first()->id,
            'completed_at' => $taskStatus == 1 ? now()->toDateTimeString() : null
        ];
    }

    /**
     * Configure the factory to create subtasks after creating a task.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Task $task) {
            $numberOfSubtasks = $this->faker->numberBetween(0, 5);

            if ($numberOfSubtasks > 0) {
                Task::factory()
                    ->count($numberOfSubtasks)
                    ->create([
                        'parent_id' => $task->id,
                    ]);
            }
        });
    }
}


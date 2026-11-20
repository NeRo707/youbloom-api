<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional(0.8)->paragraph(),
            'status' => fake()->randomElement([Task::STATUS_PENDING, Task::STATUS_COMPLETED]),
            'order' => fake()->numberBetween(0, 50),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => Task::STATUS_PENDING]);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => Task::STATUS_COMPLETED]);
    }
}

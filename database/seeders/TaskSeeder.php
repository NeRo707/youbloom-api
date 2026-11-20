<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Design responsive dashboard layout with Bootstrap 4',
                'description' => 'Create a user-friendly UI incorporating cards, navigation, badges, and responsive tables.',
                'status' => Task::STATUS_COMPLETED,
                'order' => 1,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Set up database migrations and Eloquent models',
                'description' => 'Define the tasks table with title, description, status, and order columns.',
                'status' => Task::STATUS_COMPLETED,
                'order' => 2,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Implement task creation with form validation',
                'description' => 'Add validation for required title and status fields with clear error messages.',
                'status' => Task::STATUS_COMPLETED,
                'order' => 3,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(12),
            ],
            [
                'title' => 'Add deletion confirmation modal',
                'description' => 'Display a Bootstrap 4 modal asking for confirmation before deleting any task.',
                'status' => Task::STATUS_PENDING,
                'order' => 4,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Implement quick status toggle button',
                'description' => 'Allow users to quickly toggle between Pending and Completed directly from the task list.',
                'status' => Task::STATUS_PENDING,
                'order' => 5,
                'created_at' => now()->subHours(18),
                'updated_at' => now()->subHours(18),
            ],
            [
                'title' => 'Integrate search bar and status filter',
                'description' => 'Filter tasks by title keywords and filter by Pending or Completed status.',
                'status' => Task::STATUS_PENDING,
                'order' => 6,
                'created_at' => now()->subHours(10),
                'updated_at' => now()->subHours(10),
            ],
            [
                'title' => 'Implement drag-and-drop task reordering',
                'description' => 'Allow users to reorder tasks seamlessly via drag-and-drop and persist order via AJAX.',
                'status' => Task::STATUS_PENDING,
                'order' => 7,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'title' => 'Write comprehensive feature and unit tests',
                'description' => 'Ensure complete test coverage for CRUD operations, validation, filtering, and reordering.',
                'status' => Task::STATUS_PENDING,
                'order' => 8,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}

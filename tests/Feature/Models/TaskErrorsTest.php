<?php

namespace Tests\Feature\Models;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class TaskErrorsTest extends TestCase
{
    public function test_task_cant_be_deleted_if_it_doesnt_exist(): void
    {
        $response = $this->deleteJson('/api/tasks/9999');

        $response
            ->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Task] 9999',
            ]);
    }

    public function test_task_cant_be_updated_if_it_doesnt_exist(): void
    {
        $response = $this->putJson('/api/tasks/9999', ['title' => 'New Title']);

        $response
            ->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Task] 9999',
            ]);
    }

    public function test_task_cant_be_found_if_it_doesnt_exist(): void
    {
        $response = $this->getJson('/api/tasks/9999');

        $response
            ->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\\Models\\Task] 9999',
            ]);
    }

    public function test_task_updating_requires_at_least_one_field()
    {
        // First create a task
        $task = Task::factory()->create([
            'title' => 'Test Title',
            'description' => 'Lorem Ipsum Dolor sit amet.',
            'completed' => true,
        ]);

        // Then try to update a task without any value
        $response = $this->putJson("/api/tasks/{$task->id}", []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'title' => 'The title field is required when none of description / completed are present.',
                'description' => 'The description field is required when none of title / completed are present.',
                'completed' => 'The completed field is required when none of description / title are present.'
            ]);
    }
}


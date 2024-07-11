<?php

namespace Tests\Feature\Models;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_task_can_be_created(): void
    {
        $data = [
            'title' => 'Test Title',
            'description' => 'Lorem Ipsum Dolor sit amet.',
            'completed' => true,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => $data,
            ]);
    }

    public function test_task_can_be_updated(): void
    {
        // First create a task
        $task = $this->createSampleTask();

        // Then test updating the task
        $newData = [
            'title' => 'New Title',
            'description' => 'UPDATED DESCRIPTION.',
            'completed' => false,
        ];
        $response = $this->putJson("/api/tasks/{$task->id}", $newData);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => $newData,
            ]);
    }

    public function test_task_can_be_deleted(): void
    {
        // First create a task
        $task = $this->createSampleTask();

        // Then test deleting the task
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response
            ->assertStatus(200);
    }

    public function test_task_can_be_found(): void
    {
        // First create a task
        $task = $this->createSampleTask();


        // Then test retrieving it by id
        $response = $this->getJson("/api/tasks/{$task->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => (array)(new TaskResource($task))['data'],
            ]);
    }

    public function test_all_tasks_can_be_found(): void
    {
        // First create several tasks via API
        $allData = [];
        for ($i = 1; $i <= 5; $i++) {
            $data = [
                'title' => "Test Title {$i}",
                'description' => 'Lorem Ipsum Dolor sit amet.',
                'completed' => true,
                'created_by_name' => $this->firstUser->name,
                'updated_by_name' => $this->firstUser->name,
            ];
            $allData[] = $data;
            $this->postJson('/api/tasks', $data);
        }

        // Reverse $allData to simulate order descending
        $expectedPagedData = array_chunk(array_reverse($allData), 2);

        // Then test endpoint finds them all, page by page
        $page1 = $this->getJson("/api/tasks?" . http_build_query([
            'page' => 1,
            'per_page' => 2
        ]));

        $tasksUrl = route('tasks.index');

        $page1
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedPagedData[0],
                'links' => [
                    "first" => "{$tasksUrl}?per_page=2&page=1",
                    "last" => "{$tasksUrl}?per_page=2&page=3",
                    "prev" => null,
                    "next" => "{$tasksUrl}?per_page=2&page=2"
                ],
                "meta" => [
                    "current_page" => 1,
                    "from" => 1,
                    "last_page" => 3,
                    "links" => [
                        [
                            "url" => null,
                            "label" => "&laquo; Previous",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=1",
                            "label" => "1",
                            "active" => true
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=2",
                            "label" => "2",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=3",
                            "label" => "3",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=2",
                            "label" => "Next &raquo;",
                            "active" => false
                        ]
                    ],
                    "path" => $tasksUrl,
                    "per_page" => 2,
                    "to" => 2,
                    "total" => 5
                ]
            ]);

        $page2 = $this->getJson("/api/tasks?" . http_build_query([
            'page' => 2,
            'per_page' => 2
        ]));

        $page2
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedPagedData[1],
                'links' => [
                    "first" => "{$tasksUrl}?per_page=2&page=1",
                    "last" => "{$tasksUrl}?per_page=2&page=3",
                    "prev" => "{$tasksUrl}?per_page=2&page=1",
                    "next" => "{$tasksUrl}?per_page=2&page=3",
                ],
                "meta" => [
                    "current_page" => 2,
                    "from" => 3,
                    "last_page" => 3,
                    "links" => [
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=1",
                            "label" => "&laquo; Previous",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=1",
                            "label" => "1",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=2",
                            "label" => "2",
                            "active" => true
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=3",
                            "label" => "3",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=3",
                            "label" => "Next &raquo;",
                            "active" => false
                        ]
                    ],
                    "path" => $tasksUrl,
                    "per_page" => 2,
                    "to" => 4,
                    "total" => 5
                ]
            ]);

        $page3 = $this->getJson("/api/tasks?" . http_build_query([
            'page' => 3,
            'per_page' => 2
        ]));

        $page3
            ->assertStatus(200)
            ->assertJson([
                'data' => $expectedPagedData[2],
                'links' => [
                    "first" => "{$tasksUrl}?per_page=2&page=1",
                    "last" => "{$tasksUrl}?per_page=2&page=3",
                    "prev" => "{$tasksUrl}?per_page=2&page=2",
                    "next" => null,
                ],
                "meta" => [
                    "current_page" => 3,
                    "from" => 5,
                    "last_page" => 3,
                    "links" => [
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=2",
                            "label" => "&laquo; Previous",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=1",
                            "label" => "1",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=2",
                            "label" => "2",
                            "active" => false
                        ],
                        [
                            "url" => "{$tasksUrl}?per_page=2&page=3",
                            "label" => "3",
                            "active" => true
                        ],
                        [
                            "url" => null,
                            "label" => "Next &raquo;",
                            "active" => false
                        ]
                    ],
                    "path" => $tasksUrl,
                    "per_page" => 2,
                    "to" => 5,
                    "total" => 5
                ]
            ]);
    }

    public function test_task_updating_requires_at_least_one_field()
    {
        // First create a task
        $task = $this->createSampleTask();

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

    #[TestWith(['title', 'New Title'])]
    #[TestWith(['description', 'New Description'])]
    #[TestWith(['completed', true])]
    public function test_task_can_be_updated_with_at_least_one_field($fieldName, $newValue)
    {
        // First create a task
        $task = $this->createSampleTask();

        // Then try to update a single field of the task
        $response = $this->putJson("/api/tasks/{$task->id}", [$fieldName => $newValue]);

        $response
            ->assertStatus(200);
    }

    private function createSampleTask(): Task
    {
        return Task::factory()->create([
            'title' => 'Test Title',
            'description' => 'Lorem Ipsum Dolor sit amet.',
            'completed' => true,
        ]);
    }
}

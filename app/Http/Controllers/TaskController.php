<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    public function __construct(private TaskService $taskService){}

    /**
     * Get a paginated list of all tasks.
     */
    public function index(Request $request): TaskCollection
    {
        return $this->taskService->findAll($request->integer('per_page', 10));
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        return $this->taskService->create($request->only('title', 'description', 'completed'));
    }

    /**
     * Find a specific task by id.
     */
    public function show(int $task): TaskResource
    {
        return $this->taskService->findById($task, true);
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, int $task): TaskResource
    {
        return $this->taskService->update($request->only('title', 'description', 'completed'), $task);
    }

    /**
     * Delete a task by id.
     */
    public function destroy(int $task): bool
    {
        return $this->taskService->delete($task);
    }
}

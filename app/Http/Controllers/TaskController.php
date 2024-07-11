<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->taskService->findAll($request->has('per_page') ? $request->get('per_page') : 10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        return $this->taskService->create($request->only('title', 'description', 'completed'));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $task)
    {
        return $this->taskService->findById($task, true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, int $task)
    {
        return $this->taskService->update($request->only('title', 'description', 'completed'), $task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $task)
    {
        return $this->taskService->delete($task);
    }
}

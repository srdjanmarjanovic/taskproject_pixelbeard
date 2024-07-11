<?php

namespace App\Services;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Repositories\Task\TaskRepositoryInterface;
use Closure;

class TaskService
{
    private Closure $authenticatedUserResolver;

    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        callable $authenticatedUserResolver
    ){
        $this->authenticatedUserResolver = $authenticatedUserResolver;
    }

    public function create(array $data): TaskResource
    {
        $authenticatedUser = call_user_func($this->authenticatedUserResolver);

        $data['created_by'] = $authenticatedUser->id;
        $data['updated_by'] = $authenticatedUser->id;
        
        return new TaskResource($this->taskRepository->create($data));
    }

    public function update(array $data, $id): TaskResource
    {
        $authenticatedUser = call_user_func($this->authenticatedUserResolver);

        $data['updated_by'] = $authenticatedUser->id;

        return new TaskResource($this->taskRepository->update($data, $id));
    }

    public function delete(int $id): bool 
    {
        return $this->taskRepository->delete($id);
    }
    
    public function findById(int $id, bool $includeRelations): TaskResource 
    {
        return new TaskResource($this->taskRepository->findById($id, $includeRelations));
    }
    
    public function findAll(int $perPage = 10): TaskCollection 
    {
        return new TaskCollection($this->taskRepository->findAll($perPage));
    }
}

<?php 

namespace App\Repositories\Task;

use App\Models\Task;
use Illuminate\Contracts\Pagination\Paginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(array $data, $id): Task
    {
        $user = Task::findOrFail($id);
        $user->update($data);
        
        return $user;
    }

    public function delete(int $id): bool
    {
        $user = Task::findOrFail($id);
        
        return $user->delete();
    }

    public function findById(int $id, bool $includeRelations): Task
    {
        $query = Task::query();
        
        if ($includeRelations) {
            $query->with(['createdBy','updatedBy']);
        }

        return $query->findOrFail($id);
    }
    
    public function findAll(int $perPage): Paginator
    {
        return Task::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }
}
<?php 

namespace App\Repositories\Task;

use App\Models\Task;
use Illuminate\Contracts\Pagination\Paginator;

interface TaskRepositoryInterface
{
    public function create(array $data): Task;
    public function update(array $data, $id): Task;
    public function delete(int $id): bool;
    public function findById(int $id, bool $includeRelations): Task;
    public function findAll(int $perPage): Paginator;
}
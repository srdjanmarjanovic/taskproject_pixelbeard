<?php

namespace Tests\Unit;

use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\TaskService;
use Mockery\MockInterface;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    private TaskRepositoryInterface $taskRepository;

    protected function setUp(): void
    {        
        parent::setUp();

        $this->taskRepository = $this->mock(TaskRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('process')->once();
        });
    }

    // public function test_container_service_will_use_mocked_repo(): void
    // {
    //     $this->taskRepository->expects($this->once())->method('create')->with([]);
    //     $this->taskRepository->expects($this->once())->method('update')->with([], 1);
    //     $this->taskRepository->expects($this->once())->method('findAll')->with(10);
    //     $this->taskRepository->expects($this->once())->method('findById')->with(1);
    //     $this->taskRepository->expects($this->once())->method('delete')->with(1);

    //     $service = app()->get(TaskService::class);
        
    //     $service->create([]);
    //     $service->update([], 1);
    //     $service->findAll(10);
    //     $service->findById(1, true);
    //     $service->delete(1);
    // }
}

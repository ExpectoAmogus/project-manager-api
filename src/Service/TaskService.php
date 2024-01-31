<?php

namespace App\Service;

use App\Entity\Project;
use App\Entity\Task;
use App\Exception\EntityAlreadyExistsException;
use App\Exception\EntityNotFoundException;
use App\Mapper\TaskMapper;
use App\Model\TaskListItem;
use App\Model\TaskListResponse;
use App\Model\TaskCreateRequest;
use App\Model\TaskUpdateRequest;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(private TaskRepository $taskRepository,
                                private EntityManagerInterface $manager,
                                private TaskMapper $taskMapper)
    {
    }

    public function getAllTasks(): TaskListResponse
    {
        $tasks = $this->taskRepository->findAllSortedByDeadline();
        $taskDTOs = array_map([$this->taskMapper, 'mapToDTO'], $tasks);

        return new TaskListResponse($taskDTOs);
    }

    public function getTaskById($taskId): TaskListItem
    {
        if (!$this->taskRepository->existsById($taskId))
        {
            throw new EntityNotFoundException();
        }
        $task = $this->taskRepository->find($taskId);

        return $this->taskMapper->mapToDTO($task);
    }

    public function createTask(TaskCreateRequest $taskCreateRequest): void
    {
        if (!$this->manager->getRepository(Project::class)->existsById($taskCreateRequest->getProjectId())) {
            throw new EntityNotFoundException();
        }
        if (!$this->taskRepository->existsByTitle($taskCreateRequest->getTitle()))
        {
            throw new EntityAlreadyExistsException();
        }
        $project = $this->manager->getRepository(Project::class)->find($taskCreateRequest->getProjectId());

        $task = $this->taskMapper->mapToEntity($taskCreateRequest, $project);

        $this->manager->persist($task);
        $this->manager->flush();
    }

    public function updateTask(TaskUpdateRequest $updatedTask, int $id): void
    {
        if (!$this->taskRepository->existsById($id))
        {
            throw new EntityNotFoundException();
        }

        $task = $this->taskRepository->find($id);

        $this->taskMapper->mapToEntityFromUpdateRequest($updatedTask, $task);

        $this->manager->persist($task);
        $this->manager->flush();
    }

    public function deleteTask(int $id): void
    {
        if (!$this->taskRepository->existsById($id))
        {
            throw new EntityNotFoundException();
        }
        $task = $this->taskRepository->find($id);
        $this->manager->remove($task);
        $this->manager->flush();
    }
}
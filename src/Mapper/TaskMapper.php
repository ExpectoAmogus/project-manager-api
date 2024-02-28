<?php

namespace App\Mapper;

use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Model\TaskCreateRequest;
use App\Model\TaskListItem;
use App\Model\TaskUpdateRequest;

class TaskMapper
{
    public function mapToEntity(TaskCreateRequest $taskRequest, Project $project): Task
    {
        $task = new Task();
        $task
            ->setTitle($taskRequest->getTitle())
            ->setDescription($taskRequest->getDescription())
            ->setDeadline(new \DateTimeImmutable('@' . $taskRequest->getDeadline()))
            ->setStatus(TaskStatus::TODO)
            ->setProject($project);

        return $task;
    }

    public function mapToEntityFromUpdateRequest(TaskUpdateRequest $updatedTask, Task $task): Task
    {
        $task
            ->setTitle($updatedTask->getTitle())
            ->setDescription($updatedTask->getDescription())
            ->setDeadline(new \DateTimeImmutable('@' . $updatedTask->getDeadline()))
            ->setStatus($updatedTask->getStatus());

        return $task;
    }

    public function mapToDTO(Task $task): TaskListItem
    {
        return (new TaskListItem())
            ->setId($task->getId())
            ->setTitle($task->getTitle())
            ->setSlug($task->getSlug())
            ->setDescription($task->getDescription())
            ->setDeadline($task->getDeadline()->getTimestamp())
            ->setStatus($task->getStatus())
            ->setProjectId($task->getProject()->getId());
    }
}

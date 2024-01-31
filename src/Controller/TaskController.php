<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\TaskCreateRequest;
use App\Model\TaskUpdateRequest;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(private TaskService $taskService)
    {
    }

    #[Route("/api/v1/task/all", name: "task_list", methods: "GET")]
    public function listTasks(): Response
    {
        $tasks = $this->taskService->getAllTasks();
        return $this->json($tasks, 200, [], ['groups' => 'task']);
    }

    #[Route("/api/v1/task/{id}", name: "task_show", methods: "GET")]
    public function showTask($id): Response
    {
        $task = $this->taskService->getTaskById($id);
        return $this->json($task, 200, [], ['groups' => 'task']);
    }


    #[Route("/api/v1/task/add", name: "task_add", methods: "POST")]
    public function createTask(#[RequestBody] TaskCreateRequest $taskCreateRequest): Response
    {
        $this->taskService->createTask($taskCreateRequest);
        return $this->json(null);
    }

    #[Route("/api/v1/task/update/{id}", name: "task_update", methods: "PATCH")]
    public function updateTask(#[RequestBody] TaskUpdateRequest $taskUpdateRequest, $id): Response
    {
        $this->taskService->updateTask($taskUpdateRequest, $id);
        return $this->json(null);
    }

    #[Route("/api/v1/task/delete/{id}", name: "task_delete", methods: "DELETE")]
    public function deleteTask($id)
    {
        $this->taskService->deleteTask($id);
        return $this->json(null);
    }
}

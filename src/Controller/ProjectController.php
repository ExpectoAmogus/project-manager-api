<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ProjectCreateRequest;
use App\Model\ProjectUpdateRequest;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(private ProjectService $projectService)
    {
    }

    #[Route("/api/v1/project/all", name: "project_list", methods: "GET")]
    public function listProjects(Request $request): Response
    {
        $criteria = $request->query->get('criteria', []);
        $orderBy = $request->query->get('orderBy', []);
        $limit = $request->query->getInt('limit', null);
        $offset = $request->query->getInt('offset', null);

        $projects = $this->projectService->getAllProjects($criteria, $orderBy, $limit, $offset);
        return $this->json($projects, 200, [], ['groups' => 'project']);
    }

    #[Route("/api/v1/project/{id}", name: "project_show", methods: "GET")]
    public function showProject($id): Response
    {
        $project = $this->projectService->getProjectById($id);
        return $this->json($project, 200, [], ['groups' => 'project']);
    }

    #[Route("/api/v1/project/add", name: "project_add", methods: "POST")]
    public function createProject(#[RequestBody] ProjectCreateRequest $projectRequest): Response
    {
        $this->projectService->createProject($projectRequest);
        return $this->json(null, 201);
    }

    #[Route("/api/v1/project/update/{id}", name: "project_update", methods: "PATCH")]
    public function updateProject(#[RequestBody] ProjectUpdateRequest $projectRequest, $id): Response
    {
        $this->projectService->updateProject($projectRequest, $id);
        return $this->json(null);
    }

    #[Route("/api/v1/project/delete/{id}", name: "project_delete", methods: "DELETE")]
    public function deleteProject($id): Response
    {
        $this->projectService->deleteProject($id);
        return $this->json(null);
    }
}

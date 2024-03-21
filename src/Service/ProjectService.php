<?php

namespace App\Service;

use App\Exception\EntityAlreadyExistsException;
use App\Exception\EntityNotFoundException;
use App\Mapper\ProjectMapper;
use App\Model\ProjectCreateRequest;
use App\Model\ProjectListItem;
use App\Model\ProjectListResponse;
use App\Model\ProjectUpdateRequest;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(private ProjectRepository $projectRepository,
                                private EntityManagerInterface $manager,
                                private ProjectMapper $projectMapper)
    {
    }

    public function getAllProjects($criteria, $orderBy, $limit, $offset): ProjectListResponse
    {
        $projects = $this->projectRepository->findFilteredAndSorted($criteria, $orderBy, $limit, $offset);
        $projectDTOs = array_map([$this->projectMapper, 'mapToDTO'], $projects);

        return new ProjectListResponse($projectDTOs);
    }

    public function getProjectById($projectId): ProjectListItem
    {
        if (!$this->projectRepository->existsById($projectId))
        {
            throw new EntityNotFoundException();
        }

        $project = $this->projectRepository->find($projectId);

        return $this->projectMapper->mapToDTO($project);
    }

    public function createProject(ProjectCreateRequest $projectCreateRequest): void
    {
        if (!$this->projectRepository->existsByTitle($projectCreateRequest->getTitle()))
        {
            throw new EntityAlreadyExistsException();
        }

        $project = $this->projectMapper->mapToEntity($projectCreateRequest);

        $this->manager->persist($project);
        $this->manager->flush();
    }

    public function updateProject(ProjectUpdateRequest $updatedProject, int $id): void
    {
        if (!$this->projectRepository->existsById($id))
        {
            throw new EntityNotFoundException();
        }
        $project = $this->projectRepository->find($id);

        $project = $this->projectMapper->mapToEntityFromUpdateRequest($updatedProject, $project);

        $this->manager->persist($project);
        $this->manager->flush();
    }

    public function deleteProject(int $id): void
    {
        if (!$this->projectRepository->existsById($id))
        {
            throw new EntityNotFoundException();
        }
        $project = $this->projectRepository->find($id);

        $this->manager->remove($project);
        $this->manager->flush();
    }
}

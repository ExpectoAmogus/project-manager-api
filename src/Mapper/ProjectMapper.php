<?php

namespace App\Mapper;

use App\Entity\Project;
use App\Enum\ProjectStatus;
use App\Model\ProjectCreateRequest;
use App\Model\ProjectListItem;
use App\Model\ProjectUpdateRequest;

class ProjectMapper
{
    public function mapToEntity(ProjectCreateRequest $projectRequest): Project
    {
        $project = new Project();
        $project
            ->setTitle($projectRequest->getTitle())
            ->setDescription($projectRequest->getDescription())
            ->setDeadline(new \DateTimeImmutable())
            ->setStatus(ProjectStatus::TODO);


        return $project;
    }

    public function mapToEntityFromUpdateRequest(ProjectUpdateRequest $updatedProject, Project $project): Project
    {
        $project
            ->setTitle($updatedProject->getTitle())
            ->setDescription($updatedProject->getDescription())
            ->setDeadline(new \DateTimeImmutable('@' . $updatedProject->getDeadline()))
            ->setStatus($updatedProject->getStatus());

        return $project;
    }

    public function mapToDTO(Project $project): ProjectListItem
    {
        return (new ProjectListItem())
            ->setId($project->getId())
            ->setTitle($project->getTitle())
            ->setSlug($project->getSlug())
            ->setDescription($project->getDescription())
            ->setDeadline($project->getDeadline()->getTimestamp())
            ->setStatus($project->getStatus());
    }
}

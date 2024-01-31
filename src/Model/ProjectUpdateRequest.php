<?php

namespace App\Model;

class ProjectUpdateRequest
{
    private string $title;
    private string $description;
    private int $deadline;
    private string $status;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDeadline(): int
    {
        return $this->deadline;
    }

    public function setDeadline(int $deadline): self
    {
        $this->deadline = $deadline;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

}
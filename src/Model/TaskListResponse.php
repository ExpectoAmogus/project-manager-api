<?php

namespace App\Model;

class TaskListResponse
{
    /**
     * @var TaskListItem[]
     */
    private array $items;

    /**
     * @param TaskListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return TaskListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
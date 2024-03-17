<?php

namespace App\Enum;

class TaskStatus
{
    public const TODO = 'todo';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';

    public static function isValid(string $status)
    {
        if ($status == 'todo' || $status == 'in_progress' || $status == 'completed'){
            return true;
        }
        return false;
    }
}

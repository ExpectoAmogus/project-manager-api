<?php

namespace App\Exception;

class EntityAlreadyExistsException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Entity already exists');
    }
}

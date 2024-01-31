<?php

namespace App\Exception;

class EntityNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Entity not found');
    }
}

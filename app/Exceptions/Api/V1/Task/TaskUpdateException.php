<?php

namespace App\Exceptions\Api\V1\Task;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TaskUpdateException extends Exception
{
    protected $code = Response::HTTP_METHOD_NOT_ALLOWED;

    protected $message = 'Cannot mark as "DONE" while subtasks have "TODO" status.';
}

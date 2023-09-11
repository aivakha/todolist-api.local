<?php

namespace App\Exceptions\Api\V1\Task;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TaskDeleteException extends Exception
{
    protected $code = Response::HTTP_METHOD_NOT_ALLOWED;

    protected $message = 'Cannot delete task with status "DONE".';
}

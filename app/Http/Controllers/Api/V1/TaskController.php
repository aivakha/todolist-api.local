<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\TaskFilter as Filter;
use App\Http\Requests\Api\V1\Task\TaskFilter;
use App\Http\Requests\Api\V1\Task\TaskStore;
use App\Http\Requests\Api\V1\Task\TaskUpdate;
use App\Http\Resources\V1\Task\TaskCollection;
use App\Http\Resources\V1\Task\TaskResource;
use App\Models\Task;
use App\Services\Api\V1\TaskService;

class TaskController extends Controller
{

    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(TaskFilter $request)
    {
        $data = $request->validated();

        $filter = app()->make(Filter::class, ['queryParams' => array_filter($data)]);

        $tasks = Task::filter($filter)->where('user_id', auth()->user()->id)->get();

        return new TaskCollection($tasks);
    }

    public function store(TaskStore $request)
    {
        $data = $request->validated();

        $task = $this->taskService->store($data);

        return new TaskResource($task);
    }

    public function update(TaskUpdate $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validated();

        $task = $this->taskService->update($task, $data);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }
}

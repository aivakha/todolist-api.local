<?php

namespace App\Http\Filters;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;

class TaskFilter extends AbstractFilter
{
    const SEARCH = 'search';
    const STATUS = 'status';
    const PRIORITY_FROM = 'priorityFrom';
    const PRIORITY_TO = 'priorityTo';
    const SORT_BY = 'sortBy';
    const ORDER_BY = 'orderBy';
    const PER_PAGE = 'perPage';
    const WITH_SUBTASKS = 'withSubtasks';

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],
            self::STATUS => [$this, 'status'],
            self::PRIORITY_FROM => [$this, 'priorityFrom'],
            self::PRIORITY_TO => [$this, 'priorityTo'],
            self::SORT_BY => [$this, 'sortBy'],
            self::PER_PAGE => [$this, 'perPage'],
            self::WITH_SUBTASKS => [$this, 'withSubtasks']
        ];
    }

    protected function perPage(Builder $builder, $value)
    {
        if ($value) {
            $builder->paginate($value);
        }
    }

    protected function withSubtasks(Builder $builder, $value)
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN) === true) {
            $builder->with('subtasks');
        }
    }

    protected function status(Builder $builder, $value)
    {
        $status = match (strtolower($value)) {
            'todo' => TaskStatus::TODO,
            'done' => TaskStatus::DONE,
            default => null,
        };

        if ($status !== null) {
            $builder->where('status', $status);
        }
    }

    protected function priorityFrom(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('priority', '>=', $value);
        }
    }

    protected function priorityTo(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('priority', '<=', $value);
        }
    }

    protected function search(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('title', 'LIKE', '%' . $value . '%');
        }
    }

    protected function sortBy(Builder $builder, $value)
    {
        match ($value) {
            'completedAt' => $this->sortByCompletedAt($builder, request()->input(self::ORDER_BY, 'asc')),
            'priority' => $this->sortByPriority($builder, request()->input(self::ORDER_BY, 'asc')),
            'createdAt' => $this->sortByCreatedAt($builder, request()->input(self::ORDER_BY, 'asc'))
        };
    }

    private function sortByCompletedAt(Builder $builder, $value)
    {
        $builder->orderBy('completed_at', $value);
    }

    private function sortByPriority(Builder $builder, $value)
    {
        $builder->orderBy('priority', $value);
    }

    private function sortByCreatedAt(Builder $builder, $value)
    {
        $builder->orderBy('created_at', $value);
    }
}

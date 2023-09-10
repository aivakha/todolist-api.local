<?php

namespace App\Http\Requests\Api\V1\Task;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskFilter extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'priorityFrom' => ['nullable', 'integer', 'min:1', 'max:5'],
            'priorityTo' => ['nullable', 'integer', 'min:1', 'max:5'],
            'status' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
            'sortBy' => ['nullable', 'string', 'in:priority,completedAt,createdAt'],
            'orderBy' => ['nullable', 'string', 'in:asc,desc'],
            'perPage' => ['nullable', 'integer'],
            'withSubtasks' => ['nullable'],
        ];
    }
}

<?php

namespace App\Http\Requests\Api\V1\Task;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Rules\SubtasksStatusRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TaskUpdate extends FormRequest
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
        $taskId = $this->route('task')->id;
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => [
                'nullable',
                new Enum(TaskStatus::class),
            ],
            'priority' => ['required', 'integer', 'min:1', 'max:5'],
            'parent_id' => ['nullable', 'exists:tasks,id',
                Rule::exists('tasks', 'id')->where(function ($query) use ($taskId) {
                    $query->where('id', '!=', $taskId);
                })],
            'user_id' => ['nullable', 'exists:users,id'],
            'completed_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'completed_at' => ((int) $this->input('status') === TaskStatus::DONE->value) ? now()->toDateTimeString() : null,
            'parent_id' => $this->parentId
        ]);
    }
}

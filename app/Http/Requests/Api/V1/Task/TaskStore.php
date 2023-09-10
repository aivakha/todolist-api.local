<?php

namespace App\Http\Requests\Api\V1\Task;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskStore extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['nullable', new Enum(TaskStatus::class)],
            'priority' => ['required', 'integer', 'min:1', 'max:5'],
            'parent_id' => ['nullable', 'exists:tasks,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'completed_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ((int) $this->input('status') === TaskStatus::DONE->value) {
            $this->merge(['completed_at' => now()->toDateTimeString()]);
        }

        $this->merge([
            'parent_id' => $this->parentId,
            'user_id' => auth()->user()->id
        ]);
    }
}

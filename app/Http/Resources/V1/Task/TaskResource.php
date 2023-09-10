<?php

namespace App\Http\Resources\V1\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'parentId' => $this->parent_id,
            'userId' => $this->user_id,
            'completedAt' => $this->completed_at,
            'createdAt' => $this->created_at,
            'subtasks' => TaskResource::collection($this->whenLoaded('subtasks'))
        ];
    }
}

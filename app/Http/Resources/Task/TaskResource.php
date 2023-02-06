<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\Label\LabelCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'due_date' => $this->due_date,
            'order' => $this->order,
            'current_status' => $this->current_status,
            'labels' => new \App\Http\Resources\Task\LabelCollection($this->labels),
            'assign' => new UserTaskResource($this->user),
            'activity_status' => new TaskStatusCollection($this->task_status),
        ];
    }
}

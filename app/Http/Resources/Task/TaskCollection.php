<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [

                    'id' => $data->id,
                    'title' => $data->title,
                    'description' => $data->description,
                    'image' => $data->image,
                    'due_date' => $data->due_date,
                    'order' => $data->order,
                    'current_status' => $data->current_status,
                    'labels' => new \App\Http\Resources\Task\LabelCollection($data->labels),
                    'assign' => ($data?->user) ? new UserTaskResource($data?->user) : [],
                    'activity_status' => new TaskStatusCollection($data->task_status),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}

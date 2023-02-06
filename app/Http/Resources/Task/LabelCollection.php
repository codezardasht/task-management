<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LabelCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($data) {
            return [
                'id' => $data?->label?->id,
                'name' => $data?->label?->name,
            ];
        });
    }
}

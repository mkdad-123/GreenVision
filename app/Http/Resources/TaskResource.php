<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'farm_id'         => $this->farm_id,
            'farm'            => new FarmResource($this->whenLoaded('farm')), // إن كنت تستعمله
            'type'            => $this->type,
            'description'     => $this->description,
            'date'            => optional($this->date)->format('Y-m-d'),
            'repeat_interval' => $this->repeat_interval,
            'status'          => $this->status,
            'priority'        => $this->priority,

            // مهم: ISO-8601 لسهولة تفسيرها في JS
            'created_at'      => optional($this->created_at)->toIso8601String(),
            'updated_at'      => optional($this->updated_at)->toIso8601String(),
        ];
    }
}

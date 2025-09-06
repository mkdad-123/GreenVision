<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'location'       => $this->location,
            'crop_type'      => $this->crop_type,
            'soil_type'      => $this->soil_type,
            'area'           => $this->area,
            'notes'          => $this->notes,
            'irrigation_type'=> $this->irrigation_type,
            'status'         => $this->status,
            'created_at'     => $this->created_at->format('Y-m-d H:i'),
            // 'owner'          => new UserResource($this->whenLoaded('user')), // في حال أردت إظهار معلومات المستخدم
        ];
    }
}

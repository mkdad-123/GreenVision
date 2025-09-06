<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'serial_number'     => $this->serial_number,
            'purchase_date'     => $this->purchase_date,
            'last_maintenance'  => $this->last_maintenance,
            'next_maintenance'  => $this->next_maintenance,
            'status'            => $this->status,
            'type'              => $this->type,
            'location'          => $this->location,
            'usage_hours'       => $this->usage_hours,
            'notes'             => $this->notes,

        ];
    }
}

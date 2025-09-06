<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * تحويل البيانات إلى مصفوفة JSON.
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'type'             => $this->type,
            'quantity'         => $this->quantity,
            'unit'             => $this->unit,
            'purchase_date'    => $this->purchase_date,
            'expiry_date'      => $this->expiry_date,
            'min_threshold'    => $this->min_threshold,
            'supplier'         => $this->supplier,
            'storage_location' => $this->storage_location,
            'notes'            => $this->notes,
        ];
    }
}

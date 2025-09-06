<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CropSaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'farm_id'           => $this->farm_id,

            'crop_name'         => $this->crop_name,
            'quantity'          => (float) $this->quantity,
            'unit'              => $this->unit,

            'price_per_unit'    => (float) $this->price_per_unit,
            'total_price'       => (float) $this->total_price,

            'sale_date'         => $this->sale_date?->toDateString(),
            'status'            => $this->status,
            'buyer_name'        => $this->buyer_name,
            'delivery_location' => $this->delivery_location,
            'notes'             => $this->notes,


        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'direction'        => $this->direction,        // دخل | نفقات
            'category'         => $this->category,         // من القيم المعرفة في الميغريشن
            'amount'           => (float) $this->amount,
            'date'             => $this->date?->format('Y-m-d'),
            'description'      => $this->description,
            'reference_number' => $this->reference_number,
        ];
    }
}

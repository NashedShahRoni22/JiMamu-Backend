<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyOrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_unique_id,
            'pickup_latitude' => $this->pickup_latitude,
            'pickup_longitude' => $this->pickup_longitude,
            'drop_latitude' => $this->drop_latitude,
            'drop_longitude' => $this->drop_longitude,
            'date' => $this->created_at->format('d-m-Y'),
        ];
    }
}

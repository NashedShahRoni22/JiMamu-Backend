<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplyBidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rider_id' => $this->user?->id,
            'name' => $this->user?->name,
            'phone_number' => $this->user?->phone_number,
            'profile_image' => $this->user?->profile_image,
            'bid_amount' => $this->bid_amount,
        ];
    }
}

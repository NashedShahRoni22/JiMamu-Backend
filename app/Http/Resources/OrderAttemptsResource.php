<?php

namespace App\Http\Resources;

use App\Models\OrderAttempt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderAttemptsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => OrderAttempt::$ORDER_STATUS_NAME[$this->status],
            'order_tracking_number' => $this->order_tracking_number,
            'payment_status' => $this->payment_status,
            'fare' => $this->fare,
            'order_date' => $this->created_at->format('F j, Y, h:i:s A'),
            'rider_bids' => ApplyBidResource::collection($this->bids)
        ];
    }
}

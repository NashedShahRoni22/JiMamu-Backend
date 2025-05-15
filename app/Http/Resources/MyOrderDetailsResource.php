<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyOrderDetailsResource extends JsonResource
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
            'order_type' => $this->order_type == 1? 'national': 'international',
            'pickup_latitude' => $this->pickup_latitude,
            'pickup_longitude' => $this->pickup_longitude,
            'drop_latitude' => $this->drop_latitude,
            'drop_longitude' => $this->drop_longitude,
            'status' => Order::$ORDER_STATUS_NAME[$this->status],
            'date' => $this->created_at->format('d-m-Y  h:i:s A'),
            'order_attempts' => OrderAttemptsResource::collection($this->orderAttempts),
            'receiver_information' => $this->when(
                $this->receiverInformation,
                new ReceiverInformationResource($this->receiverInformation)
            ),
            'sender_information' => $this->when(
                $this->senderInformation,
                new SenderInformationResource($this->senderInformation)
            ),
            'order_destination' => new OrderDestinationResource($this?->orderDestination)


        ];
    }
}

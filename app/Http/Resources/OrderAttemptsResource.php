<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\OrderAttempt;
use App\Models\PricingRate;
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
        $order = Order::find($this->order_id);
        $pricingRate = PricingRate::where('type', $order->order_type)->first();
        // cutting system base fare and platform change, rider will show only need fare
        $netFare = abs((float)($pricingRate->base_fare + $pricingRate->platform_charge) - (float)$this->fare );

        return [
            'status' => OrderAttempt::$ORDER_STATUS_NAME[$this->status],
            'order_tracking_number' => $this->order_tracking_number,
            'payment_status' => $this->payment_status,
            'fare' => $order->customer_id == auth()->id() ? $this->fare : $netFare,
            'parcel_estimate_price' => $this->parcel_estimate_price,
            'order_date' => $this->created_at->format('F j, Y, h:i:s A'),
            'rider_bids' => ApplyBidResource::collection($this->bids)
        ];
    }
}

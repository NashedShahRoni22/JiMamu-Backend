<?php

namespace App\Http\Resources\Wallet;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this?->balance,
            'walletHistory' => WalletHistoryResource::collection($this->whenLoaded('walletHistory')),
        ];
    }
}

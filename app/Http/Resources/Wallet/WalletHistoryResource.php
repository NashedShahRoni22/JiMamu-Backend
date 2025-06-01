<?php

namespace App\Http\Resources\Wallet;

use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'purpose_of_transaction' => WalletHistory::$PURPOSE_OF_TRANSACTION_NAME[$this->purpose_of_transaction],
            'transaction_type' => WalletHistory::$TRANSACTION_TYPE_NAME[$this->transaction_type],
            'status' => WalletHistory::$STATUS_NAME[$this->status],
            'transaction_date' => $this->created_at->format('d-m-Y h:i A'),
        ];
    }
}

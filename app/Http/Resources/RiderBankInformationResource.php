<?php

namespace App\Http\Resources;

use App\Models\RiderBankInformation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderBankInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'account_number' => $this->account_number,
            'cvc_code' => $this->cvc_code,
            'expiry_date' => $this->expiry_date,
            'is_default_payment' => $this->is_default_payment,
            'review_status' => RiderBankInformation::$REVIEW_STATUS[$this->review_status]

        ];
    }
}

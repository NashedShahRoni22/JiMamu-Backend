<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => array_search($this->status, User::$status) ?? 'unknown', // Convert integer to string
            'role' => $this->getRoleNames()->toArray(),
            'rider_document' => RiderDocumentResource::collection($this->userRiders),
            'rider_bank_information' => RiderBankInformationResource::collection($this->riderBankInformations),
        ];
    }
}

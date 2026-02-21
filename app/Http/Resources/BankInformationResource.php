<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'account_holer_name' => $this?->name,
            'account_number' => $this?->account_number,
            'institution_number' => $this?->institution_number,
            'transit_number' => $this?->transit_number,
            'bank_document' => $this?->bank_document,
            'status' => $this?->status,

        ];
    }
}

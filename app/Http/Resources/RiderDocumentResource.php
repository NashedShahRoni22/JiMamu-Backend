<?php

namespace App\Http\Resources;

use App\Models\UserRider;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'document' => $this->document,
            'review_status' => UserRider::$REVIEW_STATUS[$this->review_status]
        ];
    }
}

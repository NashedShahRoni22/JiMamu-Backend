<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this?->phone_number,
            'profile_image' => $this?->profile_image,
            'dod' => $this->dod,
            'status' => array_search($this->status, User::$status) ?? 'unknown', // Convert integer to string
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'date_of_birth' => $this->date_of_birth,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'bank_account_number' => $this->bank_account_number,
        ];
    }
}

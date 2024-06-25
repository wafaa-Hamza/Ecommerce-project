<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => $this->status,
            'user_id' => $this->user_id,
            'total' => $this->total,
            'address' => $this->address,
            'address_2' => $this->when($this->address_2 , $this->address_2),
            'city' => $this->city,
            "shipping" => $this->shipping,
            'products_count' => $this->products_count,
            'products' => ProductResource::collection($this->products),
            'pay_on_delivery' => $this->pay_on_delivery
        ];
    }
}

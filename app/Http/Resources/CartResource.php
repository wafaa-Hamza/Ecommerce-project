<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'price' => $this->price,
            'priceAfter' => $this->priceAfter,
            'productQuantity' => $this->productQuantity,
            'cartQuantity' => $this->cartQuantity,
            'image' => $this->when($this->getFirstMediaUrl("main") != "", MediaResource::make($this->getMedia("main")->first())),
            'total_discount' => $this->total_discount,
            'total_price' => $this->total_price,          
        ];
    }
}

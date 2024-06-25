<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'quantity' => $this->quantity,
            'image' => $this->when($this->getFirstMediaUrl("main") != "", MediaResource::make($this->getMedia("main")->first())),
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'rate' => $this->averageRating(),
            'live' => $this->live,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'expires_at' => $this->expires_at,
            'additional_images' => $this->when(($request->isMethod("POST") || $request->is('api/product/*')) && $this->getMedia("additional_images") != "", MediaResource::collection($this->getMedia("additional_images"))),
            'sizes' => $this->when($this->sizes , $this->sizes),
            'colors' => $this->when($this->colors , $this->colors),
            'randomImages' => $this->when($request->is('api/product/*') && !$request->is('api/product/index_admin') && $request->isMethod("GET"), function () {
                return $this->whenLoaded('randomImages' , RatingUserResource::collection($this->randomImages));
            }),
            'ratings' => $this->when($request->is('api/product/*') && !$request->is('api/product/index_admin') && $request->isMethod("GET"), function () {
                return $this->whenLoaded('ratings' , RatingResource::collection($this->ratings))->response()->getData();
            }),

        ];
    }
}

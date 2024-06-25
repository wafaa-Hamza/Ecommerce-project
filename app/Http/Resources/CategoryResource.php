<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent_id' => $this->when(isset($this->parent_id), $this->parent_id),
            'image' => $this->when($this->getFirstMediaUrl("main") != "", MediaResource::make($this->getMedia("main")->first())),
            'children' => $this->when(($request->is('api/category') && $request->isMethod("GET") && !isset($this->parent_id)), function () { 
                return $this->whenLoaded('children' , CategoryResource::collection($this->children));
            }),
            'products' => $this->when($request->is('api/category/*') && !$request->is('api/category/show-sub-category') && $request->isMethod("GET"), function () { 
                return $this->when(isset($this->products) , ProductResource::collection($this->products))->response()->getData();
            }),
        ];
    }
}

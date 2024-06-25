<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'address' => $this->address,
            'nick' => $this->nick,
            'image' => $this->when($this->getFirstMediaUrl("main") != "", MediaResource::make($this->getMedia("main")->first())),
            'active_status' => $this->when($this->active_status, $this->active_status),
            'unread' =>$this->unread,
            'last_seen' => $this->when($this->active_status == 1, $this->last_seen),
            'max_created_at' => $this->when($this->max_created_at, $this->max_created_at),
            'lastMessage' => $this->when($this->lastMessage, $this->lastMessage),
            'orders_count' => $this->when($this->orders_count, $this->orders_count),
            'roles' => $this->getRoleNames(),
            'token' => $this->when($this->token, $this->token),
            'created_at' => $this->created_at
        ];
    }
}

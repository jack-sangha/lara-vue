<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // whenLoaded() omits relations that weren't eager loaded, so the resource
        // can never lazy load (and cause N+1) by accident.
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'created_at' => $this->created_at,
            'creator' => $this->whenLoaded('creator', fn() => $this->creator->only('id', 'name')),
            'assignee' => $this->whenLoaded('assignee', fn() => $this->assignee?->only('id', 'name')),
            'categories' => $this->whenLoaded('categories', fn() => $this->categories->map->only('id', 'name')),
        ];
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Models\Room;

/**
 * @mixin Room
 */
class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(?Request $request = null): array
    {
        return [
            'roomId' => $this->getKey(),
            'creator' => $this->creator->toResource(UserResource::class),
            'roomName' => $this->name,
            'users' => $this->users->toResourceCollection(UserResource::class),
            'tags' => $this->tags,
            'index' => $this->updated_at,
            'lastMessage' => new MessageResource($this->whenNotNull($this->lastMessage)),
        ];
    }
}

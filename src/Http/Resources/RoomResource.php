<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\SupportChat;

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
        $resourceClass = SupportChat::userResource();

        return [
            'roomId' => $this->getKey(),
            'creator' => $this->creator->toResource($resourceClass),
            'roomName' => $this->name,
            'users' => $this->users->toResourceCollection($resourceClass),
            'tags' => $this->tags->toResourceCollection(),
            'index' => $this->updated_at,
            'lastMessage' => new MessageResource($this->whenNotNull($this->lastMessage)),
        ];
    }
}

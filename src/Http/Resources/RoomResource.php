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
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'roomId' => (string) $this->getKey(),
            'roomName' => $this->name,
            'users' => UserResource::collection($this->users),
        ];
    }
}

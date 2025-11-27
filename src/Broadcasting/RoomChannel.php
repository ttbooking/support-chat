<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Broadcasting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\SupportChat;

class RoomChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(Model $user, Room $room): JsonResource|false
    {
        if ($room->users()->whereKey($user->getKey())->exists()) {
            return $user->toResource(SupportChat::userResource());
        }

        return false;
    }
}

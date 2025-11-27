<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Broadcasting;

use Illuminate\Database\Eloquent\Model;
use TTBooking\SupportChat\Http\Resources\UserResource;
use TTBooking\SupportChat\Models\Room;

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
    public function join(Model $user, Room $room): UserResource|false
    {
        if ($room->users()->whereKey($user->getKey())->exists()) {
            /** @var UserResource */
            return $user->toResource(UserResource::class);
        }

        return false;
    }
}

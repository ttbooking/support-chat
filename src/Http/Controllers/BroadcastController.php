<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use TTBooking\SupportChat\Http\Resources\UserResource;
use TTBooking\SupportChat\Models\Room;

class BroadcastController
{
    public function userPresentInRoom(Model $user, Room $room): UserResource|false
    {
        if ($room->users()->whereKey($user->getKey())->exists()) {
            return new UserResource($user);
        }

        return false;
    }
}

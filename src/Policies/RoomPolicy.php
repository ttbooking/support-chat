<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use TTBooking\SupportChat\Models\Room;

class RoomPolicy
{
    /**
     * Determine whether the user can view any rooms.
     */
    public function viewAny(Authenticatable&Model $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the room.
     */
    public function view(Authenticatable&Model $user, Room $room): Response
    {
        if ($user instanceof Authorizable && $user->can('viewForeignRooms')) {
            return Response::allow();
        }

        return $room->users()->whereKey($user->getAuthIdentifier())->exists()
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create rooms.
     */
    public function create(Authenticatable&Model $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the room.
     */
    public function update(Authenticatable&Model $user, Room $room): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the room.
     */
    public function delete(Authenticatable&Model $user, Room $room): bool
    {
        return true;
    }
}

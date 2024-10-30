<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

class MessagePolicy
{
    /**
     * Determine whether the user can view any messages.
     */
    public function viewAny(Authenticatable&Model $user, Room $room): Response
    {
        return $room->users()->whereKey($user->getAuthIdentifier())->exists()
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view the message.
     */
    public function view(Authenticatable&Model $user, Message $message): bool
    {
        return $message->room()->whereHas('users', function (Builder $query) use ($user) {
            $query->whereKey($user->getAuthIdentifier());
        })->exists();
    }

    /**
     * Determine whether the user can create messages.
     */
    public function create(Authenticatable&Model $user, Room $room): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the message.
     */
    public function update(Authenticatable&Model $user, Message $message): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the message.
     */
    public function delete(Authenticatable&Model $user, Message $message): bool
    {
        return true;
    }
}

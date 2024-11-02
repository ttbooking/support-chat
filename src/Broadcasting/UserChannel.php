<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Broadcasting;

use Illuminate\Database\Eloquent\Model;

class UserChannel
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
    public function join(Model $user, int|string $id): bool
    {
        return $user->getKey() === $id;
    }
}

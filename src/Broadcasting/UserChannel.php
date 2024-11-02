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
    public function join(Model $user, string $id): bool
    {
        return (string) $user->getKey() === $id;
    }
}

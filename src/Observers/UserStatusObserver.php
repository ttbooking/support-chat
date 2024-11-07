<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Observers;

use TTBooking\SupportChat\Events\User\Invited;
use TTBooking\SupportChat\Events\User\Kicked;
use TTBooking\SupportChat\Models\UserStatus;

class UserStatusObserver
{
    /**
     * Handle the UserStatus "created" event.
     */
    public function created(UserStatus $userStatus): void
    {
        broadcast(new Invited($userStatus->user, $userStatus->room))->toOthers();
    }

    /**
     * Handle the UserStatus "updated" event.
     */
    public function updated(UserStatus $userStatus): void
    {
        //
    }

    /**
     * Handle the UserStatus "deleted" event.
     */
    public function deleted(UserStatus $userStatus): void
    {
        broadcast(new Kicked($userStatus->user, $userStatus->room))->toOthers();
    }

    /**
     * Handle the UserStatus "restored" event.
     */
    public function restored(UserStatus $userStatus): void
    {
        //
    }

    /**
     * Handle the UserStatus "force deleted" event.
     */
    public function forceDeleted(UserStatus $userStatus): void
    {
        //
    }
}

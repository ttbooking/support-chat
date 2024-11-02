<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Observers;

use TTBooking\SupportChat\Models\RoomUser;

class RoomUserObserver
{
    /**
     * Handle the RoomUser "created" event.
     */
    public function created(RoomUser $roomUser): void
    {
        //
    }

    /**
     * Handle the RoomUser "updated" event.
     */
    public function updated(RoomUser $roomUser): void
    {
        //
    }

    /**
     * Handle the RoomUser "deleted" event.
     */
    public function deleted(RoomUser $roomUser): void
    {
        //
    }

    /**
     * Handle the RoomUser "restored" event.
     */
    public function restored(RoomUser $roomUser): void
    {
        //
    }

    /**
     * Handle the RoomUser "force deleted" event.
     */
    public function forceDeleted(RoomUser $roomUser): void
    {
        //
    }
}

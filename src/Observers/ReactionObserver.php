<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Observers;

use TTBooking\SupportChat\Events\Reaction\Left;
use TTBooking\SupportChat\Events\Reaction\Removed;
use TTBooking\SupportChat\Models\Reaction;

class ReactionObserver
{
    /**
     * Handle the Reaction "created" event.
     */
    public function created(Reaction $reaction): void
    {
        broadcast(new Left($reaction))->toOthers();
    }

    /**
     * Handle the Reaction "deleted" event.
     */
    public function deleted(Reaction $reaction): void
    {
        broadcast(new Removed($reaction))->toOthers();
    }
}

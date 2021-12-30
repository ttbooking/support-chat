<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Observers;

use TTBooking\SupportChat\Events\MessageReaction\Left;
use TTBooking\SupportChat\Events\MessageReaction\Removed;
use TTBooking\SupportChat\Models\MessageReaction;

class MessageReactionObserver
{
    /**
     * Handle the Message's reaction "created" event.
     *
     * @param  MessageReaction  $reaction
     * @return void
     */
    public function created(MessageReaction $reaction): void
    {
        broadcast(new Left($reaction))->toOthers();
    }

    /**
     * Handle the Message's reaction "deleted" event.
     *
     * @param  MessageReaction  $reaction
     * @return void
     */
    public function deleted(MessageReaction $reaction): void
    {
        broadcast(new Removed($reaction))->toOthers();
    }
}

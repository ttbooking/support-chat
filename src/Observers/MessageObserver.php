<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Observers;

use TTBooking\SupportChat\Events\Message\Deleted;
use TTBooking\SupportChat\Events\Message\Edited;
use TTBooking\SupportChat\Events\Message\Posted;
use TTBooking\SupportChat\Models\Message;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function created(Message $message): void
    {
        broadcast(new Posted($message))->toOthers();
    }

    /**
     * Handle the Message "updated" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function updated(Message $message): void
    {
        broadcast(new Edited($message))->toOthers();
    }

    /**
     * Handle the Message "deleted" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function deleted(Message $message): void
    {
        broadcast(new Deleted($message))->toOthers();
    }

    /**
     * Handle the Message "restored" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}

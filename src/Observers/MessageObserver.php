<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Observers;

use Exception;
use TTBooking\SupportChat\Events\Message\Deleted;
use TTBooking\SupportChat\Events\Message\Edited;
use TTBooking\SupportChat\Events\Message\Event;
use TTBooking\SupportChat\Events\Message\Posted;
use TTBooking\SupportChat\Models\Message;

class MessageObserver
{
    protected static function event(Event $event): void
    {
        try {
            event($event->dontBroadcastToCurrentUser());
            Message::withoutEvents(function () use ($event) {
                $event->message->state = Message::STATE_DISTRIBUTED;
                $event->message->save();
            });
        } catch (Exception $e) {
            report($e);
        }
    }

    /**
     * Handle the Message "created" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function created(Message $message): void
    {
        static::event(new Posted($message));
    }

    /**
     * Handle the Message "updated" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function updated(Message $message): void
    {
        static::event(new Edited($message));
    }

    /**
     * Handle the Message "deleted" event.
     *
     * @param  Message  $message
     * @return void
     */
    public function deleted(Message $message): void
    {
        static::event(new Deleted($message));
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

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Observers;

use Exception;
use TTBooking\SupportChat\Enums\MessageState;
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
            broadcast($event)->toOthers();
            Message::withoutEvents(static function () use ($event) {
                $event->message->state = MessageState::Distributed;
                $event->message->save();
            });
        } catch (Exception $e) {
            report($e);
        }
    }

    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        static::event(new Posted($message));
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        static::event(new Edited($message));
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        static::event(new Deleted($message));
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}

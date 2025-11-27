<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Models\Message;

abstract class Event implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public bool $afterCommit = true;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message) {}

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('support-chat.room.'.$this->message->room_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return $this->message->toResource()->resolve();
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return list<string>
     */
    public function tags(): array
    {
        return ['support-chat', 'room:'.$this->message->room_id, 'message:'.$this->message->getKey()];
    }
}

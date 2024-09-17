<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\MessageReaction;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Models\MessageReaction;

abstract class Event implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     */
    public function __construct(public MessageReaction $reaction) {}

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'reaction.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('support-chat.room.'.$this->reaction->message->room_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'messageId' => $this->reaction->message_id,
            'userId' => $this->reaction->user_id,
            'emoji' => $this->reaction->emoji,
        ];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return list<string>
     */
    public function tags(): array
    {
        return ['support-chat', 'room:'.$this->reaction->message->room_id, 'message:'.$this->reaction->message->getKey()];
    }
}

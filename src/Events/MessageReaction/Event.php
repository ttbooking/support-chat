<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\MessageReaction;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Models\MessageReaction;

abstract class Event implements ShouldBroadcast
{
    use InteractsWithSockets;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     *
     * @param  MessageReaction  $reaction
     * @return void
     */
    public function __construct(public MessageReaction $reaction)
    {
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'message-reaction.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PresenceChannel
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('support-chat.room.'.$this->reaction->message->room_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'emoji' => $this->reaction->emoji,
            'message_id' => $this->reaction->message_id,
            'user_id' => $this->reaction->user_id,
        ];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags(): array
    {
        return ['support-chat', 'room:'.$this->reaction->message->room_id, 'message:'.$this->reaction->message->getKey()];
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\Attachment;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Models\Attachment;

class Uploaded implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     */
    public function __construct(public Attachment $attachment) {}

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'attachment.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('support-chat.room.'.$this->attachment->message->room_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'messageId' => $this->attachment->message_id,
            'filename' => $this->attachment->name,
        ];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return list<string>
     */
    public function tags(): array
    {
        return ['support-chat', 'room:'.$this->attachment->message->room_id, 'message:'.$this->attachment->message->getKey()];
    }
}

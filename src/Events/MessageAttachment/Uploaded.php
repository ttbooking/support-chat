<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\MessageAttachment;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Models\MessageFile;

class Uploaded implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     *
     * @param  MessageFile  $attachment
     * @return void
     */
    public function __construct(public MessageFile $attachment)
    {
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'attachment.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PresenceChannel
     */
    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('support-chat.room.'.$this->attachment->message->room_id);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'messageIndexId' => $this->attachment->message_id,
            'filename' => $this->attachment->name,
        ];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags(): array
    {
        return ['support-chat', 'room:'.$this->attachment->message->room_id, 'message:'.$this->attachment->message->getKey()];
    }
}

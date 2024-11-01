<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\Room;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Http\Resources\RoomResource;
use TTBooking\SupportChat\Models\Room;

abstract class Event implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public bool $afterCommit = true;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     */
    public function __construct(public Room $room) {}

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'room.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('support-chat');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return (new RoomResource($this->room))->resolve();
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return list<string>
     */
    public function tags(): array
    {
        return ['support-chat', 'room:'.$this->room->getKey()];
    }
}

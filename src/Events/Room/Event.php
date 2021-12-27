<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\Room;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Http\Resources\RoomResource;
use TTBooking\SupportChat\Models\Room;

abstract class Event implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     *
     * @param  Room  $room
     * @return void
     */
    public function __construct(public Room $room)
    {
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'room.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('support-chat');
    }

    /**
     * Get the data to broadcast.
     *
     * @return RoomResource
     */
    public function broadcastWith(): RoomResource
    {
        return new RoomResource($this->room);
    }
}
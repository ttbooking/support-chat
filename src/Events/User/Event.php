<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\User;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Http\Resources\RoomResource;
use TTBooking\SupportChat\Models\Room;

abstract class Event implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public bool $afterCommit = true;

    protected ?string $broadcastAs = null;

    /**
     * Create a new event instance.
     */
    public function __construct(public Model $user, public Room $room) {}

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.'.($this->broadcastAs ?? Str::kebab(class_basename(static::class)));
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('support-chat.user.'.$this->user->getKey());
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
        return ['support-chat', 'user:'.$this->user->getKey(), 'room:'.$this->room->getKey()];
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use TTBooking\SupportChat\Exceptions\RoomNotFoundException;

/**
 * @implements Contracts\Chat<Room>
 */
class Chat implements Contracts\Chat
{
    public function __construct(protected Authenticatable $user) {}

    public function as(Authenticatable $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function user(): Authenticatable
    {
        return $this->user;
    }

    public function createRoom(?string $name = null, array $tags = []): Room
    {
        $room = Models\Room::query()->create([
            'name' => $name,
            'created_by' => $this->user->getAuthIdentifier(),
        ]);
        $room->users()->syncWithoutDetaching([$this->user->getAuthIdentifier()]);

        return (new Room($this, $room))->when($tags)->tag(...$tags);
    }

    public function room(string $id): Room
    {
        try {
            return new Room($this, Models\Room::query()->findOrFail($id));
        } catch (ModelNotFoundException $e) {
            throw new RoomNotFoundException("Room [$id] not found.", 0, $e);
        }
    }

    /**
     * @return Collection<int, Room>
     */
    public function rooms(): Collection
    {
        return Models\Room::query()->chunkMap(fn (Models\Room $room) => new Room($this, $room));
    }

    /**
     * @return Collection<int, Room>
     */
    public function roomsWithTags(array $tags): Collection
    {
        return Models\Room::query()->whereJsonContains('tags', $tags)
            ->chunkMap(fn (Models\Room $room) => new Room($this, $room));
    }
}

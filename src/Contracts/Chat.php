<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Enumerable;
use TTBooking\SupportChat\Exceptions\RoomNotFoundException;
use TTBooking\SupportChat\Support\Tag;

/**
 * @template TRoom of Room
 */
interface Chat
{
    /**
     * @return $this
     */
    public function as(Authenticatable $user): static;

    public function user(): Authenticatable;

    /**
     * @param  string|Model|Tag|list<string|Model|Tag>  $tags
     *
     * @phpstan-return TRoom
     */
    public function createRoom(?string $name = null, string|Model|Tag|array $tags = []): Room;

    /**
     * @phpstan-return TRoom
     *
     * @throws RoomNotFoundException
     */
    public function room(string $id): Room;

    /**
     * @return Enumerable<int, TRoom>
     */
    public function rooms(): Enumerable;

    /**
     * @param  list<string>  $tags
     * @return Enumerable<int, TRoom>
     */
    public function roomsWithTags(array $tags): Enumerable;
}

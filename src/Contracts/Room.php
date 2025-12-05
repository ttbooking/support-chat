<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Enumerable;
use TTBooking\SupportChat\Exceptions\MessageNotFoundException;
use TTBooking\SupportChat\Support\Tag;

/**
 * @template TMessage of Message
 */
interface Room
{
    public function id(): string;

    public function name(): string;

    /**
     * @return $this
     */
    public function tag(string|Model|Tag $tag, string|Model|Tag ...$tags): static;

    /**
     * @return $this
     */
    public function untag(string|Model|Tag $tag, string|Model|Tag ...$tags): static;

    /**
     * @return Enumerable<int, string>
     */
    public function tags(): Enumerable;

    /**
     * @param  iterable<Authenticatable|string|int>  $users
     * @return $this
     */
    public function addUsers(iterable $users): static;

    /**
     * @param  iterable<Authenticatable|string|int>  $users
     * @return $this
     */
    public function kickUsers(iterable $users): static;

    /**
     * @return Enumerable<int, Authenticatable>
     */
    public function users(): Enumerable;

    /**
     * @return Enumerable<int, TMessage>
     */
    public function messages(): Enumerable;

    /**
     * @phpstan-return TMessage
     *
     * @throws MessageNotFoundException
     */
    public function message(string $id): Message;

    public function post(string $content, ?Message $replyTo = null): Message;

    /**
     * @return $this
     */
    public function rename(string $name): static;

    public function delete(): void;
}

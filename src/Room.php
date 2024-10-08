<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use TTBooking\SupportChat\Exceptions\MessageNotFoundException;

/**
 * @implements Contracts\Room<Message>
 */
class Room implements Contracts\Room
{
    public function __construct(protected Chat $chat, protected Models\Room $model) {}

    public function id(): string
    {
        return $this->model->id;
    }

    /**
     * @return Collection<int, string>
     */
    public function tags(): Collection
    {
        return collect($this->model->tags->map->tag);
    }

    public function addUsers(iterable $users): static
    {
        $this->model->users()->syncWithoutDetaching(collect($users));

        return $this;
    }

    public function kickUsers(iterable $users): static
    {
        $this->model->users()->detach(collect($users));

        return $this;
    }

    /**
     * @return Collection<int, Authenticatable>
     */
    public function users(): Collection
    {
        return $this->model->users;
    }

    /**
     * @return Collection<int, Message>
     */
    public function messages(): Collection
    {
        return $this->model->messages()->chunkMap(fn (Models\Message $message) => new Message($this, $message));
    }

    public function message(string $id): Message
    {
        try {
            return new Message($this, $this->model->messages()->findOrFail($id));
        } catch (ModelNotFoundException $e) {
            throw new MessageNotFoundException("Message [$id] not found.", 0, $e);
        }
    }

    public function post(string $content, ?Contracts\Message $replyTo = null): Message
    {
        return new Message($this, $this->model->messages()->create([
            'content' => $content,
            'parent_id' => $replyTo?->id(),
        ]));
    }

    public function rename(string $name): static
    {
        $this->model->update(compact('name'));

        return $this;
    }

    public function delete(): void
    {
        $this->model->delete();
    }
}

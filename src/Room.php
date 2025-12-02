<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use TTBooking\SupportChat\Exceptions\MessageNotFoundException;
use TTBooking\SupportChat\Support\Tag;

/**
 * @implements Contracts\Room<Message>
 */
class Room implements Contracts\Room
{
    use Conditionable;

    public function __construct(protected Chat $chat, protected Models\Room $model) {}

    public function id(): string
    {
        return $this->model->id;
    }

    public function name(): string
    {
        return $this->model->name;
    }

    public function tag(string|Model|Tag $tag, string|Model|Tag ...$tags): static
    {
        foreach ([$tag, ...$tags] as $_) {
            $this->model->tags()->createOrFirst(Tag::from($_)->toArray());
        }

        return $this;
    }

    /**
     * @return Collection<int, string>
     */
    public function tags(): Collection
    {
        return collect($this->model->tags->map->name);
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
            'sent_by' => $this->chat->user()->getAuthIdentifier(),
            'reply_to' => $replyTo?->id(),
            'content' => $content,
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

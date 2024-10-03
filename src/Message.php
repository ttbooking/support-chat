<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

class Message implements Contracts\Message
{
    public function __construct(protected Room $room, protected Models\Message $model) {}

    public function id(): string
    {
        return $this->model->id;
    }

    public function room(): Room
    {
        return $this->room;
    }

    public function edit(string $content): static
    {
        $this->model->update(compact('content'));

        return $this;
    }

    public function delete(): void
    {
        $this->model->delete();
    }
}

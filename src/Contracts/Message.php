<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Contracts;

interface Message
{
    public function id(): string;

    /**
     * @return Room<static>
     */
    public function room(): Room;

    /**
     * @return $this
     */
    public function edit(string $content): static;

    public function delete(): void;
}

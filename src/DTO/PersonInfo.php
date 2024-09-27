<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\DTO;

use DateTimeInterface;

class PersonInfo
{
    public function __construct(
        public string $name,
        public ?string $email = null,
        public ?string $avatar = null,
        public bool $online = false,
        public ?DateTimeInterface $lastChanged = null,
    ) {}
}

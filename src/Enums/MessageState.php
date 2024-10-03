<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Enums;

enum MessageState: string
{
    case Saved = '✔️';
    case Distributed = '⚡';
    case Seen = '👀';
    case Failure = '❗';
}

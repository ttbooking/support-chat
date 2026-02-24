<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Enums;

enum MessageState: string
{
    case Saved = 'saved';
    case Distributed = 'distributed';
    case Seen = 'seen';
    case Failure = 'failure';
}

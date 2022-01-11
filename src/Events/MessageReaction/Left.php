<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Events\MessageReaction;

use Illuminate\Queue\SerializesModels;

class Left extends Event
{
    use SerializesModels;
}

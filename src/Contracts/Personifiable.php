<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Contracts;

use TTBooking\SupportChat\DTO\PersonInfo;

interface Personifiable
{
    public function getPersonInfo(): PersonInfo;
}

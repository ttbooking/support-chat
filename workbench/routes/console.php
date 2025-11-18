<?php

use Illuminate\Support\Facades\Schedule;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

Schedule::call(function () {
    Message::factory()->recycle(Room::all())->create();
})->name('shitpost')->everyTenSeconds();

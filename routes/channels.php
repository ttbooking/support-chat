<?php

use Illuminate\Support\Facades\Broadcast;
use TTBooking\SupportChat\Broadcasting\RoomChannel;

Broadcast::channel('support-chat.room.{room}', RoomChannel::class);

<?php

use Illuminate\Support\Facades\Broadcast;
use TTBooking\SupportChat\Broadcasting;

Broadcast::channel('support-chat.user.{id}', Broadcasting\UserChannel::class);

Broadcast::channel('support-chat.room.{room}', Broadcasting\RoomChannel::class);

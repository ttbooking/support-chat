<?php

use Illuminate\Support\Facades\Broadcast;
use TTBooking\SupportChat\Http\Controllers\BroadcastController;

Broadcast::channel('support-chat.room.{room}', [BroadcastController::class, 'userPresentInRoom']);

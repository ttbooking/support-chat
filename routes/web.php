<?php

use Illuminate\Support\Facades\Route;
use TTBooking\SupportChat\Http\Controllers\MessageController;
use TTBooking\SupportChat\Http\Controllers\RoomController;

Route::prefix('api/v1')->group(function () {

    Route::apiResources([
        'rooms' => class_basename(RoomController::class),
        'rooms.messages' => class_basename(MessageController::class),
    ], ['shallow' => true]);

});

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('index');

<?php

use Illuminate\Support\Facades\Route;
use TTBooking\SupportChat\Http\Controllers\MessageController;
use TTBooking\SupportChat\Http\Controllers\RoomController;

Route::prefix('api/v1')->group(function () {

    Route::apiResources([
        'rooms' => RoomController::class,
        'rooms.messages' => MessageController::class,
    ], ['shallow' => true]);

});

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('index');

<?php

use Illuminate\Support\Facades\Route;
use TTBooking\SupportChat\Http\Controllers\MessageAttachmentController;
use TTBooking\SupportChat\Http\Controllers\MessageController;
use TTBooking\SupportChat\Http\Controllers\MessageReactionController;
use TTBooking\SupportChat\Http\Controllers\RoomController;

Route::prefix('api/v1')->group(function () {

    Route::apiResources([
        'rooms' => '\\'.RoomController::class,
        'rooms.messages' => '\\'.MessageController::class,
        //'messages.attachments' => '\\'.MessageAttachmentController::class,
    ], ['shallow' => true]);

    Route::apiResource(
        'messages.attachments',
        '\\'.MessageAttachmentController::class,
        ['only' => ['store', 'show', 'destroy']]
    )->scoped(['attachment' => 'name']);

    Route::scopeBindings()->apiResource(
        'messages.reactions',
        '\\'.MessageReactionController::class,
        ['only' => ['store', 'destroy']]
    );

});

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('index');

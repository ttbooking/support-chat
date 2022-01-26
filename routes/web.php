<?php

use Illuminate\Support\Facades\Route;
use TTBooking\SupportChat\Http\Controllers\MessageAttachmentController;
use TTBooking\SupportChat\Http\Controllers\MessageController;
use TTBooking\SupportChat\Http\Controllers\MessageReactionController;
use TTBooking\SupportChat\Http\Controllers\RoomController;

Route::prefix('api/v1')->group(function () {

    Route::apiResources([
        'rooms' => class_basename(RoomController::class),
        'rooms.messages' => class_basename(MessageController::class),
        //'messages.attachments' => class_basename(MessageAttachmentController::class),
    ], ['shallow' => true]);

    Route::apiResource(
        'messages.attachments',
        class_basename(MessageAttachmentController::class),
        ['only' => ['store', 'show', 'destroy']]
    )->scoped(['attachment' => 'name']);

    Route::scopeBindings()->apiResource(
        'messages.reactions',
        class_basename(MessageReactionController::class),
        ['only' => ['store', 'destroy']]
    );

});

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('index');

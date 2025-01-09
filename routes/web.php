<?php

use Illuminate\Support\Facades\Route;
use TTBooking\SupportChat\Http\Controllers\AttachmentController;
use TTBooking\SupportChat\Http\Controllers\MessageController;
use TTBooking\SupportChat\Http\Controllers\ReactionController;
use TTBooking\SupportChat\Http\Controllers\RoomController;
use TTBooking\SupportChat\Http\Controllers\RoomTagController;
use TTBooking\SupportChat\Http\Controllers\UserController;

Route::prefix('api')->group(function () {
    Route::apiResource('users', '\\'.UserController::class, ['only' => ['index', 'show']]);

    Route::apiResource('tags', '\\'.RoomTagController::class, ['only' => ['index']]);

    Route::apiResources([
        'rooms' => '\\'.RoomController::class,
        'rooms.messages' => '\\'.MessageController::class,
        // 'messages.attachments' => '\\'.AttachmentController::class,
    ], ['shallow' => true]);

    Route::apiResource(
        'messages.attachments',
        '\\'.AttachmentController::class,
        ['only' => ['store', 'show', 'destroy']]
    )->scoped(['attachment' => 'name']);

    Route::scopeBindings()->apiResource(
        'messages.reactions',
        '\\'.ReactionController::class,
        ['only' => ['store', 'destroy']]
    );
});

Route::get('/{roomId?}', 'ChatController@index')->where('roomId', '(.{7})')->name('index');

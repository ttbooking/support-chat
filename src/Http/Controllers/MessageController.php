<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Http\Requests\StoreMessageRequest;
use TTBooking\SupportChat\Http\Resources\MessageResource;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

class MessageController extends Controller
{
    /**
     * Display a listing of the messages in the room.
     *
     * @param  Room  $room
     * @return ResourceCollection
     */
    public function index(Room $room): ResourceCollection
    {
        return MessageResource::collection($room->messages);
    }

    /**
     * Store a newly created message in storage.
     *
     * @param  StoreMessageRequest  $request
     * @param  Room  $room
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request, Room $room): JsonResponse
    {
        $message = $room->messages()->create($request->validated());

        return Response::json($message, JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified message.
     *
     * @param  Message  $message
     * @return MessageResource
     */
    public function show(Message $message): MessageResource
    {
        return new MessageResource($message);
    }

    /**
     * Update the specified message in storage.
     *
     * @param  StoreMessageRequest  $request
     * @param  Message  $message
     * @return JsonResponse
     */
    public function update(StoreMessageRequest $request, Message $message): JsonResponse
    {
        $message->update($request->validated());

        return Response::json($message);
    }

    /**
     * Remove the specified message from storage.
     *
     * @param  Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message): \Illuminate\Http\Response
    {
        $message->delete();

        return Response::noContent();
    }
}

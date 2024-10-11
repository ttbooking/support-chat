<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Http\Requests\StoreMessageRequest;
use TTBooking\SupportChat\Http\Resources\MessageResource;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

class MessageController extends Controller
{
    /**
     * Display a listing of the messages in the room.
     */
    public function index(Room $room): AnonymousResourceCollection
    {
        return MessageResource::collection($room->messages);
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(StoreMessageRequest $request, Room $room): MessageResource
    {
        $message = DB::transaction(static function () use ($request, $room) {
            $input = $request->validated();

            /** @var Message $message */
            $message = $room->messages()->create($input + ['sent_by' => auth()->id()]);

            if ($input['attachments']) {
                foreach ($input['attachments'] as $attachment) {
                    $message->files()->create($attachment);
                }
            }

            return $message;
        });

        return new MessageResource($message->load('files'));
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message): MessageResource
    {
        return new MessageResource($message);
    }

    /**
     * Update the specified message in storage.
     */
    public function update(StoreMessageRequest $request, Message $message): MessageResource
    {
        $message->update($request->validated());

        return new MessageResource($message);
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(Message $message): \Illuminate\Http\Response
    {
        $message->delete();

        return Response::noContent();
    }
}

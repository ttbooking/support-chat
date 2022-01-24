<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
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
     * @return MessageResource
     */
    public function store(StoreMessageRequest $request, Room $room): MessageResource
    {
        $input = $request->validated();

        /** @var Message $message */
        $message = $room->messages()->create($input + ['sender_id' => $request->user()->id]);

        if ($input['attachments']) {
            foreach ($input['attachments'] as $attachment) {
                $message->files()->create($attachment);
            }
        }

        /** @var UploadedFile $attachment */
        /*foreach (Arr::wrap($request->file('attachments')) as $attachment) {
            $attachment->store('attachments');
        }*/

        return new MessageResource($message);
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
     * @return MessageResource
     */
    public function update(StoreMessageRequest $request, Message $message): MessageResource
    {
        $message->update($request->validated());

        return new MessageResource($message);
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

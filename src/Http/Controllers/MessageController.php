<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Http\Requests\StoreMessageRequest;
use TTBooking\SupportChat\Http\Requests\UpdateMessageRequest;
use TTBooking\SupportChat\Http\Resources\MessageResource;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

class MessageController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource([Message::class, 'room']);
    }

    /**
     * Display a listing of the messages in the room.
     */
    public function index(Room $room): ResourceCollection
    {
        return $room->messages->toResourceCollection();
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

        return $message->load('files')->toResource();
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message): MessageResource
    {
        /** @var MessageResource */
        return $message->toResource();
    }

    /**
     * Update the specified message in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message): MessageResource
    {
        $message->update($request->validated());

        /** @var MessageResource */
        return $message->toResource();
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

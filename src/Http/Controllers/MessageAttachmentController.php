<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Http\Requests\StoreAttachmentRequest;
use TTBooking\SupportChat\Http\Resources\MessageResource;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

class MessageAttachmentController extends Controller
{
    /**
     * Store a newly created message in storage.
     *
     * @param  StoreAttachmentRequest  $request
     * @param  Room  $room
     * @return MessageResource
     */
    public function store(StoreAttachmentRequest $request, Message $message): MessageResource
    {
        $attachment = $request->file('attachment');
        //$attachment->storeAs($attachment->);

        //$message->files()->create();

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

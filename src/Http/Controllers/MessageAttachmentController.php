<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TTBooking\SupportChat\Events\MessageAttachment\Uploaded;
use TTBooking\SupportChat\Http\Requests\StoreAttachmentRequest;
use TTBooking\SupportChat\Http\Resources\MessageFileResource;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\MessageFile;

class MessageAttachmentController extends Controller
{
    /**
     * Store a newly created attachment in storage.
     */
    public function store(StoreAttachmentRequest $request, Message $message): MessageFileResource
    {
        $attachmentFile = $request->file('attachment');
        $attachment = $message->getAttachment($attachmentFile->getClientOriginalName());
        $attachmentFile->storeAs($message->attachmentPath, $attachment->name, config('support-chat.disk'));

        broadcast(new Uploaded($attachment))->toOthers();

        return new MessageFileResource($attachment);
    }

    /**
     * Download the specified attachment.
     */
    public function show(Message $message, MessageFile $attachment): StreamedResponse
    {
        return Storage::disk(config('support-chat.disk'))->download($attachment->attachmentPath);
    }

    /**
     * Remove the specified attachment from storage.
     */
    public function destroy(Message $message, MessageFile $attachment): \Illuminate\Http\Response
    {
        $attachment->delete();

        return Response::noContent();
    }
}

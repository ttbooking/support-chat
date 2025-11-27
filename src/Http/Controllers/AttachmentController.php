<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TTBooking\SupportChat\Events\Attachment\Uploaded;
use TTBooking\SupportChat\Http\Requests\StoreAttachmentRequest;
use TTBooking\SupportChat\Http\Resources\AttachmentResource;
use TTBooking\SupportChat\Models\Attachment;
use TTBooking\SupportChat\Models\Message;

class AttachmentController extends Controller
{
    /**
     * Store a newly created attachment in storage.
     */
    public function store(StoreAttachmentRequest $request, Message $message): AttachmentResource
    {
        $attachmentFile = $request->file('attachment');
        $attachment = $message->getAttachment($attachmentFile->getClientOriginalName());
        $attachmentFile->storeAs($message->attachmentPath, $attachment->name, config('support-chat.disk'));

        broadcast(new Uploaded($attachment))->toOthers();

        /** @var AttachmentResource */
        return $attachment->toResource();
    }

    /**
     * Download the specified attachment.
     */
    public function show(Message $message, Attachment $attachment): StreamedResponse
    {
        return Storage::disk(config('support-chat.disk'))->download($attachment->attachmentPath);
    }

    /**
     * Remove the specified attachment from storage.
     */
    public function destroy(Message $message, Attachment $attachment): \Illuminate\Http\Response
    {
        $attachment->delete();

        return Response::noContent();
    }
}

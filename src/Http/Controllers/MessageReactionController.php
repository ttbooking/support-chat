<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\MessageReaction;

class MessageReactionController extends Controller
{
    /**
     * Store a newly created message reaction in storage.
     *
     * @param  Request  $request
     * @param  Message  $message
     * @return JsonResponse
     */
    public function store(Request $request, Message $message): JsonResponse
    {
        $reaction = $message->reactions()->create([
            'user_id' => $request->user()->id,
            'emoji' => $request->getContent()[0],
        ]);

        return Response::json($reaction, JsonResponse::HTTP_CREATED);
    }

    /**
     * Remove the specified message from storage.
     *
     * @param  Message  $message
     * @param  MessageReaction  $reaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message, MessageReaction $reaction): \Illuminate\Http\Response
    {
        $reaction->delete();

        return Response::noContent();
    }
}

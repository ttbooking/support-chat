<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Reaction;

class ReactionController extends Controller
{
    /**
     * Store a newly created message reaction in storage.
     */
    public function store(Request $request, Message $message): \Illuminate\Http\Response
    {
        $message->reactions()->firstOrCreate([
            'user_id' => auth()->id(),
            'emoji' => Str::substr($request->getContent(), 0, 1),
        ]);

        return Response::noContent();
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(Message $message, Reaction $reaction): \Illuminate\Http\Response
    {
        $reaction->delete();

        return Response::noContent();
    }
}

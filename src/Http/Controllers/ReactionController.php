<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Reaction;

class ReactionController extends Controller
{
    /**
     * Store a newly created message reaction in storage.
     */
    public function store(Request $request, Message $message): \Illuminate\Http\Response
    {
        if (! $emoji = grapheme_extract($request->getContent(), 10, GRAPHEME_EXTR_MAXCHARS)) {
            throw ValidationException::withMessages([
                'emoji' => __('validation.emoji'),
            ]);
        }

        $message->reactions()->firstOrCreate([
            'user_id' => auth()->id(),
            'emoji' => $emoji,
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

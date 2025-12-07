<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use TTBooking\SupportChat\Http\Requests\ShowChatRequest;

class ChatController extends Controller
{
    /**
     * Standalone chat view.
     */
    public function index(ShowChatRequest $request, ?string $roomId = null): Factory|View
    {
        $features = $request->query();

        return view('support-chat::layout', compact('roomId', 'features'));
    }
}

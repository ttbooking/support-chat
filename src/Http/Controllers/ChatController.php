<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class ChatController extends Controller
{
    /**
     * Standalone chat view.
     */
    public function index(?string $roomId = null): Factory|View
    {
        return view('support-chat::layout', compact('roomId'));
    }
}

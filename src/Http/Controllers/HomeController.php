<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     */
    public function index(): Factory|View
    {
        return view('support-chat::layout', [
            'supportChatScriptVariables' => [
                'userId' => (string) auth()->id(),
                'path' => config('support-chat.path'),
            ],
        ]);
    }
}

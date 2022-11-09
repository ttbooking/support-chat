<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Http\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('support-chat::layout', [
            'supportChatScriptVariables' => [
                'path' => config('support-chat.path'),
                'pusher' => [
                    'key' => config('broadcasting.connections.pusher.key'),
                    'host' => config('broadcasting.connections.pusher.options.host'),
                    'port' => config('broadcasting.connections.pusher.options.port', 443),
                    'useTLS' => config('broadcasting.connections.pusher.options.useTLS', true),
                ],
            ],
        ]);
    }
}

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
            ],
        ]);
    }
}

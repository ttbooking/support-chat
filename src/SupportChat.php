<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use TTBooking\ViteManager\Facades\Vite;

class SupportChat
{
    public static function standalone(?string $roomId = null): Htmlable
    {
        $scriptVariables = json_encode([
            'path' => config('support-chat.path'),
            'userId' => (string) auth()->id(),
            'roomId' => $roomId,
        ]);

        return new HtmlString(
            "<script>window.SupportChat = $scriptVariables</script>".
            Vite::app('standalone-chat')->toHtml()
        );
    }

    public static function windowed(): Htmlable
    {
        $scriptVariables = json_encode([
            'path' => config('support-chat.path'),
            'userId' => (string) auth()->id(),
        ]);

        return new HtmlString(
            "<script>window.SupportChat = $scriptVariables</script>".
            Vite::app('windowed-chat')->toHtml()
        );
    }
}

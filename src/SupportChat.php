<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;

class SupportChat
{
    public static function register(): Htmlable
    {
        $scriptVariables = json_encode([
            'userId' => (string) auth()->id(),
            'path' => config('support-chat.path'),
        ]);

        return new HtmlString(
            "<script>window.SupportChat = $scriptVariables</script>".
            Vite::useHotFile('vendor/support-chat/hot')
                ->useBuildDirectory('vendor/support-chat/build')
                ->withEntryPoints(['resources/js/app.ts'])
                ->toHtml()
        );
    }

    public static function asset(string $asset): string
    {
        return Vite::useHotFile('vendor/support-chat/hot')
            ->useBuildDirectory('vendor/support-chat/build')
            ->asset("resources/$asset");
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\HtmlString;
use TTBooking\SupportChat\Http\Resources\UserResource;
use TTBooking\ViteManager\Facades\Vite;

class SupportChat
{
    /**
     * @return class-string<User>
     */
    public static function userModel(): string
    {
        /** @var class-string<User> */
        return config('support-chat.user_model', 'App\\Models\\User');
    }

    /**
     * @return class-string<JsonResource>
     */
    public static function userResource(): string
    {
        /** @var class-string<JsonResource> */
        return config('support-chat.user_resource', UserResource::class);
    }

    public static function standalone(?string $roomId = null): HtmlString
    {
        $scriptVariables = json_encode([
            'path' => config('support-chat.path'),
            'userId' => (string) auth()->id(),
            'roomId' => $roomId,
            'features' => config('support-chat.features', []),
            'styles' => (object) array_filter(config('support-chat.styles', [])),
        ]);

        return new HtmlString(
            "<script>window.SupportChat = $scriptVariables;</script>".
            Vite::app('support-chat')->toHtml()
        );
    }

    public static function windowed(): HtmlString
    {
        $scriptVariables = json_encode([
            'path' => config('support-chat.path'),
            'userId' => (string) auth()->id(),
            'features' => config('support-chat.features', []),
            'styles' => config('support-chat.styles', []),
        ]);

        return new HtmlString(
            "<script>window.SupportChat = $scriptVariables;</script>".
            Vite::app('support-chat')->withEntryPoints(['resources/js/win.ts'])->toHtml()
        );
    }
}

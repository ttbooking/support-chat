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

    /**
     * @param  array<string, mixed>  $features
     * @param  array{light?: array<string, array<string, string>>, dark?: array<string, array<string, string>>}  $styles
     */
    public static function standalone(string $filter = '', ?string $roomId = null, array $features = [], array $styles = []): HtmlString
    {
        $scriptVariables = json_encode([
            'path' => config('support-chat.path'),
            'userId' => (string) auth()->id(),
            'roomId' => $roomId,
            'filter' => $filter,
            'features' => (object) ($features + array_filter(config('support-chat.features', []), static fn (mixed $value) => ! is_null($value))),
            'styles' => [
                'light' => (object) array_merge_recursive(array_filter(config('support-chat.styles.light', [])), $styles['light'] ?? []),
                'dark' => (object) array_merge_recursive(array_filter(config('support-chat.styles.dark', [])), $styles['dark'] ?? []),
            ],
        ]);

        return new HtmlString(
            "<script>window.SupportChat = $scriptVariables;</script>".
            Vite::app('support-chat')->toHtml()
        );
    }

    /**
     * @param  array<string, mixed>  $features
     * @param  array{light?: array<string, array<string, string>>, dark?: array<string, array<string, string>>}  $styles
     */
    public static function windowed(array $features = [], array $styles = []): HtmlString
    {
        $scriptVariables = json_encode([
            'path' => config('support-chat.path'),
            'userId' => (string) auth()->id(),
            'features' => (object) ($features + array_filter(config('support-chat.features', []), static fn (mixed $value) => ! is_null($value))),
            'styles' => [
                'light' => (object) array_merge_recursive(array_filter(config('support-chat.styles.light', [])), $styles['light'] ?? []),
                'dark' => (object) array_merge_recursive(array_filter(config('support-chat.styles.dark', [])), $styles['dark'] ?? []),
            ],
        ]);

        return new HtmlString(
            "<script>window.SupportChat = $scriptVariables;</script>".
            Vite::app('support-chat')->withEntryPoints(['resources/js/win.ts'])->toHtml()
        );
    }
}

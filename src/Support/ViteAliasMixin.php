<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Support;

use Closure;
use Illuminate\Foundation\Vite;

/**
 * @mixin Vite
 */
class ViteAliasMixin
{
    /**
     * @return Closure(): $this
     */
    protected function supportChatEntryPoint(): Closure
    {
        return fn (): static => $this
            ->useHotFile('vendor/support-chat/hot')
            ->useBuildDirectory('vendor/support-chat/build')
            ->withEntryPoints(['resources/js/app.ts']);
    }

    /**
     * @return Closure(string $asset): string
     */
    protected function supportChatImage(): Closure
    {
        return fn (string $asset): string => $this
            ->useHotFile('vendor/support-chat/hot')
            ->useBuildDirectory('vendor/support-chat/build')
            ->asset("resources/images/$asset");
    }
}

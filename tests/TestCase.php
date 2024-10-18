<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\SupportChat\Facades\Chat;
use TTBooking\SupportChat\SupportChat;
use TTBooking\SupportChat\SupportChatServiceProvider;
use TTBooking\ViteManager\ViteServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected $enablesPackageDiscoveries = true;

    protected function getPackageProviders($app): array
    {
        return [ViteServiceProvider::class, SupportChatServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Chat' => Chat::class,
            'SupportChat' => SupportChat::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        //
    }
}

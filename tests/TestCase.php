<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\SupportChat\SupportChat;
use TTBooking\SupportChat\SupportChatServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [SupportChatServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['SupportChat' => SupportChat::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        //
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use TTBooking\SupportChat\Facades\Chat;
use TTBooking\SupportChat\SupportChat;

abstract class TestCase extends OrchestraTestCase
{
    use WithWorkbench;

    protected $enablesPackageDiscoveries = true;

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

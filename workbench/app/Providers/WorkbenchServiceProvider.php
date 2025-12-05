<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Workbench\App\Models\User;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../public/' => public_path(),
            ], 'workbench-assets');
        }

        config([
            'support-chat.user_model' => User::class,
            'support-chat.user_cred_seed' => 'test@example.com',
        ]);

        Broadcast::routes();
    }
}

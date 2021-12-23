<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SupportChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerMigrations();
        }
    }

    /**
     * Register the Support Chat routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group([
            'domain' => $this->app['config']['support-chat.domain'] ?? null,
            'prefix' => $this->app['config']['support-chat.path'] ?? null,
            'name' => 'support-chat.',
            'namespace' => 'TTBooking\\SupportChat\\Http\\Controllers',
            'middleware' => $this->app['config']['support-chat.middleware'] ?? 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadRoutesFrom(__DIR__.'/../routes/channels.php');
    }

    /**
     * Register the Support Chat resources.
     *
     * @return void
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'support-chat');
    }

    protected function offerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/support-chat.php' => $this->app->configPath('support-chat.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/support-chat'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/support-chat'),
        ], 'assets');
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->configure();
        $this->registerServices();
    }

    protected function configure(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/support-chat.php', 'support-chat');
    }

    protected function registerServices(): void
    {
        //
    }
}

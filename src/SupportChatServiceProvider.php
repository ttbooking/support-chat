<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TTBooking\ViteManager\Facades\Vite;

class SupportChatServiceProvider extends ServiceProvider
{
    /**
     * All of the singletons that should be registered.
     *
     * @var array<string, class-string>
     */
    public array $singletons = [
        'support-chat' => Chat::class,
    ];

    /**
     * The commands to be registered.
     *
     * @var list<class-string<Command>>
     */
    protected array $commands = [
        Console\AddCommand::class,
        Console\AddRoomCommand::class,
        Console\InfoCommand::class,
        Console\InitCommand::class,
        Console\KickCommand::class,
        Console\ListCommand::class,
        Console\PostCommand::class,
        Console\RemoveRoomCommand::class,
        Console\SeedCommand::class,
        Console\TagCommand::class,
        Console\UntagCommand::class,
        Console\ViewCommand::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerAssets();
        $this->registerBladeDirectives();
        $this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerMigrations();
        }
    }

    /**
     * Register the Support Chat routes.
     */
    protected function registerRoutes(): void
    {
        Route::domain($this->app['config']['support-chat.domain'])
            ->prefix($this->app['config']['support-chat.path'] ?? 'support-chat')
            ->name('support-chat.')
            ->namespace('TTBooking\\SupportChat\\Http\\Controllers')
            ->middleware($this->app['config']['support-chat.middleware'] ?? ['web', 'auth'])
            ->group(fn () => $this->loadRoutesFrom(__DIR__.'/../routes/web.php'));

        require __DIR__.'/../routes/channels.php';
    }

    /**
     * Register the Support Chat assets.
     */
    protected function registerAssets(): void
    {
        Vite::app('support-chat')
            ->useHotFile('vendor/support-chat/hot')
            ->useBuildDirectory('vendor/support-chat/build')
            ->withEntryPoints(['resources/js/app.ts']);
    }

    /**
     * Register the Support Chat Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('chat', static function (string $expression) {
            return "<?php echo TTBooking\SupportChat\SupportChat::standalone($expression)->toHtml(); ?>";
        });

        Blade::directive('winchat', static function (string $expression) {
            return "<?php echo TTBooking\SupportChat\SupportChat::windowed($expression)->toHtml(); ?>";
        });
    }

    /**
     * Register the Support Chat resources.
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'support-chat');
    }

    protected function offerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/support-chat.php' => $this->app->configPath('support-chat.php'),
        ], ['support-chat-config', 'support-chat', 'config']);

        $this->publishes([
            __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
        ], ['support-chat-migrations', 'support-chat', 'migrations']);

        $this->publishes([
            __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/support-chat'),
        ], ['support-chat-views', 'support-chat', 'views']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/support-chat'),
        ], ['support-chat-assets', 'support-chat', 'assets']);
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->configure();
        $this->registerServices();
        $this->registerCommands();
    }

    /**
     * Setup the configuration for Support Chat.
     */
    protected function configure(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/support-chat.php', 'support-chat');
    }

    /**
     * Register Support Chat's services in the container.
     */
    protected function registerServices(): void
    {
        $this->app->when(Chat::class)->needs(Authenticatable::class)->give(static function () {
            $credentials = [
                config('support-chat.user_cred_key') => config('support-chat.user_cred_seed'),
            ];

            return Auth::getProvider()->retrieveByCredentials($credentials) ?? new User;
        });

        $this->app->alias('support-chat', Contracts\Chat::class);
    }

    /**
     * Register the Support Chat Artisan commands.
     */
    protected function registerCommands(): void
    {
        foreach ($this->commands as $command) {
            $this->app->singleton($command);
        }

        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return list<string>
     */
    public function provides(): array
    {
        return ['support-chat', Contracts\Chat::class, ...$this->commands];
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Tags\Tag;
use TTBooking\SupportChat\Models\Room;

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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerBladeDirective();
        $this->defineTagRoomsRelationship();

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
        Route::group([
            'domain' => $this->app['config']['support-chat.domain'] ?? null,
            'prefix' => $this->app['config']['support-chat.path'] ?? null,
            'name' => 'support-chat.',
            'namespace' => 'TTBooking\\SupportChat\\Http\\Controllers',
            'middleware' => $this->app['config']['support-chat.middleware'] ?? 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        require __DIR__.'/../routes/channels.php';
    }

    protected function registerBladeDirective(): void
    {
        Blade::directive('supportChat', static function () {
            return "<?php echo TTBooking\SupportChat\SupportChat::register()->toHtml(); ?>";
        });
    }

    protected function defineTagRoomsRelationship(): void
    {
        $room = new Room;

        Tag::resolveRelationUsing('rooms', static fn (Tag $tag) => $tag
            ->morphedByMany(Room::class, $room->getTaggableMorphName(), $room->getTaggableTableName())
            ->using($room->getPivotModelClassName())
            ->ordered()
        );
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
    }

    protected function configure(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/support-chat.php', 'support-chat');
    }

    protected function registerServices(): void
    {
        $this->app->alias('support-chat', Contracts\Chat::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return list<string>
     */
    public function provides(): array
    {
        return ['support-chat', Contracts\Chat::class];
    }
}

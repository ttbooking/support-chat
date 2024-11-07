<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'chat:init',
    description: 'Clean up support chat tables',
)]
class InitCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'chat:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up support chat tables';

    /** @var list<string> */
    protected array $tables = [
        'message_reactions',
        'message_files',
        'messages',
        'user_status',
        'room_tag',
        'room_tags',
        'rooms',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $this->components->info('Cleaning up database.');

        Schema::disableForeignKeyConstraints();

        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        return 0;
    }
}

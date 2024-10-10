<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'chat:init',
    description: 'Clean up support chat tables',
)]
class InitCommand extends Command
{
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

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tables = [
            'message_reactions',
            'message_files',
            'messages',
            'room_user',
            'room_tag',
            'room_tags',
            'rooms',
        ];

        $this->components->info('Cleaning up database.');

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        $this->components->info('Clean up finished.');
    }
}

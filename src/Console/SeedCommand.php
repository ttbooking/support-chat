<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Database\Seeders\DatabaseSeeder;

#[AsCommand(
    name: 'chat:seed',
    description: 'Seed support chat tables',
)]
class SeedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'chat:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed support chat tables';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->call('db:seed', ['class' => DatabaseSeeder::class]);
    }
}

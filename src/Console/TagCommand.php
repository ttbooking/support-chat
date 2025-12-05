<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;

#[AsCommand(
    name: 'chat:tag',
    description: 'Tag room',
)]
class TagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:tag
        {room : Room id}
        {tag* : Tag(s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tag room';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $room = $chat->room($this->argument('room'))->tag(...$this->argument('tag'));

        $this->components->info(sprintf('Room <info>%s</info> successfully tagged.', $room->id()));
    }
}

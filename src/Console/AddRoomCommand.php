<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;

#[AsCommand(
    name: 'chat:add-room',
    description: 'Add a room',
)]
class AddRoomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:add-room {name? : Room name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a room';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $room = $chat->createRoom($this->argument('name'));

        $this->components->info(sprintf('Room <info>%s</info> successfully added.', $room->id()));
    }
}

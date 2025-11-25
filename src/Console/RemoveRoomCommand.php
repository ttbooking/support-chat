<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;

#[AsCommand(
    name: 'chat:remove-room',
    description: 'Remove a room',
)]
class RemoveRoomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:remove-room {room : Room identifier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a room';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $chat->room($id = $this->argument('room'))->delete();

        $this->components->info(sprintf('Room <info>%s</info> successfully removed.', $id));
    }
}

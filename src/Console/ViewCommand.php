<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;
use function Laravel\Prompts\note;

#[AsCommand(
    name: 'chat:view',
    description: 'View room messages',
)]
class ViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:view {room : Room to view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View room messages';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $room = $chat->room($this->argument('room'));

        foreach ($room->messages() as $message) {
            note(sprintf('<bg=cyan;fg=black> %s </> %s', $message->id(), $message->content()));
        }
    }
}

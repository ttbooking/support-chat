<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;
use function Laravel\Prompts\note;

#[AsCommand(
    name: 'chat:list',
    description: 'List chat rooms',
)]
class ListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'chat:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List chat rooms';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        foreach ($chat->rooms() as $room) {
            note(sprintf('<bg=cyan;fg=black> %s </> %s', $room->id(), $room->name()));
        }
    }
}

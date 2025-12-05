<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;

#[AsCommand(
    name: 'chat:info',
    description: 'Room info',
)]
class InfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:info {room : Room id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Room info';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $room = $chat->room($this->argument('room'));

        $this->components->info(sprintf(
            'The room <info>%s</info> is titled <info>%s</info> and has <info>%d</info> messages.',
            $room->id(),
            $room->name(),
            $room->messages()->count(),
        ));

        $tags = $room->tags()->map('strval')->join('</info>, <info>', '</info> and <info>');
        $this->components->info($tags ? sprintf('<info>%s</info>.', $tags) : 'No tags.');

        $users = $room->users()->map->name->join('</info>, <info>', '</info> and <info>');
        $this->components->info(sprintf('<info>%s</info>.', $users));
    }
}

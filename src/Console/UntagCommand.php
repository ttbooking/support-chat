<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;

#[AsCommand(
    name: 'chat:untag',
    description: 'Untag room',
)]
class UntagCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:untag
        {room : Room id}
        {tag* : Tag(s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untag room';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $room = $chat->room($this->argument('room'))->untag(...$this->argument('tag'));

        $this->components->info(sprintf('Room <info>%s</info> successfully untagged.', $room->id()));
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;

#[AsCommand(
    name: 'chat:post',
    description: 'Post a message',
)]
class PostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:post
        {room : Room to post into}
        {message : Message to post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post a message';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $message = $chat->room($this->argument('room'))->post($this->argument('message'));

        $this->components->info(sprintf('Message <info>%s</info> successfully posted.', $message->id()));
    }
}

<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\SupportChat\Contracts\Chat;

#[AsCommand(
    name: 'chat:add',
    description: 'Add user to a room',
)]
class AddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:add
        {room : Room id}
        {user* : User(s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user to a room';

    /**
     * Execute the console command.
     */
    public function handle(Chat $chat): void
    {
        $userProvider = Auth::getProvider();
        $users = collect($this->argument('user'))->map(static function (string $user) use ($userProvider) {
            return $userProvider->retrieveById($user)
                ?? $userProvider->retrieveByCredentials([config('support-chat.user_cred_key') => $user]);
        })->filter()->unique();

        $room = $chat->room($this->argument('room'))->addUser(...$users->all());

        $this->components->info(sprintf(
            '<info>%s</info> has been successfully added to room <info>%s</info>.',
            $users->pluck('name')->join('</info>, <info>', '</info> and <info>'),
            $room->id(),
        ));
    }
}

<?php

declare(strict_types=1);

namespace Workbench\App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'pnpm:build',
    description: 'Build workbench assets',
)]
class PnpmBuildCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pnpm:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build workbench assets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        passthru('cd workbench && pnpm install && pnpm build', $result);

        return $result;
    }
}

<?php

namespace Uteq\Convey\Commands;

use Illuminate\Console\Command;
use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    public $signature = 'convey:install';

    public $description = 'Installs all of the Webpush resources';

    public function handle(): int
    {
        $name = text('What is your name?');

        dd($name);

        $this->comment('All done');

        return self::SUCCESS;
    }
}

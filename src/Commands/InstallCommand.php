<?php

namespace Uteq\Convey\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class InstallCommand extends Command
{
    public $signature = 'convey:install';

    public function handle(): int
    {
        $this->info('For more info or to run this manually visit https://laravel-notification-channels.com/webpush/#installation');
        $this->handleWebpush();
        $this->handleVapidKeys();

        // TODO

        $this->comment('All done');

        return self::SUCCESS;
    }

    public function handleVapidKeys()
    {
        $keysAlreadySet = config('convey.vapid_public_key')
            && config('convey.vapid_private_key');

        $question = match ($keysAlreadySet) {
            true => confirm('VAPID keys are already set in you .env file do you want to overwrite them?'),
            false => confirm('Would you like to generate the VAPID keys?'),
        };

        if (! $question) {
            return;
        }

        $result = select('How would you like to add the VAPID keys', [
            null => 'add them to my .env file',
            'show' => 'show them in console',
        ]);

        if ($result === 'show') {
            $this->call('webpush:vapid', ['--show' => true]);
            $this->info('Add the keys to your .env file');
        } else {
            $this->call('webpush:vapid', []);
        }
    }

    public function handleWebpush()
    {
        if ($migrationsAdded = confirm('Would you like to publish the webpush migrations?')) {
            $this->call('vendor:publish', [
                '--provider' => 'NotificationChannels\WebPush\WebPushServiceProvider',
                '--tag' => 'migrations',
            ]);
        }

        if ($migrationsAdded && confirm('Would you like to migrate now?')) {
            $this->call('migrate');
        }

        if (confirm('Would publish the config file now?')) {
            $this->call('vendor:publish', [
                '--provider' => 'NotificationChannels\WebPush\WebPushServiceProvider',
                '--tag' => 'config',
            ]);
        }
    }
}

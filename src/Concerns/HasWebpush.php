<?php

namespace Uteq\Convey\Concerns;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

trait HasWebpush
{
    #[locked]
    public ?string $webpushNotifier = null;

    #[Computed]
    public function user(): User
    {
        return auth()->user();
    }

    public function webpushNotifier(string $notifier)
    {
        $this->webpushNotifier = $notifier;
    }

    #[On('convey::subscribe')]
    public function subscribe($subscription)
    {
        $this->user->updatePushSubscription(
            endpoint: $subscription['endpoint'],
            key: $subscription['keys']['p256dh'],
            token: $subscription['keys']['auth'],
            contentEncoding: 'aesgcm',
        );

        $this->dispatch('convey::subscribed');
    }

    #[On('convey::subscribed')]
    public function subscribed()
    {
        if (! $this->webpushNotifier) {
            return;
        }

        $this->user->notify(new ($this->webpushNotifier));
    }
}

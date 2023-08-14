<?php

use function Livewire\Volt\{state, on};

state(['user', 'notifier']);

on(['webpush::subscribe' => function($subscription) {
    $this->user->updatePushSubscription(
        endpoint: $subscription['endpoint'],
        key: $subscription['keys']['p256dh'],
        token: $subscription['keys']['auth'],
        contentEncoding: 'aesgcm',
    );

    $this->dispatch('webpush::subscribed');
}]);

on(['webpush::subscribed' => function() {
    if (! $this->notifier) return;

    $this->user->notify(new ($this->notifier));
}]);

on(['webpush::unsubscribe' => function() {
    $this->user->deletePushSubscription();

    $this->dispatch('webpush::unsubscribed');
}]);

?>

<button
    {{ $attributes }}
    x-data="{
        loading: false,

        async askPushPermission() {

            this.loading = true;

            let permission = await Notification.requestPermission();

            if (permission === 'granted' || permission === 'default') {
                await this.subscribeUserToPush();
            } else {
                alert('Permission denied ' + permission);
            }

            await new Promise(r => setTimeout(r, 300));

            this.loading = false;
        },

        async subscribeUserToPush() {
            let registration = await navigator.serviceWorker.ready;

            if (! registration) {
                return;
            }

            let subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: window.vapidPublicKey
            });

            if (! subscription) {
                return;
            }

            await Livewire.dispatch('webpush::subscribe', {subscription: subscription});
        }
    }"
    x-on:click="askPushPermission; loading = true"
    :class="{ 'opacity-50': loading }"
>
    @volt <template></template> @endvolt

    {{ $slot }}
</button>

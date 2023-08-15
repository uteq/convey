<button
    {{ $attributes }}
    x-data="webpush"
    x-on:click="askPushPermission; loading = true;"
    :class="{ 'opacity-50': loading }"
>
    {{ $slot }}
</button>

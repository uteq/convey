<button
    {{ $attributes }}
    x-data="convey"
    x-on:click="askPushPermission; loading = true;"
    :class="{ 'opacity-50': loading }"
>
    {{ $slot }}
</button>

@props([
    'title' => '',
    'name' => 'adminModal',
])

<div
    x-data="{ open: false }"
    x-on:open-admin-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:keydown.escape.window="open = false"
    {{ $attributes }}
>
    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="admin-modal-backdrop"
            @click.self="open = false"
            role="dialog"
            aria-modal="true"
        >
            <div
                class="admin-modal-panel"
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                @click.stop
            >
                @if ($title !== '')
                    <h2 class="admin-modal-title">{{ $title }}</h2>
                @endif
                <div class="admin-modal-body">
                    {{ $slot }}
                </div>
                @isset($footer)
                    <div class="admin-modal-actions">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </template>
</div>

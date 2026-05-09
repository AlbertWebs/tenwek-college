@props(['header' => 'Dashboard', 'title' => null, 'breadcrumbs' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-dvh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? $header.' | '.config('tenwek.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="admin-app h-dvh max-h-dvh overflow-hidden bg-[var(--admin-canvas-bg)] text-thc-text antialiased"
    x-data="adminShell()"
    @keydown.escape.window="sidebarOpen = false"
>
    <div class="flex h-full min-h-0 w-full overflow-hidden">
        <div
            class="fixed inset-0 z-20 bg-thc-navy/40 backdrop-blur-[1px] transition-opacity lg:hidden motion-reduce:transition-none"
            x-show="sidebarOpen"
            x-cloak
            x-transition.opacity
            @click="sidebarOpen = false"
            aria-hidden="true"
        ></div>

        <x-admin.sidebar :admin-nav-groups="$adminNavGroups ?? []" />

        <div class="flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden lg:min-w-0">
            <x-admin.topbar :header="$header" :breadcrumbs="$breadcrumbs" />
            <main class="admin-main min-h-0 flex-1 overflow-y-auto overscroll-y-contain p-4 sm:p-6 lg:p-8">
                @if (session('status'))
                    <div class="admin-alert-success mb-6">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="admin-alert-error mb-6" role="alert">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

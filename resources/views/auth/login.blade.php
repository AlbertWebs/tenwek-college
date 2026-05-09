<x-layouts.guest title="Sign in | {{ config('tenwek.name') }}">
    <div class="w-full max-w-md rounded-2xl border border-thc-navy/12 bg-white p-8 shadow-sm">
        <h1 class="font-serif text-2xl font-semibold text-thc-navy">Administration</h1>
        <p class="mt-2 text-sm text-thc-text/90">Sign in with your institutional account.</p>

        <form method="post" action="{{ route('login') }}" class="mt-8 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-thc-text">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-thc-text">Password</label>
                <input type="password" name="password" id="password" required class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-thc-text/90">
                <input type="checkbox" name="remember" class="rounded border-thc-text/25 text-thc-navy focus:ring-thc-royal">
                Remember me
            </label>
            <button type="submit" class="w-full rounded-full bg-thc-royal py-3 text-sm font-semibold text-white hover:bg-thc-navy">Sign in</button>
        </form>
    </div>
</x-layouts.guest>

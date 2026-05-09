<x-layouts.public :seo="$seo">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:grid lg:grid-cols-2 lg:gap-16 lg:px-8 lg:py-20">
        <div>
            <h1 class="font-serif text-4xl font-semibold text-thc-navy sm:text-5xl">Contact us</h1>
            <p class="mt-4 text-lg text-thc-text/90">Admissions, clinical coordination, partnerships, and general enquiries.</p>
            <dl class="mt-10 space-y-4 text-sm">
                <div>
                    <dt class="font-semibold text-thc-navy">Email</dt>
                    <dd><a href="mailto:{{ config('tenwek.email_public') }}" class="text-thc-royal hover:underline">{{ config('tenwek.email_public') }}</a></dd>
                </div>
                <div>
                    <dt class="font-semibold text-thc-navy">Phone</dt>
                    <dd><a href="tel:{{ preg_replace('/\s+/', '', config('tenwek.phone')) }}" class="text-thc-royal hover:underline">{{ config('tenwek.phone') }}</a></dd>
                </div>
                <div>
                    <dt class="font-semibold text-thc-navy">Address</dt>
                    <dd class="text-thc-text/90">{{ config('tenwek.address.street') }}, {{ config('tenwek.address.locality') }}, {{ config('tenwek.address.country_name') }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-12 rounded-2xl border border-thc-navy/12 bg-white p-6 shadow-sm lg:mt-0">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-thc-royal/25 bg-thc-royal/8 px-4 py-3 text-sm text-thc-navy">{{ session('status') }}</div>
            @endif

            <form method="post" action="{{ route('contact.store') }}" class="space-y-4">
                @csrf
                <input type="text" name="fax" tabindex="-1" autocomplete="off" class="absolute -left-[9999px] h-0 w-0 opacity-0" aria-hidden="true">

                <div>
                    <label for="name" class="block text-sm font-medium text-thc-text">Full name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-thc-text">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-thc-text">Phone <span class="font-normal text-thc-text/65">(optional)</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="topic" class="block text-sm font-medium text-thc-text">Topic <span class="font-normal text-thc-text/65">(optional)</span></label>
                    <input type="text" name="topic" id="topic" value="{{ old('topic') }}" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">
                    @error('topic')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-thc-text">Message <span class="font-normal text-thc-text/65">(optional)</span></label>
                    <textarea name="message" id="message" rows="5" class="mt-1 w-full rounded-xl border border-thc-navy/12 px-3 py-2 text-sm focus:border-thc-royal focus:outline-none focus:ring-2 focus:ring-thc-royal/20">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full rounded-full bg-thc-royal py-3 text-sm font-semibold text-white hover:bg-thc-navy sm:w-auto sm:px-8">Send message</button>
            </form>
        </div>
    </div>
</x-layouts.public>

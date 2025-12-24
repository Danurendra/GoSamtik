<x-guest-layout>
    <div class="space-y-2 mb-6">
        <p class="text-sm font-semibold text-eco-700 inline-flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-eco-500"></span>
            <span>Masuk ke GO SAMTIK</span>
        </p>
        <h2 class="text-2xl font-bold text-gray-900">Atur penjemputan dengan mudah</h2>
        <p class="text-gray-600">Pantau progres driver, jadwal, dan pembayaran dalam satu dashboard.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="space-y-1">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-eco-700 hover:text-eco-800" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center space-x-2 text-sm text-gray-700">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-eco-600 shadow-sm focus:ring-eco-500" name="remember">
                <span>Ingatkan saya</span>
            </label>
            <a href="{{ route('register') }}" class="text-sm font-medium text-eco-700 hover:text-eco-800">Belum punya akun?</a>
        </div>

        <x-primary-button class="w-full justify-center py-3 text-base">
            Masuk dan lanjutkan
        </x-primary-button>
    </form>

    <div class="mt-6 p-4 rounded-2xl bg-eco-50 border border-eco-100">
        <div class="flex items-start space-x-3">
            <div class="w-9 h-9 rounded-xl bg-white text-eco-700 flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5l7 3v4.5c0 4.556-3.053 8.682-7 9.5-3.947-.818-7-4.944-7-9.5V7.5l7-3z"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900">Dukungan 24/7</p>
                <p class="text-sm text-gray-600">Tim kami siap membantu jadwal dan kendala penjemputan kapan saja.</p>
            </div>
        </div>
    </div>
</x-guest-layout>

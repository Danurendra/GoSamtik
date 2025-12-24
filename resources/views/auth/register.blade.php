<x-guest-layout>
    <div class="space-y-2 mb-6">
        <p class="text-sm font-semibold text-eco-700 inline-flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-eco-500"></span>
            <span>Buat akun baru</span>
        </p>
        <h2 class="text-2xl font-bold text-gray-900">Mulai jadwalkan penjemputan</h2>
        <p class="text-gray-600">Daftar untuk mengelola langganan, pembayaran, dan notifikasi GO SAMTIK.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div class="grid sm:grid-cols-2 gap-4">
            <div class="space-y-1 sm:col-span-2">
                <x-input-label for="name" value="Nama lengkap" />
                <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama sesuai identitas" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="space-y-1 sm:col-span-2">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="space-y-1">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="space-y-1">
                <x-input-label for="password_confirmation" value="Konfirmasi password" />
                <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-start space-x-2 text-sm text-gray-600">
            <input type="checkbox" id="terms" name="terms" class="mt-0.5 rounded border-gray-300 text-eco-600 shadow-sm focus:ring-eco-500" required>
            <label for="terms">Saya setuju dengan ketentuan layanan dan kebijakan privasi GO SAMTIK.</label>
        </div>

        <x-primary-button class="w-full justify-center py-3 text-base">
            Buat akun dan lanjutkan
        </x-primary-button>

        <p class="text-center text-sm text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-eco-700 hover:text-eco-800">Masuk di sini</a></p>
    </form>

    <div class="mt-6 grid sm:grid-cols-2 gap-4">
        <div class="p-4 rounded-2xl bg-eco-50 border border-eco-100">
            <p class="text-sm font-semibold text-eco-800">Langganan fleksibel</p>
            <p class="text-sm text-gray-600 mt-1">Pilih jadwal penjemputan harian, mingguan, atau custom sesuai kebutuhan.</p>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm">
            <p class="text-sm font-semibold text-gray-900">Lacak driver & rute</p>
            <p class="text-sm text-gray-600 mt-1">Lihat posisi driver, status rute, dan riwayat koleksi langsung dari dashboard.</p>
        </div>
    </div>
</x-guest-layout>

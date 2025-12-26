<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Penjemputan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('collections.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">1. Seberapa banyak sampahmu?</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <label class="cursor-pointer relative">
                                    <input type="radio" name="waste_size" value="small" class="peer sr-only" checked onchange="calculatePrice()">
                                    <div class="p-4 border-2 rounded-lg hover:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-50 transition">
                                        <div class="font-bold text-lg">Kecil (Small)</div>
                                        <div class="text-sm text-gray-500">± 5kg (1-2 Kantong Kresek)</div>
                                        <div class="mt-2 text-xs font-semibold text-blue-600">Harga Normal (x1)</div>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative">
                                    <input type="radio" name="waste_size" value="medium" class="peer sr-only" onchange="calculatePrice()">
                                    <div class="p-4 border-2 rounded-lg hover:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-50 transition">
                                        <div class="font-bold text-lg">Sedang (Medium)</div>
                                        <div class="text-sm text-gray-500">± 20kg (1 Tong Sampah/Karung)</div>
                                        <div class="mt-2 text-xs font-semibold text-blue-600">Harga x 2.5</div>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative">
                                    <input type="radio" name="waste_size" value="large" class="peer sr-only" onchange="calculatePrice()">
                                    <div class="p-4 border-2 rounded-lg hover:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-50 transition">
                                        <div class="font-bold text-lg">Besar (Large)</div>
                                        <div class="text-sm text-gray-500">± 50kg (Pickup Truck Load)</div>
                                        <div class="mt-2 text-xs font-semibold text-blue-600">Harga x 5</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="service_type_id" class="block text-sm font-medium text-gray-700 mb-2">2. Jenis Sampah</label>
                            <select name="service_type_id" id="service_type_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" onchange="calculatePrice()">
                                @foreach(\App\Models\ServiceType::where('is_active', true)->get() as $type)
                                    <option value="{{ $type->id }}" data-price="{{ $type->base_price }}">
                                        {{ $type->name }} - Rp {{ number_format($type->base_price, 0, ',', '.') }} / Paket Kecil
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center border border-gray-200">
                            <div>
                                <span class="block text-sm text-gray-500">Estimasi Total Biaya</span>
                                <span class="text-xs text-gray-400">*Harga dapat berubah sesuai berat aktual</span>
                            </div>
                            <span class="text-3xl font-bold text-green-600" id="display_price">Rp 0</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Penjemputan</label>
                                <input type="date" name="scheduled_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                    <input type="time" name="scheduled_time_start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                    <input type="time" name="scheduled_time_end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea name="service_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required placeholder="Jalan Mawar No. 123, Kecamatan..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                            <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Pagar hitam, bel rusak..."></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 shadow-lg transition transform hover:scale-105">
                                Buat Pesanan & Bayar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function calculatePrice() {
            // 1. Ambil Multiplier Ukuran
            let sizeElement = document.querySelector('input[name="waste_size"]:checked');
            let size = sizeElement ? sizeElement.value : 'small';

            let multiplier = 1;
            if (size === 'medium') multiplier = 2.5;
            if (size === 'large') multiplier = 5;

            // 2. Ambil Base Price dari Dropdown
            let select = document.getElementById('service_type_id');
            let selectedOption = select.options[select.selectedIndex];
            let basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            // 3. Logika Khusus Bulk Items (Barang Besar)
            // Jika user memilih "Barang Besar", multiplier ukuran mungkin tidak berlaku atau berbeda
            // Kita bisa cek text option-nya atau ID-nya.
            // Disini kita asumsi sederhana dulu:

            let total = basePrice * multiplier;

            // 4. Tampilkan Format Rupiah
            document.getElementById('display_price').innerText = formatRupiah(total);
        }

        // Jalankan saat load
        document.addEventListener('DOMContentLoaded', calculatePrice);
    </script>
</x-app-layout>

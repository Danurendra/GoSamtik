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

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Ups! Ada kesalahan.</strong>
                            <span class="block sm:inline">Mohon periksa inputan Anda di bawah.</span>
                        </div>
                    @endif

                    <form action="{{ route('collections.store') }}" method="POST" class="space-y-8" id="orderForm">
                        @csrf

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">1. Seberapa banyak sampahmu?</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="waste_size" value="small"
                                           class="peer sr-only"
                                           {{ old('waste_size', 'small') == 'small' ? 'checked' : '' }}
                                           onchange="calculatePrice()">
                                    <div class="p-4 border-2 rounded-lg border-gray-200 hover:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-50 transition h-full flex flex-col justify-between">
                                        <div>
                                            <div class="font-bold text-lg">Kecil (Small)</div>
                                            <div class="text-sm text-gray-500">± 5kg (1-2 Kantong Kresek)</div>
                                        </div>
                                        <div class="mt-2 text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded w-fit">Harga Normal (x1)</div>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="waste_size" value="medium"
                                           class="peer sr-only"
                                           {{ old('waste_size') == 'medium' ? 'checked' : '' }}
                                           onchange="calculatePrice()">
                                    <div class="p-4 border-2 rounded-lg border-gray-200 hover:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-50 transition h-full flex flex-col justify-between">
                                        <div>
                                            <div class="font-bold text-lg">Sedang (Medium)</div>
                                            <div class="text-sm text-gray-500">± 20kg (1 Tong Sampah/Karung)</div>
                                        </div>
                                        <div class="mt-2 text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded w-fit">Harga x 2.5</div>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="waste_size" value="large"
                                           class="peer sr-only"
                                           {{ old('waste_size') == 'large' ? 'checked' : '' }}
                                           onchange="calculatePrice()">
                                    <div class="p-4 border-2 rounded-lg border-gray-200 hover:border-green-500 peer-checked:border-green-600 peer-checked:bg-green-50 transition h-full flex flex-col justify-between">
                                        <div>
                                            <div class="font-bold text-lg">Besar (Large)</div>
                                            <div class="text-sm text-gray-500">± 50kg (Pickup Truck Load)</div>
                                        </div>
                                        <div class="mt-2 text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded w-fit">Harga x 5</div>
                                    </div>
                                </label>
                            </div>
                            @error('waste_size')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="service_type_id" class="block text-sm font-medium text-gray-700 mb-2">2. Jenis Sampah</label>
                            <select name="service_type_id" id="service_type_id"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                                    onchange="calculatePrice()">
                                @foreach($serviceTypes as $type)
                                    <option value="{{ $type->id }}"
                                            data-price="{{ $type->base_price }}"
                                        {{ old('service_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} - Rp {{ number_format($type->base_price, 0, ',', '.') }} / Paket Kecil
                                    </option>
                                @endforeach
                            </select>
                            @error('service_type_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-center border border-gray-200">
                            <div class="mb-2 sm:mb-0">
                                <span class="block text-sm text-gray-500 font-medium">Estimasi Total Biaya</span>
                                <span class="text-xs text-gray-400">*Harga final dihitung sistem sebelum pembayaran</span>
                            </div>
                            <span class="text-3xl font-bold text-green-600" id="display_price">Rp 0</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Penjemputan</label>
                                <input type="date" name="scheduled_date"
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ old('scheduled_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                @error('scheduled_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                    <input type="time" name="scheduled_time_start"
                                           value="{{ old('scheduled_time_start') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                    <input type="time" name="scheduled_time_end"
                                           value="{{ old('scheduled_time_end') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea name="service_address" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                      required
                                      placeholder="Nama Jalan, Nomor Rumah, RT/RW, Patokan...">{{ old('service_address') }}</textarea>
                            @error('service_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                      placeholder="Contoh: Pagar hitam, bel rusak, ada anjing galak...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="button"
                                    id="btn-submit"
                                    onclick="submitForm()"
                                    class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 shadow-lg transition transform hover:scale-105 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">

                                <svg id="btn-spinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>

                                <span id="btn-text">Buat Pesanan & Bayar</span>
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

        function submitForm() {
            const form = document.getElementById('orderForm');

            // Validasi HTML5 Native
            if (form.checkValidity()) {
                const btn = document.getElementById('btn-submit');
                const spinner = document.getElementById('btn-spinner');
                const text = document.getElementById('btn-text');

                // UI Loading
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btn.classList.remove('hover:scale-105'); // Hilangkan efek hover
                spinner.classList.remove('hidden');
                text.innerText = 'Memproses...';

                // Submit Form
                form.submit();
            } else {
                // Trigger browser validation popup
                form.reportValidity();
            }
        }

        // Jalankan saat load
        document.addEventListener('DOMContentLoaded', calculatePrice);
    </script>
</x-app-layout>

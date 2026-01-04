<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h2 class="text-2xl font-bold mb-4">Detail Penjemputan #{{ $collection->id }}</h2>

                @if($collection->payment_status == 'unpaid')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Menunggu Pembayaran</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Silakan transfer sebesar <strong>Rp {{ number_format($collection->total_amount) }}</strong> ke:</p>
                                    <ul class="list-disc pl-5 mt-2 font-bold">
                                        <li>BCA: 123-456-7890 (a.n GoSamtik)</li>
                                        <li>Mandiri: 987-000-1111 (a.n GoSamtik)</li>
                                    </ul>
                                    <p class="mt-2">Setelah transfer, mohon konfirmasi ke Admin via WhatsApp: 0812-xxxx-xxxx</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <p class="font-bold text-green-700">PEMBAYARAN LUNAS (PAID)</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

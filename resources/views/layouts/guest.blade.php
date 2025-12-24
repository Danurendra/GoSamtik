<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GO SAMTIK') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="relative min-h-screen bg-gradient-to-br from-eco-50 via-white to-emerald-50 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -left-24 top-10 w-72 h-72 bg-eco-200 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute right-0 bottom-10 w-80 h-80 bg-emerald-100 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute left-1/2 top-1/2 w-64 h-64 bg-eco-100 rounded-full blur-3xl opacity-40"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
            <div class="flex items-center justify-between mb-8 text-sm text-gray-600">
                <a href="/" class="inline-flex items-center space-x-4 group text-gray-700 transition-colors">
                    <div class="w-14 h-14 bg-white border-2 border-gray-100 rounded-full flex items-center justify-center shadow-sm group-hover:shadow-eco/20 group-hover:border-eco-200 group-hover:scale-105 transition-all duration-300">
                        <div class="relative">
                            <svg class="w-7 h-7 text-gray-400 group-hover:text-eco-600 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <div class="absolute -bottom-1.5 -right-1.5 bg-eco-500 rounded-full p-1 border-2 border-white shadow-sm">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col justify-center">
                        <span class="font-extrabold text-gray-900 text-2xl tracking-tight leading-none group-hover:text-eco-700 transition-colors">GoSamtik</span>
                        <span class="text-[10px] font-bold text-eco-600 uppercase tracking-[0.2em] mt-1.5 bg-eco-50 px-2 py-0.5 rounded-md w-fit">Smart Waste Solution</span>
                    </div>
                </a>
{{--                <a href="{{ Route::has('welcome') ? route('welcome') : url('/') }}" class="hidden sm:inline-flex items-center space-x-2 text-eco-700 hover:text-eco-800 font-medium transition-colors">--}}
{{--                    <span>Back to homepage</span>--}}
{{--                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>--}}
{{--                    </svg>--}}
{{--                </a>--}}
            </div>

            <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                <div class="hidden lg:flex flex-col justify-between bg-white/80 backdrop-blur border border-eco-100 rounded-3xl shadow-lg p-8">
                    <div>
                        <div class="inline-flex items-center px-3 py-1 bg-eco-100 text-eco-700 rounded-full text-xs font-semibold mb-6">Trusted waste partner</div>
                        <h1 class="text-3xl font-bold text-gray-900 leading-tight mb-4">Kelola penjemputan sampah lebih mudah dan tepat waktu.</h1>
                        <p class="text-gray-600 mb-8">Jadwalkan, pantau, dan terima notifikasi progres penjemputan dengan dashboard ramah lingkungan yang sudah Anda kenal.</p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-2xl bg-eco-50 text-eco-700 flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Terintegrasi dengan dashboard</p>
                                    <p class="text-gray-600 text-sm">Status rute, progres pengemudi, dan pembayaran tersinkron otomatis.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h11v12H3z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 7h3l4 4v6h-7z"></path>
                                        <circle cx="7.5" cy="17.5" r="1.5" fill="currentColor" />
                                        <circle cx="17" cy="17.5" r="1.5" fill="currentColor" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Notifikasi real-time</p>
                                    <p class="text-gray-600 text-sm">Pengingat jadwal dan update status langsung ke email dan aplikasi.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8a3 3 0 100 6 3 3 0 000-6z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6m0-16v2m8 6h-2M6 12H4m13.657-5.657l-1.414 1.414M6.757 17.243l-1.414 1.414m0-12.728l1.414 1.414m9.9 9.9l1.414 1.414"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Pembayaran aman</p>
                                    <p class="text-gray-600 text-sm">Invoice, langganan, dan riwayat pembayaran tercatat rapi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-8">
                        <div class="p-4 rounded-2xl bg-white border border-eco-100 text-center">
                            <p class="text-2xl font-bold text-gray-900">10K+</p>
                            <p class="text-xs text-gray-500">Pengguna aktif</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-eco-100 text-center">
                            <p class="text-2xl font-bold text-gray-900">98%</p>
                            <p class="text-xs text-gray-500">On-time pickup</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-eco-100 text-center">
                            <p class="text-2xl font-bold text-gray-900">4.9</p>
                            <p class="text-xs text-gray-500">User rating</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur-lg border border-gray-100 shadow-xl rounded-3xl p-6 sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua service type, key-nya pakai slug biar gampang dipanggil
        $serviceTypes = ServiceType::all()->keyBy('slug');

        // 1. Paket Sampah Umum (General Waste)
        // Pastikan slug 'sampah-umum' sesuai dengan ServiceTypeSeeder sebelumnya
        if (isset($serviceTypes['sampah-umum'])) {
            $this->createPlansForService($serviceTypes['sampah-umum'], [
                [
                    'name' => 'Basic Mingguan',
                    'slug' => 'sampah-mingguan',
                    'frequency_per_week' => 1,
                    'monthly_price' => 50000, // Rp 50.000 per bulan
                    'per_pickup_price' => 12500, // Jatuhnya Rp 12.500/jemput
                    'discount_percentage' => 17,
                    'description' => 'Cocok untuk rumah tangga kecil.',
                    'features' => json_encode([
                        'Jemput 1x seminggu',
                        'Maksimal 2 kantong per jemput',
                        'Notifikasi email',
                        'Jadwal fleksibel',
                    ]),
                    'is_popular' => false,
                ],
                [
                    'name' => 'Standar (2x Seminggu)',
                    'slug' => 'sampah-standard',
                    'frequency_per_week' => 2,
                    'monthly_price' => 90000, // Rp 90.000 per bulan
                    'per_pickup_price' => 11250,
                    'discount_percentage' => 25,
                    'description' => 'Paling populer untuk keluarga.',
                    'features' => json_encode([
                        'Jemput 2x seminggu',
                        'Maksimal 3 kantong per jemput',
                        'Notifikasi WA & Email',
                        'Prioritas penjemputan',
                        'Bebas atur ulang jadwal',
                    ]),
                    'is_popular' => true,
                ],
                [
                    'name' => 'Premium Harian',
                    'slug' => 'sampah-premium',
                    'frequency_per_week' => 5, // Senin - Jumat
                    'monthly_price' => 200000, // Rp 200.000 per bulan
                    'per_pickup_price' => 10000,
                    'discount_percentage' => 40,
                    'description' => 'Untuk bisnis atau rumah tangga besar.',
                    'features' => json_encode([
                        'Jemput Senin - Jumat',
                        'Tanpa batas jumlah kantong',
                        'Layanan Pelanggan Prioritas',
                        'Driver khusus langganan',
                        'Tracking Real-time',
                    ]),
                    'is_popular' => false,
                ],
            ]);
        }

        // 2. Paket Daur Ulang (Recyclables)
        if (isset($serviceTypes['recyclables'])) {
            $this->createPlansForService($serviceTypes['recyclables'], [
                [
                    'name' => 'Eco Mingguan',
                    'slug' => 'recycle-mingguan',
                    'frequency_per_week' => 1,
                    'monthly_price' => 35000, // Rp 35.000
                    'per_pickup_price' => 8750,
                    'discount_percentage' => 17,
                    'description' => 'Mulai kebiasaan daur ulangmu.',
                    'features' => json_encode([
                        'Jemput 1x seminggu',
                        'Gratis karung daur ulang',
                        'Panduan pemilahan sampah',
                    ]),
                    'is_popular' => false,
                ],
                [
                    'name' => 'Green Pro (2x Seminggu)',
                    'slug' => 'recycle-biweekly',
                    'frequency_per_week' => 2,
                    'monthly_price' => 60000, // Rp 60.000
                    'per_pickup_price' => 7500,
                    'discount_percentage' => 27,
                    'description' => 'Terbaik untuk pecinta lingkungan.',
                    'features' => json_encode([
                        'Jemput 2x seminggu',
                        'Bin daur ulang multiple',
                        'Laporan dampak lingkungan bulanan',
                        'Jadwal prioritas',
                    ]),
                    'is_popular' => true,
                ],
            ]);
        }

        // 3. Paket Organik (Organic Waste)
        if (isset($serviceTypes['organic-waste'])) {
            $this->createPlansForService($serviceTypes['organic-waste'], [
                [
                    'name' => 'Kompos Mingguan',
                    'slug' => 'organik-mingguan',
                    'frequency_per_week' => 1,
                    'monthly_price' => 40000, // Rp 40.000
                    'per_pickup_price' => 10000,
                    'discount_percentage' => 12,
                    'description' => 'Layanan kompos mingguan.',
                    'features' => json_encode([
                        'Jemput 1x seminggu',
                        'Gratis wadah kompos',
                        'Termasuk kantong biodegradable',
                    ]),
                    'is_popular' => false,
                ],
                [
                    'name' => 'Garden Pro',
                    'slug' => 'organik-pro',
                    'frequency_per_week' => 2,
                    'monthly_price' => 75000, // Rp 75.000
                    'per_pickup_price' => 9375,
                    'discount_percentage' => 25,
                    'description' => 'Untuk yang punya taman & dapur aktif.',
                    'features' => json_encode([
                        'Jemput 2x seminggu',
                        'Wadah kompos premium',
                        'Gratis pupuk kompos balikan',
                        'Newsletter tips berkebun',
                    ]),
                    'is_popular' => true,
                ],
            ]);
        }
    }

    private function createPlansForService(ServiceType $serviceType, array $plans): void
    {
        foreach ($plans as $plan) {
            SubscriptionPlan::create(array_merge($plan, [
                'service_type_id' => $serviceType->id,
                'is_active' => true,
            ]));
        }
    }
}

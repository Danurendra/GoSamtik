<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $serviceTypes = [
            [
                'name' => 'Sampah Umum',
                'slug' => 'sampah-umum',
                'description' => 'Sampah rumah tangga biasa dan bahan yang tidak dapat didaur ulang. Cocok untuk pembuangan sampah sehari-hari.',
                'base_price' => 15000,
                'icon' => 'trash',
                'color' => '#6b7280',
                'requirements' => json_encode(['Gunakan kantong plastik standar', 'Maks 5kg', 'Tidak ada limbah B3']),
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Recyclables',
                'slug' => 'recyclables',
                'description' => 'Kertas, kardus, plastik bersih, kaca, dan logam.',
                'base_price' => 10000,
                'icon' => 'recycle',
                'color' => '#22c55e',
                'requirements' => json_encode(['Bersih dan kering', 'Pisahkan sesuai jenis']),
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Sampah Organik',
                'slug' => 'organic-waste',
                'description' => 'Sisa makanan, limbah taman, dan bahan yang dapat dikomposkan. Ubah limbah Anda menjadi kompos kaya nutrisi.',
                'base_price' => 12000,
                'icon' => 'leaf',
                'color' => '#84cc16',
                'requirements' => [
                    'Gunakan kantong kompos',
                    'Tidak boleh plastik atau logam',
                    'Tidak boleh daging atau susu (opsional)',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Limbah Berbahaya',
                'slug' => 'hazardous-waste',
                'description' => 'Baterai, cat, bahan kimia, and elektronik. Pembuangan bahan berbahaya yang aman.',
                'base_price' => 35000,
                'icon' => 'warning',
                'color' => '#ef4444',
                'requirements' => [
                    'Gunakan wadah asli jika memungkinkan',
                    'Beri label pada semua bahan',
                    'Jadwalkan terlebih dahulu',
                    'Hadir saat pengambilan',
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Barang Berukuran Besar',
                'slug' => 'bulk-items',
                'description' => 'Furnitur, peralatan rumah tangga, kasur, dan barang besar lainnya. Kami yang menangani pengangkatan barang berat untuk Anda.',
                'base_price' => 50000,
                'icon' => 'box',
                'color' => '#8b5cf6',
                'requirements' => [
                    'Letakkan barang di pinggir jalan',
                    'Jadwalkan 48 jam sebelumnya',
                    'Maksimal 3 barang besar per pengambilan',
                    'Lepas pintu dari peralatan',
                ],
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($serviceTypes as $type) {
            ServiceType::create($type);
        }
    }
}

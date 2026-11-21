<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pengembangan', 'icon' => '💻', 'description' => 'Tugas terkait pengembangan aplikasi, coding, dan teknis', 'color' => '#3490dc'],
            ['name' => 'Meeting', 'icon' => '📅', 'description' => 'Rapat, diskusi tim, dan koordinasi', 'color' => '#f6993f'],
            ['name' => 'Laporan', 'icon' => '📊', 'description' => 'Pembuatan laporan, dokumentasi, dan administrasi', 'color' => '#38c172'],
            ['name' => 'Rutin', 'icon' => '🔄', 'description' => 'Tugas harian atau mingguan yang bersifat periodik', 'color' => '#e3342f'],
            ['name' => 'Urgent', 'icon' => '⚡', 'description' => 'Prioritas tinggi yang harus segera diselesaikan', 'color' => '#dc3545'],
            ['name' => 'Improvement', 'icon' => '📈', 'description' => 'Perbaikan sistem, optimasi, dan peningkatan kualitas', 'color' => '#6f42c1'],
            ['name' => 'Riset', 'icon' => '🔍', 'description' => 'Penelitian, eksplorasi teknologi, dan pembelajaran', 'color' => '#20c997'],
            ['name' => 'Support', 'icon' => '🎧', 'description' => 'Bantuan teknis, troubleshooting, dan layanan', 'color' => '#17a2b8'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        echo PHP_EOL . "8 kategori default berhasil dibuat." . PHP_EOL;
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $pos = function ($name, $desc, $level, $order) {
            return \App\Models\Position::create(['name' => $name, 'description' => $desc, 'level' => $level, 'sort_order' => $order]);
        };
        $ketua = $pos('Ketua Pengadilan', 'Jabatan tertinggi di lembaga pengadilan', 1, 1);
        $wakilKetua = $pos('Wakil Ketua Pengadilan', 'Wakil Ketua Pengadilan', 2, 2);
        $hakim = $pos('Hakim', 'Pelaksana kekuasaan kehakiman', 3, 3);
        $panitera = $pos('Panitera', 'Pembantu Ketua dalam administrasi perkara', 3, 4);
        $sekretaris = $pos('Sekretaris', 'Pembantu Ketua dalam administrasi umum', 3, 5);
        $paniteraMudaHukum = $pos('Panitera Muda Hukum', 'Bawah langsung Panitera', 4, 6);
        $paniteraMudaGugatan = $pos('Panitera Muda Gugatan', 'Bawah langsung Panitera', 4, 7);
        $paniteraMudaPermohonan = $pos('Panitera Muda Permohonan', 'Bawah langsung Panitera', 4, 8);
        $paniteraPengganti = $pos('Panitera Pengganti', 'Bawah langsung Panitera', 4, 9);
        $adminPos = $pos('Administrator', 'Super Administrator', 5, 10);
        $kasiPTIP = $pos('Kepala Sub Bagian PTIP', 'Bawah langsung Sekretaris', 5, 11);
        $kasiKepegawaian = $pos('Kepala Sub Bagian Kepegawaian', 'Bawah langsung Sekretaris', 5, 12);
        $kasiUmum = $pos('Kepala Sub Bagian Umum dan Keuangan', 'Bawah langsung Sekretaris', 5, 13);

        // Hierarchy: many-to-many parents
        $wakilKetua->parents()->sync([$ketua->id]);
        $hakim->parents()->sync([$ketua->id, $wakilKetua->id]);
        $panitera->parents()->sync([$ketua->id, $wakilKetua->id]);
        $sekretaris->parents()->sync([$ketua->id, $wakilKetua->id]);
        $paniteraMudaHukum->parents()->sync([$panitera->id]);
        $paniteraMudaGugatan->parents()->sync([$panitera->id]);
        $paniteraMudaPermohonan->parents()->sync([$panitera->id]);
        $paniteraPengganti->parents()->sync([$panitera->id]);
        $kasiPTIP->parents()->sync([$sekretaris->id]);
        $kasiKepegawaian->parents()->sync([$sekretaris->id]);
        $kasiUmum->parents()->sync([$sekretaris->id]);

        \App\Models\Employee::create([
            'nip' => 'ADM001',
            'name' => 'Bambang',
            'front_title' => null,
            'back_title' => 'S.Kom.',
            'nik' => '3273010101900001',
            'email' => 'bambang@company.com',
            'password' => bcrypt('admin123'),
            'religion' => 'Islam',
            'marital_status' => 'Kawin',
            'education' => 'STRATA I',
            'address' => 'Jl. Contoh No. 1, Jakarta',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'gender' => 'Laki-laki',
            'position' => 'Super Administrator',
            'position_id' => $adminPos->id,
            'group_rank' => 'IV/A - Pembina',
            'group_start_date' => '2020-01-01',
            'position_start_date' => '2024-01-01',
            'phone' => '081234567890',
            'superior_id' => null,
        ]);

        $anto = \App\Models\Employee::create([
            'nip' => 'KEP002',
            'name' => 'Anto',
            'front_title' => null,
            'back_title' => 'S.T.',
            'nik' => '3273010101900002',
            'email' => 'anto@company.com',
            'password' => bcrypt('password123'),
            'religion' => 'Islam',
            'marital_status' => 'Kawin',
            'education' => 'STRATA I',
            'address' => 'Jl. Merdeka No. 10, Bandung',
            'birth_place' => 'Bandung',
            'birth_date' => '1985-05-15',
            'gender' => 'Laki-laki',
            'position' => 'Ketua Pengadilan',
            'position_id' => $ketua->id,
            'group_rank' => 'III/D - Penata Tingkat I',
            'group_start_date' => '2018-06-01',
            'position_start_date' => '2023-03-01',
            'phone' => '081234567891',
            'superior_id' => null,
        ]);

        \App\Models\Employee::create([
            'nip' => 'STF003',
            'name' => 'Budi',
            'front_title' => null,
            'back_title' => 'A.Md.',
            'nik' => '3273010101900003',
            'email' => 'budi@company.com',
            'password' => bcrypt('password123'),
            'religion' => 'Islam',
            'marital_status' => 'Tidak Kawin',
            'education' => 'DIPLOMA III',
            'address' => 'Jl. Kencana No. 5, Bandung',
            'birth_place' => 'Bandung',
            'birth_date' => '1995-08-20',
            'gender' => 'Laki-laki',
            'position' => 'Hakim',
            'position_id' => $hakim->id,
            'group_rank' => 'II/C - Pengatur',
            'group_start_date' => '2022-01-01',
            'position_start_date' => '2022-01-01',
            'phone' => '081234567892',
            'superior_id' => $ketua->id,
        ]);

        \App\Models\Employee::create([
            'nip' => 'STF004',
            'name' => 'Siti',
            'front_title' => null,
            'back_title' => 'S.E.',
            'nik' => '3273010101900004',
            'email' => 'siti@company.com',
            'password' => bcrypt('password123'),
            'religion' => 'Islam',
            'marital_status' => 'Kawin',
            'education' => 'STRATA I',
            'address' => 'Jl. Melati No. 3, Bandung',
            'birth_place' => 'Jakarta',
            'birth_date' => '1992-12-10',
            'gender' => 'Perempuan',
            'position' => 'Panitera Muda Gugatan',
            'position_id' => $paniteraMudaGugatan->id,
            'group_rank' => 'II/D - Pengatur Tingkat I',
            'group_start_date' => '2023-01-01',
            'position_start_date' => '2023-01-01',
            'phone' => '081234567893',
            'superior_id' => $ketua->id,
        ]);

        $manajer = \App\Models\Employee::create([
            'nip' => 'MGR005',
            'name' => 'Rahmat',
            'front_title' => null,
            'back_title' => 'S.T., M.T.',
            'nik' => '3273010101900005',
            'email' => 'rahmat@company.com',
            'password' => bcrypt('password123'),
            'religion' => 'Islam',
            'marital_status' => 'Kawin',
            'education' => 'PASCASARJANA',
            'address' => 'Perumahan Griya Indah Blok A.1, Surabaya',
            'birth_place' => 'Surabaya',
            'birth_date' => '1982-03-25',
            'gender' => 'Laki-laki',
            'position' => 'Wakil Ketua Pengadilan',
            'position_id' => $wakilKetua->id,
            'group_rank' => 'IV/A - Pembina',
            'group_start_date' => '2019-01-01',
            'position_start_date' => '2024-06-01',
            'phone' => '081234567894',
            'superior_id' => null,
        ]);

        \App\Models\Employee::create([
            'nip' => 'SPV006',
            'name' => 'Dewi',
            'front_title' => null,
            'back_title' => 'S.Kom.',
            'nik' => '3273010101900006',
            'email' => 'dewi@company.com',
            'password' => bcrypt('password123'),
            'religion' => 'Kristen Protestan',
            'marital_status' => 'Kawin',
            'education' => 'STRATA I',
            'address' => 'Jl. Anggrek No. 7, Surabaya',
            'birth_place' => 'Malang',
            'birth_date' => '1988-07-14',
            'gender' => 'Perempuan',
            'position' => 'Sekretaris',
            'position_id' => $sekretaris->id,
            'group_rank' => 'III/C - Penata',
            'group_start_date' => '2021-01-01',
            'position_start_date' => '2023-06-01',
            'phone' => '081234567895',
            'superior_id' => $wakilKetua->id,
        ]);

        echo PHP_EOL . "Seeder berhasil! Data pegawai dan jabatan telah dibuat." . PHP_EOL;
        echo "Login pegawai: NIP / password123" . PHP_EOL;
        echo "Login admin: username 'admin' / password 'admin123'" . PHP_EOL;
    }
}

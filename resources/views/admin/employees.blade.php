@php
    $title = 'Kelola Pegawai';
    $religions = ['Islam', 'Protestan', 'Katolik', 'Hindu', 'Budha', 'Kong Hu Chu'];
    $statuses = ['Kawin', 'Tidak Kawin'];
    $educations = ['SD', 'SLTP', 'SLTA', 'DIPLOMA I', 'DIPLOMA II', 'DIPLOMA III', 'DIPLOMA IV', 'STRATA I', 'PASCASARJANA', 'DOCTOR', 'PROFESI', 'SPESIALIS', 'DLL'];
    $genders = ['Laki-laki', 'Perempuan'];
    $groupRanks = [
        'I/A - Juru Muda', 'I/B - Juru Muda Tingkat I', 'I/C - Juru', 'I/D - Juru Tingkat I',
        'II/A - Pengatur Muda', 'II/B - Pengatur Muda Tingkat I', 'II/C - Pengatur', 'II/D - Pengatur Tingkat I',
        'III/A - Penata Muda', 'III/B - Penata Muda Tingkat I', 'III/C - Penata', 'III/D - Penata Tingkat I',
        'IV/A - Pembina', 'IV/B - Pembina Tingkat I', 'IV/C - Pembina Utama Muda', 'IV/D - Pembina Utama Madya', 'IV/E - Pembina Utama',
    ];
@endphp

@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    border: 1px solid rgb(203 213 225);
    border-radius: 0.5rem;
    padding: 0.375rem 0.75rem;
    height: auto;
    font-size: 0.875rem;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 6px;
    right: 6px;
}
</style>
<div class="space-y-6">
    @if(session('success'))
        <script>$(function(){Swal.fire({icon:'success',title:'Berhasil',text:{!! json_encode(session('success')) !!},timer:3000,showConfirmButton:false});});</script>
    @endif
    @if(session('error'))
        <script>$(function(){Swal.fire({icon:'error',title:'Gagal',text:{!! json_encode(session('error')) !!},timer:3000,showConfirmButton:false});});</script>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">Kelola Pegawai</h2>
            <button onclick="openModal('addEmployeeModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="data-table w-full text-sm border-collapse border border-slate-200">
                <thead>
                    <tr class="text-left text-slate-500 bg-slate-50">
                        <th class="px-4 py-3 font-medium border border-slate-200 w-10">No.</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">NIP</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Nama Lengkap</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Jabatan</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Golongan</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Foto</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 text-center border border-slate-200 text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-mono text-xs border border-slate-200">{{ $emp->nip ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium border border-slate-200">
                                {{ $emp->full_name }}
                            </td>
                            <td class="px-4 py-3 border border-slate-200">{{ $emp->positionData?->name ?? $emp->position ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs border border-slate-200">{{ $emp->group_rank ?? '-' }}</td>
                            <td class="px-4 py-3 border border-slate-200 text-center">
                                @if($emp->photo)
                                    <img src="{{ asset('storage/' . $emp->photo) }}" alt="foto" class="w-10 h-10 rounded-full object-cover mx-auto border border-slate-200">
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-slate-200">
                                <div class="flex gap-2">
                                    <button onclick='editEmployee(@json($emp))' class="px-3 py-1 text-xs bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    @if(true)
                                        <form method="POST" action="{{ route('admin.employees.destroy', $emp) }}" class="confirm-delete" data-name="pegawai {{ $emp->name }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-xs bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="odd:bg-white even:bg-slate-50">
                            <td class="px-4 py-8 text-center text-slate-400 border border-slate-200" colspan="7">Belum ada pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div id="addEmployeeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('addEmployeeModal')"></div>
    <div class="relative min-h-screen flex items-start justify-center p-4 pt-8">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-6 my-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Tambah Pegawai</h3>
                <button onclick="closeModal('addEmployeeModal')" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">NIP</label>
                        <input type="text" name="nip" id="add_nip" onkeyup="checkNip(this.value)" autocomplete="off" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p id="nip-status" class="text-xs mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">NIK</label>
                        <input type="text" name="nik" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Depan</label>
                        <input type="text" name="front_title" placeholder="Dr." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Belakang</label>
                        <input type="text" name="back_title" placeholder="S.Kom." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($genders as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="birth_place" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Agama</label>
                        <select name="religion" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($religions as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="marital_status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pendidikan</label>
                        <select name="education" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($educations as $e)
                                <option value="{{ $e }}">{{ $e }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">HP</label>
                        <input type="text" name="phone" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <hr class="border-slate-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                        <select name="position_id" class="select2 w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($positions as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">TMT Jabatan</label>
                        <input type="date" name="position_start_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Golongan/Ruang</label>
                        <select name="group_rank" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($groupRanks as $gr)
                                <option value="{{ $gr }}">{{ $gr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">TMT Golongan</label>
                        <input type="date" name="group_start_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Foto Profil</label>
                        <div class="flex items-center gap-3">
                            <input type="file" name="photo" accept="image/jpg,image/jpeg,image/png" onchange="previewFoto(this, 'preview_add')" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <img id="preview_add" class="w-12 h-12 rounded-full object-cover hidden border border-slate-200">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('addEmployeeModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editEmployeeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('editEmployeeModal')"></div>
    <div class="relative min-h-screen flex items-start justify-center p-4 pt-8">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-6 my-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Edit Pegawai</h3>
                <button onclick="closeModal('editEmployeeModal')" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" class="space-y-4" id="editEmployeeForm">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">NIP</label>
                        <input type="text" name="nip" id="edit_nip" onkeyup="checkEditNip(this.value)" autocomplete="off" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p id="edit-nip-status" class="text-xs mt-1"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">NIK</label>
                        <input type="text" name="nik" id="edit_nik" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Depan</label>
                        <input type="text" name="front_title" id="edit_front_title" placeholder="Dr." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Belakang</label>
                        <input type="text" name="back_title" id="edit_back_title" placeholder="S.Kom." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" id="edit_gender" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($genders as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="birth_place" id="edit_birth_place" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="edit_birth_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Agama</label>
                        <select name="religion" id="edit_religion" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($religions as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="marital_status" id="edit_marital_status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($statuses as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pendidikan</label>
                        <select name="education" id="edit_education" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($educations as $e)
                                <option value="{{ $e }}">{{ $e }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">HP</label>
                        <input type="text" name="phone" id="edit_phone" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                    <textarea name="address" id="edit_address" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <hr class="border-slate-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                        <select name="position_id" id="edit_position_id" class="select2 w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($positions as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">TMT Jabatan</label>
                        <input type="date" name="position_start_date" id="edit_position_start_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Golongan/Ruang</label>
                        <select name="group_rank" id="edit_group_rank" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih --</option>
                            @foreach($groupRanks as $gr)
                                <option value="{{ $gr }}">{{ $gr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">TMT Golongan</label>
                        <input type="date" name="group_start_date" id="edit_group_start_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Foto Profil</label>
                        <div class="flex items-center gap-3">
                            <input type="file" name="photo" accept="image/jpg,image/jpeg,image/png" onchange="previewFoto(this, 'preview_edit')" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <img id="preview_edit" class="w-12 h-12 rounded-full object-cover hidden border border-slate-200">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-slate-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" minlength="8" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('editEmployeeModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
function previewFoto(input, id) {
    var img = document.getElementById(id);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { img.src = e.target.result; img.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}
function setEmployeeFormDisabled(disabled) {
    var form = document.querySelector('#addEmployeeModal form');
    if (!form) return;
    form.querySelectorAll('input, select, textarea, button[type="submit"]').forEach(function(el) {
        el.disabled = disabled;
    });
}
function setEditEmployeeFormDisabled(disabled) {
    var form = document.querySelector('#editEmployeeModal form');
    if (!form) return;
    form.querySelectorAll('input, select, textarea, button[type="submit"]').forEach(function(el) {
        el.disabled = disabled;
    });
}
function checkNip(val) {
    var statusEl = document.getElementById('nip-status');
    var inputEl = document.getElementById('add_nip');
    if (val.length < 2) {
        statusEl.innerHTML = '';
        inputEl.classList.remove('border-red-500', 'border-green-500');
        setEmployeeFormDisabled(false);
        return;
    }
    fetch('{{ route("admin.employees.check-nip") }}?nip=' + encodeURIComponent(val))
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.available) {
                statusEl.innerHTML = '<span class="text-green-500">NIP tersedia</span>';
                inputEl.classList.remove('border-red-500');
                inputEl.classList.add('border-green-500');
                setEmployeeFormDisabled(false);
            } else {
                statusEl.innerHTML = '<span class="text-red-500">NIP sudah digunakan</span>';
                inputEl.classList.remove('border-green-500');
                inputEl.classList.add('border-red-500');
                setEmployeeFormDisabled(true);
            }
        });
}
function checkEditNip(val) {
    var statusEl = document.getElementById('edit-nip-status');
    var inputEl = document.getElementById('edit_nip');
    var empId = inputEl.getAttribute('data-emp-id');
    if (val.length < 2) {
        statusEl.innerHTML = '';
        inputEl.classList.remove('border-red-500', 'border-green-500');
        setEditEmployeeFormDisabled(false);
        return;
    }
    var url = '{{ route("admin.employees.check-nip") }}?nip=' + encodeURIComponent(val);
    if (empId) url += '&exclude_id=' + empId;
    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.available) {
                statusEl.innerHTML = '<span class="text-green-500">NIP tersedia</span>';
                inputEl.classList.remove('border-red-500');
                inputEl.classList.add('border-green-500');
                setEditEmployeeFormDisabled(false);
            } else {
                statusEl.innerHTML = '<span class="text-red-500">NIP sudah digunakan</span>';
                inputEl.classList.remove('border-green-500');
                inputEl.classList.add('border-red-500');
                setEditEmployeeFormDisabled(true);
            }
        });
}
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    $(document.getElementById(id)).find('.select2').select2({
        placeholder: '-- Pilih --',
        allowClear: true,
        width: '100%'
    });
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
    $(document.getElementById(id)).find('.select2').select2('destroy');
    setEmployeeFormDisabled(false);
    setEditEmployeeFormDisabled(false);
}
function editEmployee(emp) {
    var nipInput = document.getElementById('edit_nip');
    nipInput.value = emp.nip || '';
    nipInput.setAttribute('data-emp-id', emp.id);
    document.getElementById('edit_name').value = emp.name || '';
    document.getElementById('edit_front_title').value = emp.front_title || '';
    document.getElementById('edit_back_title').value = emp.back_title || '';
    document.getElementById('edit_nik').value = emp.nik || '';
    document.getElementById('edit_email').value = emp.email || '';
    document.getElementById('edit_religion').value = emp.religion || '';
    document.getElementById('edit_marital_status').value = emp.marital_status || '';
    document.getElementById('edit_education').value = emp.education || '';
    document.getElementById('edit_address').value = emp.address || '';
    document.getElementById('edit_birth_place').value = emp.birth_place || '';
    document.getElementById('edit_birth_date').value = emp.birth_date ? emp.birth_date.substring(0, 10) : '';
    document.getElementById('edit_gender').value = emp.gender || '';
    document.getElementById('edit_position_id').value = emp.position_id || '';
    document.getElementById('edit_group_rank').value = emp.group_rank || '';
    document.getElementById('edit_group_start_date').value = emp.group_start_date ? emp.group_start_date.substring(0, 10) : '';
    document.getElementById('edit_position_start_date').value = emp.position_start_date ? emp.position_start_date.substring(0, 10) : '';
    document.getElementById('edit_phone').value = emp.phone || '';
    document.getElementById('edit-nip-status').innerHTML = '';
    nipInput.classList.remove('border-red-500', 'border-green-500');
    var preview = document.getElementById('preview_edit');
    if (emp.photo) {
        preview.src = '{{ asset("storage") }}' + '/' + emp.photo;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
    document.getElementById('editEmployeeForm').action = '{{ route("admin.employees.update", ["employee" => "__ID__"]) }}'.replace('__ID__', emp.id);
    openModal('editEmployeeModal');
}
</script>
@endsection
@php
    $title = 'Kelola Admin';
@endphp

@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <script>$(function(){Swal.fire({icon:'success',title:'Berhasil',text:{!! json_encode(session('success')) !!},timer:3000,showConfirmButton:false});});</script>
    @endif
    @if(session('error'))
        <script>$(function(){Swal.fire({icon:'error',title:'Gagal',text:{!! json_encode(session('error')) !!},timer:3000,showConfirmButton:false});});</script>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">Kelola Admin</h2>
            <button onclick="openModal('addAdminModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="data-table w-full text-sm border-collapse border border-slate-200">
                <thead>
                    <tr class="text-left text-slate-500 bg-slate-50">
                        <th class="px-4 py-3 font-medium border border-slate-200 w-10">No.</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Username</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Nama</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $adm)
                        <tr class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 text-center border border-slate-200 text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-mono text-xs border border-slate-200">{{ $adm->username }}</td>
                            <td class="px-4 py-3 font-medium border border-slate-200">{{ $adm->name }}</td>
                            <td class="px-4 py-3 border border-slate-200">
                                <div class="flex gap-2">
                                    <button onclick='editAdmin(@json($adm))' class="px-3 py-1 text-xs bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    @if($adm->id !== Auth::guard('admin')->id())
                                        <form method="POST" action="{{ route('admin.admins.destroy', $adm) }}" class="confirm-delete" data-name="admin {{ $adm->name }}">
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
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400 border border-slate-200">Belum ada admin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div id="addAdminModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('addAdminModal')"></div>
    <div class="relative min-h-screen flex items-start justify-center p-4 pt-8">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 my-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Tambah Admin</h3>
                <button onclick="closeModal('addAdminModal')" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="add_username" value="{{ old('username') }}" required onkeyup="checkUsername(this.value)" autocomplete="off" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('username') ? 'border-red-500' : '' }}">
                    <p id="username-status" class="text-xs mt-1"></p>
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('name') ? 'border-red-500' : '' }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('addAdminModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div id="editAdminModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('editAdminModal')"></div>
    <div class="relative min-h-screen flex items-start justify-center p-4 pt-8">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 my-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Edit Admin</h3>
                <button onclick="closeModal('editAdminModal')" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            <form method="POST" action="" class="space-y-4" id="editAdminForm">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="edit_username" required onkeyup="checkEditUsername(this.value)" autocomplete="off" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p id="edit-username-status" class="text-xs mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-slate-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" minlength="8" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('editAdminModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
    setFormDisabled(false);
    setEditFormDisabled(false);
}
function editAdmin(adm) {
    var eu = document.getElementById('edit_username');
    eu.value = adm.username;
    eu.setAttribute('data-admin-id', adm.id);
    document.getElementById('edit_name').value = adm.name;
    document.getElementById('editAdminForm').action = '{{ route("admin.admins.update", ["admin" => "__ID__"]) }}'.replace('__ID__', adm.id);
    document.getElementById('edit-username-status').innerHTML = '';
    eu.classList.remove('border-red-500', 'border-green-500');
    openModal('editAdminModal');
}
function setFormDisabled(disabled) {
    var form = document.querySelector('#addAdminModal form');
    if (!form) return;
    form.querySelectorAll('input, select, textarea, button[type="submit"]').forEach(function(el) {
        el.disabled = disabled;
    });
}
function checkUsername(val) {
    var statusEl = document.getElementById('username-status');
    var inputEl = document.getElementById('add_username');
    if (val.length < 2) {
        statusEl.innerHTML = '';
        inputEl.classList.remove('border-red-500', 'border-green-500');
        setFormDisabled(false);
        return;
    }
    fetch('{{ route("admin.admins.check-username") }}?username=' + encodeURIComponent(val))
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.available) {
                statusEl.innerHTML = '<span class="text-green-500">Username tersedia</span>';
                inputEl.classList.remove('border-red-500');
                inputEl.classList.add('border-green-500');
                setFormDisabled(false);
            } else {
                statusEl.innerHTML = '<span class="text-red-500">Username sudah digunakan</span>';
                inputEl.classList.remove('border-green-500');
                inputEl.classList.add('border-red-500');
                setFormDisabled(true);
            }
        });
}
function setEditFormDisabled(disabled) {
    var form = document.querySelector('#editAdminModal form');
    if (!form) return;
    form.querySelectorAll('input, select, textarea, button[type="submit"]').forEach(function(el) {
        el.disabled = disabled;
    });
}
function checkEditUsername(val) {
    var statusEl = document.getElementById('edit-username-status');
    var inputEl = document.getElementById('edit_username');
    var adminId = inputEl.getAttribute('data-admin-id');
    if (val.length < 2) {
        statusEl.innerHTML = '';
        inputEl.classList.remove('border-red-500', 'border-green-500');
        setEditFormDisabled(false);
        return;
    }
    var url = '{{ route("admin.admins.check-username") }}?username=' + encodeURIComponent(val);
    if (adminId) url += '&exclude_id=' + adminId;
    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.available) {
                statusEl.innerHTML = '<span class="text-green-500">Username tersedia</span>';
                inputEl.classList.remove('border-red-500');
                inputEl.classList.add('border-green-500');
                setEditFormDisabled(false);
            } else {
                statusEl.innerHTML = '<span class="text-red-500">Username sudah digunakan</span>';
                inputEl.classList.remove('border-green-500');
                inputEl.classList.add('border-red-500');
                setEditFormDisabled(true);
            }
        });
}
@if($errors->has('username') || $errors->has('name') || $errors->has('password'))
document.addEventListener('DOMContentLoaded', function() { openModal('addAdminModal'); });
@endif
</script>
@endsection

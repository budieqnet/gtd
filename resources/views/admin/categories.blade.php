@php
    $title = 'Kelola Kategori';
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
            <h2 class="text-xl font-bold text-slate-800">Kelola Kategori</h2>
            <button onclick="openModal('addCategoryModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="data-table w-full text-sm border-collapse border border-slate-200">
                <thead>
                    <tr class="text-left text-slate-500 bg-slate-50">
                        <th class="px-4 py-3 font-medium border border-slate-200">Warna</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Ikon</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Nama</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Deskripsi</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Jumlah Tugas</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 border border-slate-200">
                                <div class="w-6 h-6 rounded-full border border-slate-300" style="background-color: {{ $cat->color }}"></div>
                            </td>
                            <td class="px-4 py-3 text-xl border border-slate-200">{{ $cat->icon ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium border border-slate-200">{{ $cat->name }}</td>
                            <td class="px-4 py-3 text-slate-500 max-w-xs truncate border border-slate-200">{{ $cat->description ?? '-' }}</td>
                            <td class="px-4 py-3 border border-slate-200">
                                <span class="px-2 py-1 text-xs rounded-full {{ $cat->tasks_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $cat->tasks_count }} tugas
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-slate-200">
                                <div class="flex gap-2">
                                    <button onclick='editCategory(@json($cat))' class="px-3 py-1 text-xs bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="confirm-delete" data-name="kategori {{ $cat->name }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition {{ $cat->tasks_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $cat->tasks_count > 0 ? 'disabled title=Kategori masih memiliki tugas' : '' }}>
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 border border-slate-200">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('addCategoryModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Tambah Kategori</h3>
                <button onclick="closeModal('addCategoryModal')" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Pengembangan" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ikon (Emoji)</label>
                        <input type="text" name="icon" placeholder="💻" maxlength="10" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Warna</label>
                        <input type="color" name="color" value="#3490dc" class="w-full h-10 px-1 py-1 border border-slate-300 rounded-lg cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan kategori ini digunakan untuk apa..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('addCategoryModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('editCategoryModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Edit Kategori</h3>
                <button onclick="closeModal('editCategoryModal')" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            <form method="POST" action="" class="space-y-4" id="editCategoryForm">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_cat_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ikon (Emoji)</label>
                        <input type="text" name="icon" id="edit_cat_icon" maxlength="10" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Warna</label>
                        <input type="color" name="color" id="edit_cat_color" class="w-full h-10 px-1 py-1 border border-slate-300 rounded-lg cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="edit_cat_description" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('editCategoryModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
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
}
function editCategory(cat) {
    document.getElementById('edit_cat_name').value = cat.name;
    document.getElementById('edit_cat_icon').value = cat.icon || '';
    document.getElementById('edit_cat_description').value = cat.description || '';
    document.getElementById('edit_cat_color').value = cat.color;
    document.getElementById('editCategoryForm').action = '{{ route("admin.categories.update", ["category" => "__ID__"]) }}'.replace('__ID__', cat.id);
    openModal('editCategoryModal');
}
</script>
@endsection
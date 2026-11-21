@php
    $title = 'Kelola Jabatan';
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
            <h2 class="text-xl font-bold text-slate-800">Kelola Jabatan</h2>
            <button onclick="openModal('addPositionModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="p-4 overflow-x-auto">
            <table id="positions-table" class="w-full text-sm border-collapse border border-slate-200">
                <thead>
                    <tr class="text-left text-slate-500 bg-slate-50">
                        <th class="px-4 py-3 font-medium w-10 border border-slate-200"></th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Level</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Nama Jabatan</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Bawahan dari</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Jumlah Pegawai</th>
                        <th class="px-4 py-3 font-medium border border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody id="positions-sortable">
                    @forelse($positions as $pos)
                        <tr data-id="{{ $pos->id }}" class="odd:bg-white even:bg-slate-50 hover:bg-slate-100 transition">
                            <td class="px-4 py-3 text-slate-400 cursor-grab handle text-center border border-slate-200">
                                <i class="fa-solid fa-grip-vertical"></i>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold border border-slate-200">{{ $pos->level ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium border border-slate-200">{{ $pos->name }}</td>
                            <td class="px-4 py-3 text-slate-500 border border-slate-200">
                                @if($pos->parents->isNotEmpty())
                                    {{ $pos->parents->pluck('name')->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-slate-200">{{ $pos->employees_count }} pegawai</td>
                            <td class="px-4 py-3 border border-slate-200">
                                <div class="flex gap-2">
                                    <button onclick="editPosition({{ $pos->id }}, '{{ $pos->name }}', '{{ $pos->description ?? '' }}', {{ $pos->level ?? 'null' }}, {{ json_encode($pos->parents->pluck('id')) }})" class="px-3 py-1 text-xs bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.positions.destroy', $pos) }}" class="confirm-delete" data-name="jabatan {{ $pos->name }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400 border border-slate-200">Belum ada jabatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Position Modal -->
<div id="addPositionModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('addPositionModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Tambah Jabatan</h3>
                <button onclick="closeModal('addPositionModal')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.positions.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jabatan</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Level Hirarki</label>
                    <input type="number" name="level" min="1" placeholder="1 = tertinggi" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bawahan dari <span class="text-xs text-slate-400">(bisa pilih banyak)</span></label>
                    <select name="parent_ids[]" multiple class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" size="5">
                        @foreach($allPositions as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Level {{ $p->level ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <input type="text" name="description" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('addPositionModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Position Modal -->
<div id="editPositionModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('editPositionModal')"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Edit Jabatan</h3>
                <button onclick="closeModal('editPositionModal')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form method="POST" action="" class="space-y-4" id="editPositionForm">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Jabatan</label>
                    <input type="text" name="name" id="edit_pos_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Level Hirarki</label>
                    <input type="number" name="level" id="edit_pos_level" min="1" placeholder="1 = tertinggi" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bawahan dari <span class="text-xs text-slate-400">(bisa pilih banyak)</span></label>
                    <select name="parent_ids[]" id="edit_pos_parent" multiple class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" size="5">
                        @foreach($allPositions as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Level {{ $p->level ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <input type="text" name="description" id="edit_pos_description" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeModal('editPositionModal')" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function editPosition(id, name, description, level, parentIds) {
    document.getElementById('edit_pos_name').value = name;
    document.getElementById('edit_pos_description').value = description;
    document.getElementById('edit_pos_level').value = level || '';
    var sel = document.getElementById('edit_pos_parent');
    Array.from(sel.options).forEach(function(opt) {
        opt.selected = parentIds.includes(parseInt(opt.value));
    });
    document.getElementById('editPositionForm').action = '{{ route("admin.positions.update", ["position" => "__ID__"]) }}'.replace('__ID__', id);
    openModal('editPositionModal');
}
</script>

@push('scripts')
<script>
$(document).ready(function() {
    $('#positions-table').DataTable({
        ordering: false,
        paging: false,
        info: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        initComplete: function() {
            new Sortable(document.getElementById('positions-sortable'), {
                handle: '.handle',
                animation: 150,
                onEnd: function() {
                    let ids = [];
                    document.querySelectorAll('#positions-sortable tr').forEach(function(row) {
                        ids.push(row.dataset.id);
                    });
                    fetch('{{ route("admin.positions.sort") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ ids: ids })
                    });
                }
            });
        }
    });
});
</script>
@endpush
@endsection
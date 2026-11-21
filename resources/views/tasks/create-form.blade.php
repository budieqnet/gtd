@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    border: 2px solid rgb(203 213 225);
    border-radius: 0.5rem;
    padding: 0.75rem;
    height: auto;
    font-size: 1rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5rem;
    padding-left: 0;
    color: rgb(51 65 85);
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 50%;
    transform: translateY(-50%);
    right: 8px;
}
.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: rgb(59 130 246);
    box-shadow: 0 0 0 2px rgb(219 234 254);
}
.select2-dropdown {
    border: 1px solid rgb(203 213 225);
    border-radius: 0.5rem;
    overflow: hidden;
}
.select2-search--dropdown .select2-search__field {
    border: 1px solid rgb(203 213 225);
    border-radius: 0.375rem;
    padding: 0.375rem 0.5rem;
}
</style>
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800">📋 Buat Tugas Baru</h1>
        <p class="text-slate-600 mt-2">Buat tugas untuk diri sendiri atau assign ke bawahan.</p>
    </div>

    @if(session('error'))
        <script>$(function(){Swal.fire({icon:'error',title:'Gagal',text:{!! json_encode(session('error')) !!},timer:3000,showConfirmButton:false});});</script>
    @endif

    <form action="{{ route('tasks.store') }}" method="POST" class="bg-white shadow-lg rounded-xl p-6 md:p-8">
        @csrf
        
        @if($employeeList->isNotEmpty())
        <div class="mb-6">
            <label for="assigned_to" class="block text-lg font-semibold text-slate-800 mb-2">
                🎯 Tugas Untuk:
            </label>
            <select id="assigned_to" name="assigned_to"
                    class="select2 w-full p-3 border-2 border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">
                <option value="">Diri Sendiri</option>
                @foreach($employeeList as $emp)
                    <option value="{{ $emp->id }}">
                        {{ $emp->full_name }} - {{ $emp->positionData?->name ?? $emp->position ?? '-' }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-400 mt-1">Ketik nama pegawai untuk mencari.</p>
        </div>
        @endif

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-slate-700 mb-1">Judul Tugas <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" required 
                   placeholder="Contoh: Update laporan harian server"
                   class="w-full p-3 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Tugas</label>
            <textarea id="description" name="description" rows="4" 
                      placeholder="Jelaskan detail tugas yang harus dikerjakan..."
                      class="w-full p-3 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('description') border-red-500 @enderror"></textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
            <select id="category_id" name="category_id" required 
                    class="w-full p-3 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('category_id') border-red-500 @enderror">
                <option value="">Pilih Kategori</option>
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->icon ?? '' }} {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="priority" class="block text-sm font-medium text-slate-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
                <select id="priority" name="priority" required 
                        class="w-full p-3 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('priority') border-red-500 @enderror">
                    <option value="Low">🟢 Low</option>
                    <option value="Medium" selected>🟡 Medium</option>
                    <option value="High">🔴 High</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status Awal <span class="text-red-500">*</span></label>
                <select id="status" name="status" required 
                        class="w-full p-3 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="Inbox" selected>Inbox</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Done">Done</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label for="due_date" class="block text-sm font-medium text-slate-700 mb-1">Tenggat (Opsional)</label>
            <input type="date" id="due_date" name="due_date" 
                   min="{{ date('Y-m-d') }}"
                   class="w-full p-3 border border-slate-300 rounded-lg outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('due_date') border-red-500 @enderror">
        </div>

        <div class="flex gap-4">
            <button type="submit" 
                    class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 rounded-lg transition shadow-lg flex items-center justify-center">
                <i class="fa-solid fa-paper-plane mr-2"></i> Simpan Tugas 📧
            </button>
            <a href="{{ route('tasks.index') }}" 
               class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition">
                Batal ✕
            </a>
        </div>

    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
    $('#assigned_to').select2({
        width: '100%',
        language: {
            noResults: function () { return 'Pegawai tidak ditemukan'; },
            searching: function () { return 'Mencari...'; }
        }
    });
});
</script>
@endpush
@endsection

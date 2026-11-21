@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 lg:px-6">
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tugas
        </a>
        <span class="text-xs px-2 py-1 rounded-full bg-slate-100 border border-slate-300 text-slate-500">#{{ $task->id }}</span>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 md:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                @php
                    $statusColors = [
                        'Inbox' => 'bg-slate-200 text-slate-700',
                        'In Progress' => 'bg-blue-100 text-blue-700',
                        'Done' => 'bg-green-100 text-green-700',
                    ];
                    $priorityText = ['High' => 'text-red-600', 'Medium' => 'text-orange-600', 'Low' => 'text-green-600'];
                @endphp
                <span class="text-xs uppercase tracking-wider font-bold px-3 py-1 rounded-full {{ $statusColors[$task->status] }}">
                    {{ $task->status }}
                </span>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium {{ $priorityText[$task->priority] }}">
                        <i class="fa-solid fa-circle text-[6px] mr-1"></i> {{ $task->priority }}
                    </span>
                    @if($task->due_date)
                        <span class="text-xs px-3 py-1 rounded-full border font-medium {{ $task->due_date->isPast() && $task->status != 'Done' ? 'bg-red-50 border-red-200 text-red-600' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                            <i class="fa-regular fa-calendar mr-1"></i> {{ $task->due_date->format('d M Y') }}
                            @if($task->due_date->isPast() && $task->status != 'Done') <i class="fa-solid fa-triangle-exclamation"></i> @endif
                        </span>
                    @endif
                </div>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">{{ $task->title }}</h1>

            <span class="inline-flex items-center text-xs px-3 py-1 rounded-lg bg-slate-100 border text-slate-600" style="border-left: 4px solid {{ $task->category->color ?? '#ccc' }}">
                {{ $task->category->icon ?? '' }} {{ $task->category->name ?? 'No Category' }}
            </span>

            <div class="mt-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Deskripsi</h2>
                <div class="text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $task->description ?: 'Tidak ada deskripsi.' }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 border-t border-slate-100">
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Dibuat oleh</p>
                <p class="text-sm font-semibold text-slate-700">{{ $task->createdBy?->full_name ?? $task->createdBy?->name ?? '-' }}</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Ditugaskan ke</p>
                <p class="text-sm font-semibold text-slate-700">{{ $task->assignedTo?->full_name ?? $task->assignedTo?->name ?? '-' }}</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Dibuat</p>
                <p class="text-sm font-semibold text-slate-700">{{ $task->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-slate-500 mb-1">Terakhir diubah</p>
                <p class="text-sm font-semibold text-slate-700">{{ $task->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
        @if($task->status === 'Done')
            <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2 rounded-lg text-sm">
                <i class="fa-solid fa-lock"></i>
                <span>
                    Tugas selesai bersifat permanen — tidak dapat diubah atau dihapus.
                    @if($task->created_by === Auth::id())
                        Jika hasil tugas kurang tepat, buat tugas baru untuk pegawai ini.
                    @elseif($task->assigned_to === Auth::id())
                        Hubungi atasan jika ada perbaikan.
                    @endif
                </span>
            </div>
        @else
            @if($task->created_by === Auth::id())
                <button onclick="openEditModal({{ $task->toJson() }})" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Tugas
                </button>
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="confirm-delete" data-name="tugas {{ $task->title }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg font-medium transition text-sm">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </form>
            @endif
            @if($task->assigned_to === Auth::id() && $task->created_by !== Auth::id())
                <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="Done">
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition text-sm">
                        <i class="fa-solid fa-check"></i>
                        Tandai Selesai
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>

@include('tasks.edit-modal', ['categories' => $categories, 'task' => $task])

<script>
function openEditModal(task) {
    document.getElementById('edit-title').value = task.title;
    document.getElementById('edit-description').value = task.description;
    document.getElementById('edit-category').value = task.category_id;
    document.getElementById('edit-priority').value = task.priority;
    document.getElementById('edit-status').value = task.status;
    document.getElementById('edit-due_date').value = task.due_date ? task.due_date.substring(0, 10) : '';
    document.getElementById('modal-edit').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('modal-edit').classList.add('hidden');
}
</script>
@endsection

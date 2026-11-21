@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800">🎯 Daftar Tugas</h1>
        <div class="flex gap-2">
            @if($employeeForCreate->isNotEmpty())
                <a href="{{ route('tasks.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-md text-center text-sm">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Assign ke Bawahan
                </a>
            @endif
            <button onclick="openTaskModal()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-md text-center text-sm">
                <i class="fa-solid fa-plus mr-2"></i> Buat Tugas
            </button>
        </div>
    </div>

    {{-- Filter: karyawan + tanggal (hanya untuk atasan yang punya bawahan) --}}
    @if($subordinates->isNotEmpty())
        @php $viewedEmp = $subordinates->firstWhere('id', $viewingEmployeeId); @endphp
        <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
            <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-col gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Karyawan</label>
                    <select name="employee_id" class="border border-slate-300 bg-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto">
                        <option value="">— Tugas Saya —</option>
                        @foreach($subordinates as $sub)
                            <option value="{{ $sub->id }}" {{ $viewingEmployeeId == $sub->id ? 'selected' : '' }}>
                                {{ $sub->full_name }} ({{ $sub->positionData?->name ?? $sub->position ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Tanggal Awal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="border border-slate-300 bg-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="border border-slate-300 bg-white rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        <i class="fa-solid fa-filter mr-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['employee_id', 'start_date', 'end_date']))
                        <a href="{{ route('tasks.index') }}"
                           class="px-4 py-2 bg-slate-200 text-slate-600 rounded-lg hover:bg-slate-300 transition text-sm">
                            <i class="fa-solid fa-times mr-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
            @if($viewingEmployeeId && $viewedEmp)
                <p class="mt-2 text-xs text-blue-700">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Menampilkan tugas milik <strong>{{ $viewedEmp->full_name }}</strong>
                    @if(request('start_date') || request('end_date'))
                        @if(request('start_date') && request('end_date'))
                            dari <strong>{{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}</strong>
                            sampai <strong>{{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}</strong>
                        @elseif(request('start_date'))
                            mulai <strong>{{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}</strong>
                        @else
                            sampai <strong>{{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}</strong>
                        @endif
                    @endif
                </p>
            @endif
        </div>
    @else
        {{-- Karyawan biasa: hanya filter tanggal --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4">
            <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Tanggal Awal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if(request()->hasAny(['start_date', 'end_date']))
                    <a href="{{ route('tasks.index') }}"
                       class="px-4 py-2 bg-slate-200 text-slate-600 rounded-lg hover:bg-slate-300 transition text-sm">
                        <i class="fa-solid fa-times mr-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-500">Total Tugas</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['done'] }}</p>
            <p class="text-xs text-slate-500">Selesai</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-500">Pending</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
            <p class="text-xs text-slate-500">Terlambat</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['rate'] }}%</p>
            <p class="text-xs text-slate-500">Completion</p>
        </div>
    </div>


    <!-- Kanban Board Style -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        @php
            $statuses = ['Inbox', 'In Progress', 'Done'];
            $statusColors = [
                'Inbox' => 'bg-slate-200 text-slate-700',
                'In Progress' => 'bg-blue-100 text-blue-700',
                'Done' => 'bg-green-100 text-green-700',
            ];
        @endphp

        @foreach($statuses as $status)
            <div class="flex flex-col bg-slate-100 rounded-xl p-3 md:p-4 min-h-[400px] border border-slate-200">
                <h2 class="text-lg font-bold mb-4 flex items-center justify-between">
                    {{ $status }}
                    <span class="text-xs font-normal bg-white px-2 py-1 rounded-full border border-slate-300">
                        {{ $tasks->where('status', $status)->count() }}
                    </span>
                </h2>
                
                <div class="space-y-3">
                    @foreach($tasks->where('status', $status) as $task)
                        <div class="bg-white p-3 md:p-4 rounded-lg shadow-sm border border-slate-200 hover:border-blue-400 transition">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded {{ $statusColors[$status] }}">
                                    {{ $task->status }}
                                </span>
                                <div class="flex gap-2">
                                    @if($task->status === 'Done')
                                    <span class="text-slate-400" title="Tugas selesai terkunci — tidak dapat diubah atau dihapus"><i class="fa-solid fa-lock"></i></span>
                                    @else
                                    <form action="{{ route('tasks.update-status', $task->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs border rounded p-1 outline-none">
                                            @foreach($statuses as $s)
                                                <option value="{{ $s }}" {{ $task->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    @endif
                                    @if($task->created_by === Auth::id() && $task->status !== 'Done')
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="confirm-delete" data-name="tugas {{ $task->title }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-500 transition"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            <h3 class="font-bold text-slate-800 mb-1">
                                <a href="{{ route('tasks.show', $task->id) }}" class="hover:text-blue-600 transition">{{ $task->title }}</a>
                            </h3>
                            <p class="text-sm text-slate-500 mb-3 line-clamp-2">{{ $task->description }}</p>
                            
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-xs px-2 py-1 rounded bg-slate-100 border text-slate-600" style="border-left: 4px solid {{ $task->category->color ?? '#ccc' }}">
                                    {{ $task->category->icon ?? '' }} {{ $task->category->name ?? 'No Category' }}
                                </span>
                                <div class="flex items-center gap-2">
                                    @if($task->assignedTo && $task->assignedTo->id !== Auth::id())
                                        <span class="text-[10px] text-slate-400">➡ {{ $task->assignedTo->name }}</span>
                                    @endif
                                    <span class="text-xs font-medium {{ $task->priority == 'High' ? 'text-red-600' : ($task->priority == 'Medium' ? 'text-orange-600' : 'text-green-600') }}">
                                        <i class="fa-solid fa-circle text-[6px] mr-1"></i> {{ $task->priority }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[10px] text-slate-400">
                                    @if($task->createdBy && $task->createdBy->id !== $task->assigned_to)
                                        oleh {{ $task->createdBy->name }}
                                    @elseif($task->assignedTo && $task->assignedTo->id === Auth::id())
                                        Tugas Saya
                                    @endif
                                    &middot; {{ $task->created_at->format('d/m/Y') }}
                                </span>
                                @if($task->created_by === Auth::id() && $task->status !== 'Done')
                                <button onclick="openEditModal({{ $task->toJson() }})" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Buat Tugas -->
<div id="modal-task" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Buat Tugas Baru 📝</h2>
            <button onclick="closeTaskModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                @if($employeeForCreate->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Untuk</label>
                    <select name="assigned_to" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Diri Sendiri</option>
                        @foreach($employeeForCreate as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->positionData?->name ?? $emp->position ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->icon ?? '' }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
                        <select name="priority" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Low">🟢 Low</option>
                            <option value="Medium" selected>🟡 Medium</option>
                            <option value="High">🔴 High</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Inbox">Inbox</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tenggat</label>
                        <input type="date" name="due_date" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-lg shadow-blue-200">
                Simpan Tugas 🚀
            </button>
        </form>
    </div>
</div>

<!-- Modal Edit Task -->
@include('tasks.edit-modal', ['categories' => $categories])

<script>
    function openTaskModal() {
        document.getElementById('modal-task').classList.remove('hidden');
    }
    function closeTaskModal() {
        document.getElementById('modal-task').classList.add('hidden');
    }
    function closeEditModal() {
        document.getElementById('modal-edit').classList.add('hidden');
    }
    function openEditModal(task) {
        const form = document.getElementById('edit-form');
        form.action = '{{ route('tasks.update', ':id') }}'.replace(':id', task.id);
        
        document.getElementById('edit-title').value = task.title;
        document.getElementById('edit-description').value = task.description;
        document.getElementById('edit-category').value = task.category_id;
        document.getElementById('edit-priority').value = task.priority;
        document.getElementById('edit-status').value = task.status;
        document.getElementById('edit-due_date').value = task.due_date ? task.due_date.substring(0, 10) : '';
        
        document.getElementById('modal-edit').classList.remove('hidden');
    }

    setTimeout(function() {
        const modalTask = document.getElementById('modal-task');
        const modalEdit = document.getElementById('modal-edit');
        if (!modalTask || modalTask.classList.contains('hidden')) {
            if (!modalEdit || modalEdit.classList.contains('hidden')) {
                location.reload();
            }
        }
    }, 30000);
</script>
@endsection
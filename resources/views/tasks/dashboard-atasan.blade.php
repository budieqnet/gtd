@php
    $title = 'Dashboard Atasan';
@endphp

@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Dashboard Tim</h1>
        <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Assign Tugas
        </a>
    </div>

    <!-- Statistik per Bawahan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($taskStats as $name => $stats)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <p class="font-semibold text-slate-800 truncate">{{ $name }}</p>
                <div class="flex gap-4 mt-3 text-sm">
                    <span class="text-amber-600">
                        <i class="fa-solid fa-clock"></i> {{ $stats['pending'] }} pending
                    </span>
                    <span class="text-green-600">
                        <i class="fa-solid fa-check-circle"></i> {{ $stats['done'] }} selesai
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-slate-400 py-8">
                Belum ada bawahan.
            </div>
        @endforelse
    </div>

    <!-- Daftar Tugas -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-slate-800">Semua Tugas Tim</h2>
                <p class="text-xs text-slate-500 mt-0.5">Periode: {{ $monthLabel }} &middot; {{ $allTasks->count() }} tugas</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tasks.manager-dashboard', ['month' => $prevMonth]) }}"
                   class="px-2.5 py-1.5 border border-slate-300 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition" title="Bulan sebelumnya">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
                <form method="GET" action="{{ route('tasks.manager-dashboard') }}">
                    <input type="month" name="month" value="{{ $monthValue }}" onchange="this.form.submit()"
                           class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </form>
                <a href="{{ route('tasks.manager-dashboard', ['month' => $nextMonth]) }}"
                   class="px-2.5 py-1.5 border border-slate-300 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition" title="Bulan berikutnya">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
                @if($monthValue !== now()->format('Y-m'))
                    <a href="{{ route('tasks.manager-dashboard') }}"
                       class="px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition whitespace-nowrap">
                        Bulan Ini
                    </a>
                @endif
            </div>
        </div>
        @if($allTasks->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 bg-slate-50">
                            <th class="px-4 py-3 font-medium">Tugas</th>
                            <th class="px-4 py-3 font-medium">Ditugaskan ke</th>
                            <th class="px-4 py-3 font-medium">Kategori</th>
                            <th class="px-4 py-3 font-medium">Prioritas</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allTasks as $task)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-medium">{{ $task->title }}</td>
                                <td class="px-4 py-3">{{ $task->assignedTo?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs" style="background: {{ $task->category?->color ?? '#e2e8f0' }}20; color: {{ $task->category?->color ?? '#64748b' }}">
                                        {{ $task->category?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $prioColors = ['Low' => 'green', 'Medium' => 'amber', 'High' => 'red'];
                                        $c = $prioColors[$task->priority] ?? 'slate';
                                    @endphp
                                    <span class="text-{{ $c }}-600 bg-{{ $c }}-100 px-2 py-1 rounded-full text-xs">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = ['Inbox' => 'slate', 'In Progress' => 'blue', 'Done' => 'green'];
                                        $sc = $statusColors[$task->status] ?? 'slate';
                                    @endphp
                                    <span class="text-{{ $sc }}-600 bg-{{ $sc }}-100 px-2 py-1 rounded-full text-xs whitespace-nowrap">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 text-xs">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-slate-400 py-12">
                <i class="fa-solid fa-inbox text-4xl mb-3 text-slate-300"></i>
                <p>Tidak ada tugas pada {{ $monthLabel }}.</p>
            </div>
        @endif
    </div>
</div>
@endSection
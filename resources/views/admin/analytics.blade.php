@php
    $title = 'Advanced Analytics';
@endphp

@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between no-print">
        <h2 class="text-2xl font-bold text-slate-800">Advanced Analytics</h2>
        <div class="flex items-center gap-3">
            <p class="text-sm text-slate-500">{{ now()->translatedFormat('d F Y') }}</p>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
        </div>
    </div>
    <div class="print-only" style="display:none">
        <h2 class="text-2xl font-bold text-slate-800">Advanced Analytics</h2>
        <p class="text-sm text-slate-500">{{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">Total Tugas</p>
                <span class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-tasks"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalTasks }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">Selesai</p>
                <span class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fa-solid fa-check-circle"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalDone }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $completionRate }}% completion rate</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">Terlambat</p>
                <span class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fa-solid fa-clock"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalOverdue }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $totalTasks > 0 ? round(($totalOverdue / $totalTasks) * 100) : 0 }}% overdue</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">Pegawai</p>
                <span class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <p class="text-3xl font-bold text-slate-800 mt-2">{{ $totalEmployees }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $employeePerformance->count() }} aktif punya tugas</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Distribusi Status</h3>
            <div class="flex justify-center">
                <canvas id="statusChart" height="250"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Distribusi Prioritas</h3>
            <div class="flex justify-center">
                <canvas id="priorityChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-800 mb-4">Tren Bulanan (12 Bulan)</h3>
        <canvas id="trendChart" height="280"></canvas>
    </div>

    @if($employeePerformance->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-800 mb-4">Completion Rate per Pegawai</h3>
        <div style="height: {{ max(200, $employeePerformance->count() * 40) }}px">
            <canvas id="employeeChart"></canvas>
        </div>
    </div>
    @endif

    @if(!empty($positionPerformance))
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-800 mb-4">Kinerja per Jabatan</h3>
        <div style="height: {{ max(200, count($positionPerformance) * 45) }}px">
            <canvas id="positionChart"></canvas>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Top Performers</h3>
            @if($topPerformers->isNotEmpty())
                <div class="space-y-3">
                    @foreach($topPerformers as $i => $emp)
                        <div class="flex items-center gap-3 p-3 rounded-lg {{ $i == 0 ? 'bg-yellow-50 border border-yellow-200' : 'bg-slate-50' }}">
                            <span class="w-8 h-8 rounded-full {{ $i == 0 ? 'bg-yellow-400' : 'bg-blue-500' }} flex items-center justify-center font-bold text-white text-sm">
                                {{ $i + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-800 truncate">{{ $emp->full_name }}</p>
                                <p class="text-xs text-slate-500">{{ $emp->positionData?->name ?? $emp->position ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg {{ $i == 0 ? 'text-yellow-600' : 'text-blue-600' }}">
                                    {{ $emp->total_tasks > 0 ? round(($emp->done_tasks / $emp->total_tasks) * 100) : 0 }}%
                                </p>
                                <p class="text-xs text-slate-400">{{ $emp->done_tasks }}/{{ $emp->total_tasks }} tugas</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-400 text-center py-8">Belum ada data tugas</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Paling Banyak Terlambat</h3>
            @if($mostOverdue->isNotEmpty())
                <div class="space-y-3">
                    @foreach($mostOverdue as $emp)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-red-50">
                            <span class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center font-bold text-white text-sm">
                                <i class="fa-solid fa-exclamation-triangle text-xs"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-800 truncate">{{ $emp->full_name }}</p>
                                <p class="text-xs text-slate-500">{{ $emp->positionData?->name ?? $emp->position ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg text-red-600">{{ $emp->overdue_tasks }}</p>
                                <p class="text-xs text-slate-400">terlambat</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-400 text-center py-8">Tidak ada tugas terlambat</p>
            @endif
        </div>
    </div>

    @if($employeePerformance->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 overflow-x-auto">
        <h3 class="font-semibold text-slate-800 mb-4">Detail Kinerja Pegawai</h3>
        <table class="data-table w-full text-sm">
            <thead>
                <tr class="bg-slate-100">
                    <th class="p-3 text-left font-semibold text-slate-600">Pegawai</th>
                    <th class="p-3 text-center font-semibold text-slate-600">Total</th>
                    <th class="p-3 text-center font-semibold text-green-600">Selesai</th>
                    <th class="p-3 text-center font-semibold text-red-600">Terlambat</th>
                    <th class="p-3 text-center font-semibold text-slate-600">Completion</th>
                    <th class="p-3 text-center font-semibold text-slate-600">On-Time Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employeePerformance as $emp)
                    @php
                        $onTimeCount = \App\Models\Task::where('assigned_to', $emp->id)->where('status', 'Done')->whereNotNull('due_date')->whereColumn('updated_at', '<=', 'due_date')->count();
                        $onTimeRate = $emp->done_tasks > 0 ? round(($onTimeCount / $emp->done_tasks) * 100) : 0;
                        $compRate = $emp->total_tasks > 0 ? round(($emp->done_tasks / $emp->total_tasks) * 100) : 0;
                    @endphp
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="p-3 font-medium text-slate-800">{{ $emp->full_name }}</td>
                        <td class="p-3 text-center">{{ $emp->total_tasks }}</td>
                        <td class="p-3 text-center text-green-600 font-medium">{{ $emp->done_tasks }}</td>
                        <td class="p-3 text-center text-red-600 font-medium">{{ $emp->overdue_tasks }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $compRate >= 70 ? 'bg-green-500' : ($compRate >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $compRate }}%"></div>
                                </div>
                                <span class="text-xs font-medium {{ $compRate >= 70 ? 'text-green-600' : ($compRate >= 40 ? 'text-yellow-600' : 'text-red-600') }}">{{ $compRate }}%</span>
                            </div>
                        </td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $onTimeRate >= 70 ? 'bg-green-100 text-green-700' : ($onTimeRate >= 40 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ $onTimeRate }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = {
        blue: ['#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe'],
        green: ['#22c55e', '#4ade80', '#86efac'],
        red: ['#ef4444', '#f87171', '#fca5a5'],
        yellow: ['#eab308', '#facc15', '#fde047'],
        purple: ['#a855f7', '#c084fc', '#d8b4fe'],
        orange: ['#f97316', '#fb923c', '#fdba74'],
    };

    function initChart(id, config) {
        const ctx = document.getElementById(id)?.getContext('2d');
        if (ctx) new Chart(ctx, config);
    }

    initChart('statusChart', {
        type: 'doughnut',
        data: {
            labels: ['Inbox', 'In Progress', 'Done'],
            datasets: [{
                data: [{{ $statusDistribution['Inbox'] }}, {{ $statusDistribution['In Progress'] }}, {{ $statusDistribution['Done'] }}],
                backgroundColor: ['#facc15', '#3b82f6', '#22c55e'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } }
            }
        }
    });

    initChart('priorityChart', {
        type: 'doughnut',
        data: {
            labels: ['Low', 'Medium', 'High', 'Urgent'],
            datasets: [{
                data: [{{ $priorityDistribution['Low'] }}, {{ $priorityDistribution['Medium'] }}, {{ $priorityDistribution['High'] }}, {{ $priorityDistribution['Urgent'] }}],
                backgroundColor: ['#22c55e', '#3b82f6', '#f97316', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } }
            }
        }
    });

    const months = @json(array_column($monthlyData, 'month'));
    const created = @json(array_column($monthlyData, 'created'));
    const completed = @json(array_column($monthlyData, 'completed'));

    initChart('trendChart', {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                { label: 'Dibuat', data: created, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', fill: true, tension: 0.3, pointRadius: 4 },
                { label: 'Selesai', data: completed, borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.1)', fill: true, tension: 0.3, pointRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            interaction: { intersect: false, mode: 'index' },
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    @if($employeePerformance->isNotEmpty())
    const empNames = @json($employeePerformance->pluck('full_name'));
    const empDone = @json($employeePerformance->pluck('done_tasks'));
    const empTotal = @json($employeePerformance->pluck('total_tasks'));
    const empRates = empTotal.map((t, i) => t > 0 ? Math.round((empDone[i] / t) * 100) : 0);

    const empCtx = document.getElementById('employeeChart')?.getContext('2d');
    if (empCtx) {
        new Chart(empCtx, {
            type: 'bar',
            data: {
                labels: empNames,
                datasets: [{
                    label: 'Completion Rate (%)',
                    data: empRates,
                    backgroundColor: empRates.map(r => r >= 70 ? '#22c55e' : r >= 40 ? '#eab308' : '#ef4444'),
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const i = ctx.dataIndex;
                                return ctx.parsed.x + '% (' + empDone[i] + '/' + empTotal[i] + ')';
                            }
                        }
                    }
                },
                scales: {
                    x: { beginAtZero: true, max: 100, title: { display: true, text: 'Completion Rate (%)' } }
                }
            }
        });
    }
    @endif

    @if(!empty($positionPerformance))
    const posNames = @json(array_column($positionPerformance, 'name'));
    const posRates = @json(array_column($positionPerformance, 'completion_rate'));
    const posDone = @json(array_column($positionPerformance, 'done_tasks'));
    const posTotal = @json(array_column($positionPerformance, 'total_tasks'));

    const posCtx = document.getElementById('positionChart')?.getContext('2d');
    if (posCtx) {
        new Chart(posCtx, {
            type: 'bar',
            data: {
                labels: posNames,
                datasets: [{
                    label: 'Completion Rate (%)',
                    data: posRates,
                    backgroundColor: posRates.map(r => r >= 70 ? '#22c55e' : r >= 40 ? '#eab308' : '#ef4444'),
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const i = ctx.dataIndex;
                                return ctx.parsed.x + '% (' + posDone[i] + '/' + posTotal[i] + ')';
                            }
                        }
                    }
                },
                scales: {
                    x: { beginAtZero: true, max: 100, title: { display: true, text: 'Completion Rate (%)' } }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
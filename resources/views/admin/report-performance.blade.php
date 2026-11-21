@php
    $title = 'Laporan Kinerja Pegawai';
@endphp

@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between no-print">
        <h2 class="text-2xl font-bold text-slate-800">Laporan Kinerja Pegawai</h2>
        <div class="flex items-center gap-2 no-print">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 no-print">
        <form method="GET" action="{{ route('admin.reports.performance') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Awal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                <i class="fa-solid fa-filter"></i> Tampilkan
            </button>
        </form>
    </div>

    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">
            Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong>
        </p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-slate-800">{{ $summary['total_employees'] }}</p>
            <p class="text-xs text-slate-500">Pegawai Aktif</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $summary['total_inbox'] }}</p>
            <p class="text-xs text-slate-500">Inbox</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $summary['total_progress'] }}</p>
            <p class="text-xs text-slate-500">In Progress</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $summary['total_done'] }}</p>
            <p class="text-xs text-slate-500">Done</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-slate-800">{{ $summary['total_all'] }}</p>
            <p class="text-xs text-slate-500">Total Tugas</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 overflow-x-auto">
        @if($reportData->isNotEmpty())
            <table class="data-table w-full text-sm">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="p-3 text-left font-semibold text-slate-600">No</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Nama Pegawai</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Jabatan</th>
                        <th class="p-3 text-center font-semibold text-yellow-600">Inbox</th>
                        <th class="p-3 text-center font-semibold text-blue-600">In Progress</th>
                        <th class="p-3 text-center font-semibold text-green-600">Done</th>
                        <th class="p-3 text-center font-semibold text-slate-600">Total</th>
                        <th class="p-3 text-center font-semibold text-slate-600">% Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $i => $item)
                        @php $rate = $item['total'] > 0 ? round(($item['done'] / $item['total']) * 100) : 0; @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="p-3 text-center">{{ $i + 1 }}</td>
                            <td class="p-3 font-medium text-slate-800">{{ $item['employee']->full_name }}</td>
                            <td class="p-3 text-slate-500">{{ $item['employee']->positionData?->name ?? $item['employee']->position ?? '-' }}</td>
                            <td class="p-3 text-center text-yellow-600 font-medium">{{ $item['inbox'] }}</td>
                            <td class="p-3 text-center text-blue-600 font-medium">{{ $item['progress'] }}</td>
                            <td class="p-3 text-center text-green-600 font-medium">{{ $item['done'] }}</td>
                            <td class="p-3 text-center font-medium">{{ $item['total'] }}</td>
                            <td class="p-3 text-center">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $rate >= 70 ? 'bg-green-500' : ($rate >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $rate }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium {{ $rate >= 70 ? 'text-green-600' : ($rate >= 40 ? 'text-yellow-600' : 'text-red-600') }}">{{ $rate }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-8 text-slate-400">
                <i class="fa-solid fa-file-alt text-4xl mb-3"></i>
                <p>Tidak ada data tugas pada periode ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
@php
    $title = 'Admin Dashboard';
@endphp

@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-800">Admin Dashboard</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Pegawai</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalEmployees }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fa-solid fa-tags text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Kategori</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fa-solid fa-tasks text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Tugas</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalTasks }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold text-lg text-slate-800 mb-4">Pegawai Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="pb-3 font-medium">Nama</th>
                        <th class="pb-3 font-medium">Email</th>
                        <th class="pb-3 font-medium">Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEmployees as $emp)
                        <tr class="border-b border-slate-100">
                            <td class="py-3">{{ $emp->name }}</td>
                            <td class="py-3 text-slate-500">{{ $emp->email }}</td>
                            <td class="py-3">{{ $emp->position }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-400">Belum ada pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.employees') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
            <i class="fa-solid fa-users-cog"></i> Kelola Pegawai
        </a>
        <a href="{{ route('admin.categories') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
            <i class="fa-solid fa-tags"></i> Kelola Kategori
        </a>
    </div>
</div>
@endsection
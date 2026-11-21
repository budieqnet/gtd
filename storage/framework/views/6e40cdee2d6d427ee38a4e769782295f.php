<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'GTD App')); ?> - Admin</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'%3E%3Cpolyline points='8,34 20,46 30,30' fill='none' stroke='%233b82f6' stroke-width='5' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpolyline points='28,34 40,46 54,22' fill='none' stroke='%233b82f6' stroke-width='5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @media print {
            #sidebar, #sidebar + div, header, footer,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_paginate,
            .dataTables_wrapper .dataTables_info,
            .no-print, #hamburger-btn, button, .btn,
            nav:not(.dataTables_wrapper nav) { display: none !important; }
            main, .lg\:ml-72 { margin-left: 0 !important; padding: 0 !important; }
            body { background: white !important; }
            .max-w-\[1200px\] { max-width: 100% !important; padding: 0 10px !important; }
            .bg-white, div[class*="bg-white"] { background: white !important; box-shadow: none !important; border: 1px solid #ddd !important; }
            canvas { max-height: 200px; }
            .grid, table, tr, td, th { break-inside: avoid; }
            table { width: 100% !important; border-collapse: collapse; }
            td, th { border: 1px solid #ccc !important; padding: 6px 8px !important; }
        }
    </style>
</head>
<body class="antialiased bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false }">

    <aside id="sidebar"
           :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
           class="fixed top-0 left-0 h-full bg-slate-900 text-white z-50 transform transition-transform duration-300 ease-in-out w-[85%] max-w-xs sm:w-72 lg:!translate-x-0 lg:block">

        <div class="flex justify-between items-center p-4 border-b border-slate-700">
            <h1 class="font-bold text-lg flex items-center gap-2">
                <i class="fa-solid fa-check-double text-blue-400"></i> GTD App
            </h1>
            <button @click="sidebarOpen = false" class="text-white hover:text-gray-300 transition lg:hidden">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <nav class="p-4 space-y-1 mt-2 h-[calc(100%-80px)] overflow-y-auto">
            <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Administrasi</p>

            <a href="<?php echo e(route('admin.employees')); ?>"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.employees*') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-users w-5"></i> Pegawai
            </a>

            <a href="<?php echo e(route('admin.performance')); ?>"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.performance') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-chart-line w-5"></i> Kinerja
            </a>

            <a href="<?php echo e(route('admin.analytics')); ?>"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.analytics') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-chart-pie w-5"></i> Analytics
            </a>

            <a href="<?php echo e(route('admin.reports.performance')); ?>"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.reports.performance') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-file-alt w-5"></i> Laporan
            </a>

            <div x-data="{ open: <?php echo e(request()->routeIs('admin.admins*') || request()->routeIs('admin.positions*') || request()->routeIs('admin.categories*') ? 'true' : 'false'); ?> }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg transition text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-cog w-5"></i> Setting
                    </span>
                    <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 space-y-1">
                    <a href="<?php echo e(route('admin.admins')); ?>"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition text-sm <?php echo e(request()->routeIs('admin.admins*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <i class="fa-solid fa-shield-alt w-4"></i> Admin
                    </a>
                    <a href="<?php echo e(route('admin.positions')); ?>"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition text-sm <?php echo e(request()->routeIs('admin.positions*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <i class="fa-solid fa-briefcase w-4"></i> Jabatan
                    </a>
                    <a href="<?php echo e(route('admin.categories')); ?>"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition text-sm <?php echo e(request()->routeIs('admin.categories*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white'); ?>">
                        <i class="fa-solid fa-tags w-4"></i> Kategori
                    </a>
                </div>
            </div>

            <div class="pt-3 mt-3 border-t border-slate-700">
                <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-red-300 hover:bg-red-900/50 text-sm">
                        <i class="fa-solid fa-sign-out-alt w-5"></i> Keluar
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-40 transition-opacity lg:hidden"></div>

    <main class="min-h-screen lg:ml-72">
        <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm sticky top-0 z-30">
            <button @click.stop="sidebarOpen = !sidebarOpen"
                    id="hamburger-btn"
                    class="text-slate-700 hover:text-blue-600 transition text-xl p-2 rounded-lg hover:bg-slate-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 lg:hidden">
                <i class="fa-solid fa-bars"></i>
            </button>

            <h1 class="font-bold text-lg text-slate-800"><?php echo e(config('app.name')); ?></h1>

            <div class="flex items-center gap-2 hidden md:flex">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-white text-sm">
                    <?php echo e(substr(Auth::guard('admin')->user()->name, 0, 1)); ?>

                </div>
                <div class="text-xs">
                    <p class="font-semibold"><?php echo e(Auth::guard('admin')->user()->name); ?></p>
                    <p class="text-slate-500">Administrator</p>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="md:hidden">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-slate-500 hover:text-red-600 transition p-2">
                    <i class="fa-solid fa-sign-out-alt text-lg"></i>
                </button>
            </form>
        </header>

        <div class="p-4 md:p-6 max-w-[1200px] mx-auto">
            <?php if (! empty(trim($__env->yieldContent('content')))): ?>
                <?php echo $__env->yieldContent('content'); ?>
            <?php else: ?>
                <?php echo e($slot ?? ''); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </main>

    <footer class="lg:ml-72 border-t border-slate-200 bg-white">
        <div class="px-4 md:px-6 py-4 max-w-[1200px] mx-auto text-center text-xs text-slate-500">
            Build with <i class="fa-solid fa-heart text-red-500"></i> by Pengadilan Agama Medan
        </div>
    </footer>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).on('submit', 'form.confirm-delete', function(e) {
            e.preventDefault();
            var form = this;
            var name = form.getAttribute('data-name') || 'item ini';
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Hapus ' + name + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.data-table').each(function() {
                if ($(this).find('tbody tr:not(.odd):not(.even)').length > 0 || $(this).find('tbody td').length > 0) {
                    $(this).DataTable({
                        ordering: false,
                        responsive: true,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        }
                    });
                }
            });
            $('.data-table-no-page').each(function() {
                if ($(this).find('tbody tr').length > 0) {
                    $(this).DataTable({
                        ordering: false,
                        responsive: true,
                        paging: false,
                        info: false,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        }
                    });
                }
            });
        });

        window.onbeforeprint = function() {
            $('.data-table, .data-table-no-page').each(function() {
                if ($.fn.DataTable && $(this).DataTable()) {
                    $(this).DataTable().destroy();
                    $(this).removeAttr('style');
                }
            });
        };
        window.onafterprint = function() {
            $('.data-table').each(function() {
                if ($(this).find('tbody tr').length > 0 && $.fn.DataTable && !$(this).DataTable()) {
                    $(this).DataTable({
                        ordering: false,
                        responsive: true,
                        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
                    });
                }
            });
            $('.data-table-no-page').each(function() {
                if ($(this).find('tbody tr').length > 0 && $.fn.DataTable && !$(this).DataTable()) {
                    $(this).DataTable({
                        ordering: false,
                        responsive: true,
                        paging: false,
                        info: false,
                        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
                    });
                }
            });
        };
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /var/www/html/gtd/resources/views/layouts/admin.blade.php ENDPATH**/ ?>
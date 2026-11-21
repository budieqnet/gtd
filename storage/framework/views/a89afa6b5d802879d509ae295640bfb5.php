<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'GTD App')); ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'%3E%3Cpolyline points='8,34 20,46 30,30' fill='none' stroke='%233b82f6' stroke-width='5' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpolyline points='28,34 40,46 54,22' fill='none' stroke='%233b82f6' stroke-width='5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="antialiased bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false, notifOpen: false, notifications: [], unreadCount: <?php echo e(Auth::user()->unreadNotifications->count()); ?>, msgUnread: 0 }" @click.outside="notifOpen = false" x-init="fetchNotifications(); fetchMsgUnread()">

    <!-- SIDEBAR -->
    <aside id="sidebar" 
           :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
           class="fixed top-0 left-0 h-full bg-slate-900 text-white z-50 transform transition-transform duration-300 ease-in-out w-[85%] max-w-xs sm:w-72 lg:!translate-x-0 lg:block">
        
        <!-- Header -->
        <div class="flex justify-between items-center p-4 border-b border-slate-700">
            <h1 class="font-bold text-lg flex items-center gap-2">
                <i class="fa-solid fa-check-double text-blue-400"></i> GTD App
            </h1>
            <button @click="sidebarOpen = false" class="text-white hover:text-gray-300 transition lg:hidden">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-1 mt-2 h-[calc(100%-80px)] overflow-y-auto">
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('dashboard') || request()->routeIs('tasks.index') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-chart-line w-5"></i>
                Dashboard Tugas
            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->allSubordinates()->isNotEmpty()): ?>
            <a href="<?php echo e(route('tasks.manager-dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('tasks.manager-dashboard') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-users w-5"></i>
                Dashboard Tim
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <a href="<?php echo e(route('tasks.create')); ?>" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('tasks.create*') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-plus-circle w-5"></i>
                Buat Tugas Baru
            </a>

            <a href="<?php echo e(route('tasks.history')); ?>" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('tasks.history') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-clock-rotate-left w-5"></i>
                Riwayat Tugas
            </a>

            <a href="<?php echo e(route('messenger')); ?>" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('messenger*') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                <i class="fa-solid fa-comment-dots w-5"></i>
                <span class="flex-1">Pesan</span>
                <span x-show="msgUnread > 0" class="w-5 h-5 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full" x-text="msgUnread"></span>
            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->allSubordinates()->isNotEmpty()): ?>
            <div class="pt-3 mt-3 border-t border-slate-700">
                <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Tim Saya</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = auth()->user()->allSubordinates(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a href="<?php echo e(route('dashboard', ['employee_id' => $emp->id])); ?>" 
                       class="block px-4 py-2 rounded-lg transition text-sm <?php echo e(request()->get('employee_id') == $emp->id ? 'bg-slate-800' : 'text-slate-300 hover:bg-slate-700'); ?>">
                        <i class="fa-solid fa-user w-5 mr-2"></i> <?php echo e($emp->name); ?>

                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="pt-3 mt-3 border-t border-slate-700 space-y-1">
                <p class="px-4 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Akun Saya</p>
                <a href="<?php echo e(route('profile.edit')); ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-sm <?php echo e(request()->routeIs('profile.edit') ? 'bg-blue-600' : 'text-slate-300 hover:bg-slate-800 hover:text-white'); ?>">
                    <i class="fa-solid fa-user w-5"></i> Profil Saya
                </a>
                <a href="#" onclick="showHirarki(event)" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-sitemap w-5"></i> Struktur Hirarki
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition text-red-300 hover:bg-red-900/50 text-sm">
                        <i class="fa-solid fa-sign-out-alt w-5"></i> Keluar
                    </button>
                </form>
            </div>
        </nav>

    </aside>

    <!-- Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition.opacity
         class="fixed inset-0 bg-black/50 z-40 transition-opacity lg:hidden"></div>

    <!-- Main Content -->
    <main class="min-h-screen lg:ml-72">
        <!-- Header with Hamburger -->
        <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm sticky top-0 z-30">
            <button @click.stop="sidebarOpen = !sidebarOpen" 
                    id="hamburger-btn"
                    class="text-slate-700 hover:text-blue-600 transition text-xl p-2 rounded-lg hover:bg-slate-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 lg:hidden">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <h1 class="font-bold text-lg text-slate-800"><?php echo e(config('app.name')); ?></h1>
            
            <div class="flex items-center gap-3 hidden md:flex">
                <div class="relative" @click.stop="notifOpen = !notifOpen">
                    <button class="relative p-2 text-slate-500 hover:text-blue-600 transition rounded-lg hover:bg-slate-100">
                        <i class="fa-solid fa-bell text-lg"></i>
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full" x-text="unreadCount"></span>
                        </template>
                    </button>
                    <div x-show="notifOpen"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-700">Notifikasi</span>
                            <button @click="fetch('<?php echo e(url('/notifications/read-all')); ?>', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } }).then(r => { unreadCount = 0; fetchNotifications(); })" class="text-xs text-blue-600 hover:text-blue-800 transition" x-show="unreadCount > 0">Tandai semua dibaca</button>
                        </div>
                        <div class="max-h-72 overflow-y-auto" id="notif-list">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-8 text-center text-slate-400 text-sm">Tidak ada notifikasi</div>
                            </template>
                            <template x-for="notif in notifications" :key="notif.id">
                                <div class="px-4 py-3 border-b border-slate-50 hover:bg-slate-50 transition flex items-start gap-3" :class="{ 'bg-blue-50/50': !notif.read_at }">
                                    <i class="fa-solid mt-1" :class="notif.type && notif.type.includes('NewMessage') ? 'fa-comment text-green-500' : 'fa-tasks text-blue-500'"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-slate-700" x-text="notif.data.message"></p>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="timeAgo(notif.created_at)"></p>
                                    </div>
                                    <template x-if="!notif.read_at">
                                        <button @click="markRead(notif.id)" class="text-xs text-blue-500 hover:text-blue-700 shrink-0 mt-1">
                                            <i class="fa-solid fa-circle"></i>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <a href="<?php echo e(route('tasks.index')); ?>" class="block px-4 py-2.5 text-center text-sm text-blue-600 hover:bg-slate-50 border-t border-slate-100">
                            Lihat Semua Tugas
                        </a>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-white text-sm">
                    <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                </div>
                <div class="text-xs">
                    <p class="font-semibold"><?php echo e(Auth::user()->name); ?></p>
                    <p class="text-slate-500"><?php echo e(auth()->user()->positionData?->name ?? auth()->user()->position ?? 'Pegawai'); ?></p>
                    <p class="text-slate-400 text-[10px]">NIP: <?php echo e(auth()->user()->nip); ?></p>
                </div>
            </div>

            <!-- Log Out Mobile -->
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="md:hidden">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-slate-500 hover:text-red-600 transition p-2">
                    <i class="fa-solid fa-sign-out-alt text-lg"></i>
                </button>
            </form>
        </header>

        <!-- Page Content -->
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
            $('.data-table').DataTable({
                ordering: false,
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
            $('.data-table-no-page').DataTable({
                ordering: false,
                responsive: true,
                paging: false,
                info: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
    <script>
        function fetchNotifications() {
            fetch('<?php echo e(url('/notifications/data')); ?>')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (window.Alpine) {
                        var state = Alpine.$data(document.body);
                        state.notifications = data.notifications;
                        state.unreadCount = data.unreadCount;
                    }
                });
            setTimeout(fetchNotifications, 30000);
        }
        function markRead(id) {
            fetch('<?php echo e(url('/notifications/read')); ?>/' + id, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } })
                .then(function() {
                    fetchNotifications();
                });
        }
        function timeAgo(dateStr) {
            var now = new Date();
            var date = new Date(dateStr);
            if (isNaN(date.getTime())) {
                date = new Date(dateStr.replace(' ', 'T'));
            }
            var diff = Math.floor((now - date) / 1000);
            if (diff < 60) return 'baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            if (diff < 172800) return 'kemarin';
            return date.toLocaleDateString('id-ID');
        }
        function fetchMsgUnread() {
            fetch('<?php echo e(url('/messenger/unread')); ?>')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (window.Alpine) {
                        Alpine.$data(document.body).msgUnread = data.count;
                    }
                });
            setTimeout(fetchMsgUnread, 30000);
        }
    </script>
    <div id="hirarki-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4" onclick="if(event.target===this)closeHirarki()">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">Struktur Hirarki</h2>
                <button onclick="closeHirarki()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div id="hirarki-content" class="space-y-4 text-center text-slate-400 py-8">
                <i class="fa-solid fa-spinner fa-spin text-2xl"></i>
                <p>Memuat data...</p>
            </div>
        </div>
    </div>

    <?php
        $hirarkiUser = [
            'name' => Auth::user()->full_name,
            'position' => Auth::user()->positionData?->name ?? Auth::user()->position ?? '-',
            'photo' => Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : null,
        ];
        $hirarkiSuperiors = Auth::user()->positionData
            ? \App\Models\Position::with('employees')->whereIn('id', Auth::user()->allSuperiorIds())->orderBy('level')->get()->map(fn($p) => ['name' => $p->name, 'employees' => $p->employees->pluck('full_name')])
            : [];
        $hirarkiSubordinates = Auth::user()->allSubordinates()->map(fn($e) => ['name' => $e->full_name, 'position' => $e->positionData?->name ?? $e->position ?? '-']);
    ?>
    <script>
        window.hirarkiData = <?php echo json_encode(['user' => $hirarkiUser, 'superiors' => $hirarkiSuperiors, 'subordinates' => $hirarkiSubordinates], JSON_THROW_ON_ERROR) ?>;

        function showHirarki(e) {
            e.preventDefault();
            const d = window.hirarkiData;
            let html = '';

            if (d.superiors && d.superiors.length > 0) {
                html += '<div class="mb-4"><p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Atasan</p>';
                d.superiors.forEach(function(s) {
                    html += '<div class="bg-blue-50 rounded-lg p-3 mb-2 border border-blue-200">';
                    html += '<p class="font-medium text-blue-800 text-sm">' + s.name + '</p>';
                    if (s.employees && s.employees.length > 0) {
                        html += '<p class="text-xs text-blue-600 mt-1">' + s.employees.join(', ') + '</p>';
                    }
                    html += '</div>';
                });
                html += '</div>';
            }

            html += '<div class="mb-4"><p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Saya</p>';
            html += '<div class="bg-green-50 rounded-lg p-4 border border-green-200 text-center">';
            if (d.user.photo) {
                html += '<img src="' + d.user.photo + '" class="w-12 h-12 rounded-full mx-auto mb-2 object-cover">';
            } else {
                html += '<div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-white mx-auto mb-2">' + d.user.name.charAt(0) + '</div>';
            }
            html += '<p class="font-bold text-green-800">' + d.user.name + '</p>';
            html += '<p class="text-xs text-green-600">' + d.user.position + '</p>';
            html += '</div></div>';

            if (d.subordinates && d.subordinates.length > 0) {
                html += '<div><p class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Bawahan Langsung</p>';
                d.subordinates.forEach(function(s) {
                    html += '<div class="bg-slate-50 rounded-lg p-3 mb-2 border border-slate-200 flex items-center gap-3">';
                    html += '<div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-pink-500 flex items-center justify-center font-bold text-white text-xs">' + s.name.charAt(0) + '</div>';
                    html += '<div><p class="font-medium text-slate-800 text-sm">' + s.name + '</p>';
                    html += '<p class="text-xs text-slate-500">' + s.position + '</p></div></div>';
                });
                html += '</div>';
            } else {
                html += '<div class="text-center text-slate-400 py-4"><p class="text-sm">Tidak memiliki bawahan</p></div>';
            }

            document.getElementById('hirarki-content').innerHTML = html;
            document.getElementById('hirarki-modal').classList.remove('hidden');
        }

        function closeHirarki() {
            document.getElementById('hirarki-modal').classList.add('hidden');
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/html/gtd/resources/views/layouts/app.blade.php ENDPATH**/ ?>
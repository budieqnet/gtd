# 🚀 Project History: GTD (Get Things Done) App

## 📌 Overview
Aplikasi manajemen produktivitas berbasis metodologi **GTD (Getting Things Done)** yang dirancang untuk membantu pengguna mengelola tugas dengan alur kerja: **Capture $\rightarrow$ Process $\rightarrow$ Do**.

**Tech Stack:**
- **Framework:** Laravel 13.20.0 (PHP 8.3.6)
- **Database:** MySQL (gtd_db)
- **Frontend:** Tailwind CSS (via CDN)
- **Architecture:** MVC (Model-View-Controller)

---

## 🗓️ Development Log

### Phase 1: Foundation (The Skeleton)
- **Initial State:** Proyek ditemukan dalam kondisi *skeleton*. Hanya terdapat migrasi database dasar dan model kosong.
- **Database Setup:** 
    - Konfigurasi koneksi database pada `.env`.
    - Eksekusi migrasi untuk tabel `categories` dan `tasks`.
- **Model Implementation:** Implementasi relasi One-to-Many antara `Category` dan `Task`.

### Phase 2: Backend & Logic Development
- **Controller Creation:**
    - `CategoryController`: Menangani CRUD kategori dan custom warna.
    - `TaskController`: Menangani CRUD tugas, update status, dan manajemen prioritas.
- **Routing:** Implementasi routing di `web.php` untuk menghubungkan semua fitur.

### Phase 3: Frontend & UX Implementation
- **Layouting:** Pembuatan `layouts/app.blade.php` dengan sidebar navigasi yang modern.
- **Kanban Board:** Implementasi tampilan kolom (Inbox, In Progress, Done) untuk visualisasi alur GTD.
- **Interactive UI:** 
    - Implementasi Modal untuk penambahan tugas dan kategori.
    - Fitur *Quick Status Update* via dropdown.
    - Visual indikator prioritas dan warna kategori.

### Phase 4: Stability & Bug Fixing
- **Autoload Fix:** Perbaikan masalah `Class not found` dengan `composer dump-autoload`.
- **Route Refresh:** Pembersihan cache route dan view untuk memastikan perubahan segera diterapkan.
- **Controller Restoration:** Perbaikan kehilangan file `TaskController.php` dan sinkronisasi namespace.
- **UX & Responsiveness:** Implementasi Menu Burger untuk mobile, perbaikan grid Kanban agar adaptif, dan penambahan fitur Edit Task via Modal.

### Phase 5: Notification Infrastructure (Tahap 1)
- **Mail Configuration:** Pengaturan `MAIL_MAILER=log` untuk simulasi pengiriman email melalui file log.
- **Mailable Implementation:** Pembuatan class `DailyReminderMail` untuk menyusun struktur email pengingat.
- **Email Templating:** Pembuatan view `emails.daily_reminder` dengan desain responsif, label prioritas, dan pesan motivasi.
- **Functional Testing:** Pengujian pengiriman email melalui Artisan Tinker untuk memastikan template ter-render dengan benar.

### Phase 6: Employee Hierarchy & Task Assignment (Baru!) 🎉
- **Employee Table Creation:** Tabel `employees` dengan relasi self-referencing (`superior_id`) untuk hierarchical structure.
- **Authentication Integration:** Konfigurasi auth menggunakan model `Employee` bukan `User` (Laravel Breeze).
- **Task Assignment System:** 
    - Atasan bisa assign tugas ke bawahan langsung
    - Validasi hierarki (hanya bisa assign ke direct subordinate)
    - Field baru di tasks: `assigned_to`, `created_by`, `due_date`
- **Email Notification on Task Assignment:**
    - Class `TaskAssignedMail` dengan template HTML responsif
    - Email dikirim otomatis saat tugas dibuat untuk bawahan
    - Template email menampilkan detail lengkap (judul, deskripsi, prioritas, deadline)
- **Role-Based Access Control:** 
    - Atasan: lihat semua tugas yang dibuatnya + assign ke bawahan
    - Bawahan: hanya bisa lihat & update tugas yang assigned ke dirinya
- **Database Seeding Data Pegawai:** Contoh hirarki (Anto → Budi/Siti, Rahmat → Dewi)

### Phase 7: Daily Morning Reminder (Otomatis) ☀️
- **DailyReminderMail:** Personalisasi nama employee di subject & template email.
- **SendDailyReminder Command:** Loop semua employee, filter tugas per-employee (`assigned_to`), kirim email ke masing-masing.
- **Schedule:** Terjadwal via `routes/console.php` setiap pukul 07:00.
- **Mail Driver:** Menggunakan `log` untuk simulasi (siap dipindah ke SMTP).

### Phase 8: Admin Panel & RBAC 🛡️
- **Role Admin:** Kolom `is_admin` di tabel `employees`.
- **AdminMiddleware:** Proteksi route admin, cek `isAdmin()`.
- **AdminController:** CRUD pegawai, kategori, jabatan + manajemen hierarki.
- **RedirectIfAdmin Middleware:** Admin tidak bisa akses halaman tugas (redirect ke admin panel).
- **Sidebar Admin:** Menu khusus (Kinerja Pegawai, Kelola Pegawai, Kelola Jabatan, Kelola Kategori).
- **Kinerja Pegawai:** Statistik per pegawai (total, pending, selesai, terlambat, progress bar).
- **Layout Responsif:** Sidebar fixed di desktop, overlay+hamburger di mobile.

### Phase 9: Position Management & Drag-Drop 🏢
- **Tabel Positions:** Relasi `position_id` ke employees, kolom `sort_order` untuk urutan.
- **CRUD Jabatan:** Tambah/edit/hapus jabatan dengan validasi relasi.
- **Dropdown Jabatan:** Form pegawai menggunakan select dari tabel positions.
- **Drag & Drop Reorder:** SortableJS + DataTables (tanpa pagination) untuk urutkan jabatan.
- **Seed Data:** 5 jabatan default (Kepala Divisi, Manager, Supervisor, Staff, Administrator).

### Phase 10: DataTables Integration 📊
- **DataTables:** Search, sort, pagination, responsive untuk semua tabel admin.
- **Bahasa Indonesia:** Menggunakan `id.json` dari CDN DataTables.
- **Inisialisasi:** DataTables + SortableJS via `initComplete` agar kompatibel.

### Phase 11: UI Refinement & Position Hierarchy 🎨
- **SweetAlert2:** Semua native `alert()`/`confirm()` diganti dengan SweetAlert2 (toast flash + confirm dialog).
- **jQuery & Swal di Head:** Pindah jQuery + SweetAlert2 CDN ke `<head>` agar tersedia saat inline script dijalankan.
- **Tombol Icon Only:** Tombol "Tambah" di admin hanya pakai icon `+` tanpa teks.
- **Sidebar Reorder:** Urutan sidebar: Kinerja, Jabatan, Pegawai, Kategori.
- **Sidebar Labels:** Sidebar disingkat (Kinerja Pegawai → Kinerja, Kelola Jabatan → Jabatan, dsb).
- **Footer:** Footer "Build with ❤ by Pengadilan Agama Medan & JOKI AI AGENT".
- **APP_NAME:** Diganti dari "Laravel" ke "GTD App".
- **Kolom Deskripsi:** Dihapus dari tabel Kelola Jabatan.
- **Position Hierarchy Level:** Kolom `level` (integer) untuk urutan hirarki jabatan.
- **Position Parent (Many-to-Many):** Relasi many-to-many via tabel pivot `position_parent` agar satu jabatan bisa memiliki banyak atasan langsung.
- **Seed Data:** Data jabatan diisi contoh struktur pengadilan (Ketua, Wakil Ketua, Hakim, Panitera, Sekretaris, Panitera Muda, Kepala Sub Bagian, dll) dengan relasi parent yang sesuai.

### Phase 12: UI Polish & Login NIP 🔧
- **Logo Rebrand:** Logo Laravel diganti logo GTD App (checkbox + teks) di login & sidebar.
- **Favicon GTD App:** Favicon ganti icon double-check biru.
- **Form Jabatan Vertikal:** Level & Bawahan dari disusun ke bawah (tidak grid 2 kolom) agar lebih lega.
- **Select2:** Dropdown Jabatan & Atasan pakai Select2 dengan pencarian.
- **Fix Date Input:** Tanggal Lahir, TMT Jabatan, TMT Golongan tidak muncul di edit karena format ISO, dipotong jadi YYYY-MM-DD.
- **Preview Foto:** Tambah/Edit pegawai bisa preview foto sebelum upload.
- **Kolom Foto:** Kolom foto ditambahkan di tabel Kelola Pegawai.
- **Nama Lengkap:** Menampilkan gelar depan/belakang di tabel.
- **Login NIP:** Login menggunakan NIP + password (bukan email).
- **Pesan Error:** Error login diganti Bahasa Indonesia.
- **Hapus Kolom Atasan:** Atasan dihapus dari form pegawai (cukup dari hirarki jabatan).

### Phase 13: Multi-Level Hierarchy & Full Visibility 👁️
- **Descendant Traversal:** Method `descendantIds()` & `ancestorIds()` di Position model untuk traverse tree jabatan.
- **All Subordinates:** Method `allSubordinates()` di Employee model ambil semua pegawai di posisi descendant.
- **Multi-Level Task View:** Atasan lihat tugas semua level bawahan (tidak hanya langsung).
- **Manager Dashboard:** View `dashboard-atasan.blade.php` menampilkan semua tugas tim di seluruh level.
- **Assignment Validation:** Bisa assign tugas ke bawahan di level mana pun dalam hirarki.

### Phase 14: Notifikasi In-App & Messenger 💬
- **Notifications Table:** Migrasi tabel `notifications` bawaan Laravel.
- **TaskAssigned Notification:** Notifikasi database ketika tugas didelegasikan + bell merah di header.
- **Polling Notifikasi:** JS polling tiap 30 detik untuk update notifikasi realtime.
- **SMTP Gmail:** Konfigurasi mailer SMTP Gmail untuk pengiriman email sungguhan.
- **TaskStatusUpdated Notification:** Notifikasi + email ke atasan saat bawahan mengubah status tugas (Inbox→Progress→Done).
- **Messenger Feature:**
  - 4 tabel baru: `conversations`, `conversation_participants`, `messages`, `message_attachments`
  - UI seperti Facebook Messenger (sidebar chat + chat area)
  - Kirim teks + multiple file (gambar, video, PDF, doc, zip)
  - Polling pesan tiap 3 detik, polling percakapan tiap 15 detik
  - Unread badge di sidebar navigasi
  - Cari pegawai & mulai chat baru
- **Perbaikan Sidebar Admin:**
  - Menu "Jabatan" & "Kategori" jadi submenu "Setting"
  - Urutan: Admin → Pegawai → Kinerja → Setting (Jabatan, Kategori)
- **Perbaikan Sorting Tabel:**
  - Employee table diurutkan berdasarkan posisi (level → sort_order → nama)
  - DataTables di-set `ordering: false` karena sorting sudah diatur sendiri
- **Perbaikan Logout Admin:** Guard `admin` sekarang di-logout via route `admin.logout` (sebelumnya pakai guard employee).

### Phase 15: Advanced Analytics Dashboard 📊
- **Backend Analytics:** Method `analytics()` di AdminController dengan 7 metrik: total tasks, completion rate, overdue count, monthly trends, status distribution, priority distribution, dan kinerja per jabatan.
- **Interactive Charts (Chart.js):** 5 grafik real-time — doughnut (status & prioritas), line (tren 12 bulan), bar horizontal (completion rate per pegawai & per jabatan).
- **Top Performers:** Ranking 5 pegawai dengan completion rate tertinggi + badge juara.
- **Overdue Tracking:** Daftar 5 pegawai paling banyak tugas terlambat + skor on-time rate.
- **Detail Table:** Tabel DataTables dengan progress bar completion + on-time rate per pegawai.

### Phase 16: Laporan Kinerja Pegawai per Periode 🖨️
- **Report Page:** Halaman laporan dengan filter tanggal (start_date / end_date, default bulan berjalan).
- **Period Filter:** Form filter tanggal dengan tampilan data per pegawai (Inbox, In Progress, Done, Total, % Selesai).
- **Summary Cards:** 5 kartu statistik (Pegawai Aktif, Inbox, Progress, Done, Total).
- **Print-Friendly:** Tombol Cetak dengan CSS `@media print` (sembunyikan sidebar, header, footer, tombol).
- **DataTables Print Fix:** Handler `onbeforeprint`/`onafterprint` untuk destroy/re-init DataTables agar seluruh data tercetak tidak hanya halaman aktif.

### Phase 17: Fitur Akun Pegawai (5 Fitur) 👤
- **Dashboard Statistik Pribadi:** 5 kartu statistik (Total, Selesai, Pending, Terlambat, Completion Rate) di atas Kanban board pegawai.
- **Upload/Ubah Foto Profil:** Upload foto dari halaman profil pegawai (Preview sebelum upload, format JPG/PNG max 2MB).
- **Riwayat Tugas Selesai:** Halaman `/tasks/history` — arsip tugas Done dengan pagination, filter prioritas & kategori.
- **Lihat Struktur Hirarki:** Modal dari sidebar menampilkan Atasan (posisi + pegawai) → Saya → Bawahan Langsung.
- **Filter Tugas by Periode:** Form filter Tanggal Awal - Akhir di atas Kanban, dengan tombol Reset.

### Phase 18: Notifikasi Email Messenger 📧
- **NewMessageMail:** Mailable class untuk notifikasi pesan baru dengan template HTML responsif (gradient biru-ungu).
- **Email Trigger:** Kirim email otomatis ke penerima saat pesan dikirim melalui Messenger.
- **Template Email:** Menampilkan nama pengirim, isi pesan (atau "(Mengirim file/lampiran)"), dan tombol "Buka Pesan".

### Phase 19: Fix Atasan Lihat Tugas Bawahan & Verifikasi Link 🧪
- **Opsi A - Dashboard Tim:** Sidebar menampilkan menu **"Dashboard Tim"** (route `tasks.manager-dashboard`) yang hanya muncul untuk user yang punya bawahan (`allSubordinates()->isNotEmpty()`).
- **Menu "Tim Saya" Lengkap:** Sidebar kini memakai `allSubordinates()` (semua level) + link `route('dashboard', ['employee_id' => ...])` per bawahan.
- **Opsi B - Filter employee_id:** `TaskController::index()` memproses `employee_id` — jika menunjuk bawahan, menampilkan hanya tugas pegawai itu; jika ID invalid/non-bawahan → fallback ke tugas sendiri (tidak membocorkan tugas pegawai lain).
- **Banner Tampilan:** Banner biru "Menampilkan tugas: {nama}" + jabatan + tombol "Kembali ke Tugas Saya" saat membuka tugas bawahan.
- **Opsi C - Otorisasi dashboard():** Admin diarahkan ke `admin.performance`; user tanpa bawahan di-redirect ke `tasks.index` dengan flash info.
- **Verifikasi End-to-End:** Login NIP + HTTP nyata (dashboard tim 200, filter bawahan 200 + banner, filter non-bawahan fallback tanpa banner). `php -l` & kompilasi semua blade OK.
- **Audit Link/Redirect:** Uji seluruh GET route (employee + admin) → semua 200; 404 hanya artefak ekstraksi URL CDN; 405 = form action valid; aset build, foto profil (storage), dan semua CDN eksternal 200. Tidak ada link yang menghasilkan "Not Found"/"can't be reached".

### Phase 20: Skor Kinerja Bulanan, Status Online Messenger & Notifikasi Pesan 🔔
- **Urutan Kinerja = Urutan Pegawai:** `AdminController::performance()` diurutkan hirarki jabatan (level → sort_order → nama), sama dengan halaman Kelola Pegawai.
- **Skor Kinerja Bulanan:** Kartu Kinerja Pegawai menampilkan skor 0–100 = persentase tugas selesai dari tugas yang dibuat pada bulan berjalan (tugas bulan ini), label Baik (≥70) / Cukup (≥40) / Kurang (<40). Pegawai tanpa tugas bulan ini tetap tampil dengan skor 0 + catatan.
- **Urutan "Tim Saya":** `Employee::allSubordinates()` kini diurutkan hirarki jabatan + eager load `positionData` (dropdown filter, Dashboard Tim, dan modal hirarki ikut terurut).
- **Messenger - Semua Pegawai + Status Online:** Sidebar messenger menampilkan semua pegawai (kecuali diri sendiri, urut hirarki jabatan) dengan titik hijau/abu + label Online/Offline. Deteksi online dari tabel `sessions` (guard `web`, aktif ≤5 menit, payload di-decode) sehingga session admin tidak terhitung. Endpoint `GET /messenger/employees` refresh status tiap 15 detik.
- **Notifikasi In-App Pesan Baru:** `App\Notifications\NewMessage` (channel database) dikirim ke penerima tiap pesan masuk; ikon lonceng dibedakan — komentar hijau (pesan) vs daftar biru (tugas).
- **Email & WhatsApp afterResponse:** Pengiriman email (`NewMessageMail`) dan WhatsApp dipindah ke `dispatch()->afterResponse()` + `try/catch` + log error — pengiriman chat tidak menunggu SMTP dan kegagalan kirim tidak lagi memicu error 500 (sebelumnya bisa menyebabkan pesan dobel).
- **Dashboard Tim - Filter Bulan:** Tabel "Semua Tugas Tim" hanya menampilkan tugas yang dibuat pada bulan berjalan (default), dengan navigasi bulan: tombol prev/next, pemilih bulan (`<input type="month">` auto-submit), dan tombol "Bulan Ini". Parameter `?month=YYYY-MM` divalidasi ketat; bulan kosong menampilkan pesan + navigasi tetap tersedia. Kartu statistik pending/selesai per bawahan tetap akumulatif.
- **Select2 "Tugas Untuk":** Dropdown pilih pegawai di halaman Buat Tugas Baru memakai Select2 (dengan kotak pencarian bawaan) — bisa diketik untuk mencari nama pegawai; mengikuti pola yang sudah dipakai di Kelola Pegawai. Opsi "Diri Sendiri" tetap default.
- **Tombol Kanban Sesuai Hak Akses:** Tombol ✏️ Edit dan 🗑️ Hapus pada kartu Kanban kini hanya tampil untuk pembuat tugas (`created_by === Auth::id()`), konsisten dengan otorisasi server (halaman detail sudah seperti ini dari awal). Bawahan non-pembuat tidak lagi melihat tombol yang akan gagal saat dipakai; mereka tetap bisa melihat detail & mengubah status (Inbox ↔ In Progress). Aturan penandaan `Done` (hanya pembuat/atasan) tidak diubah sesuai permintaan.
- **Kunci Tugas Selesai (Done = Permanen):** Tugas berstatus `Done` terkunci penuh — `updateStatus` (termasuk mengembalikan ke In Progress), `updateTask`, dan `destroy` ditolak server untuk semua pengguna termasuk pembuat dengan pesan "...terkunci...". UI mengikuti: dropdown status di Kanban diganti ikon 🔒 + tooltip, tombol Edit/Hapus serta tombol "Tandai Selesai"/"Kembalikan ke In Progress" disembunyikan pada tugas Done. Halaman detail menampilkan catatan terkunci — atasan diarahkan "buat tugas baru untuk pegawai ini" bila hasil kurang tepat, penerima diarahkan menghubungi atasan. Koreksi hasil kerja dilakukan lewat tugas baru (kebijakan kantor).
- **Catatan WhatsApp:** `WhatsAppService` belum aktif karena `WHATSAPP_ENABLED` belum diset di `.env`.

### Phase 21: Deploy Update ke VPS Produksi 🚀
- **Sinkronisasi Kode:** Seluruh update Phase 20 dikirim dari lokal (`/var/www/html/gtd`) ke VPS (`root@103.133.222.157:/var/www/html/gtd`) via `rsync` over SSH — hanya kode aplikasi, view, route, dan aset build. Terverifikasi: md5 semua 15 file kunci sama persis antara lokal & VPS.
- **Yang Tidak Disentuh:** `.env`, `vendor/`, `node_modules/`, `storage/` (upload foto profil & lampiran chat), `db/`, serta file adaptasi subfolder VPS (`index.php` + `.htaccess` root — berbeda dari lokal karena VPS diakses lewat `/gtd`). Data DB VPS = data produksi — tidak ditimpa (tidak ada migrasi baru).
- **Backup Sebelum Sync:** Arsip kode + `mysqldump gtd_db` disimpan di `/root/gtd-backup-20261017-1639/` di VPS sebelum perubahan.
- **Pasca-Sync:** `php artisan optimize:clear` → `php artisan migrate --force` (Nothing to migrate) → restart `php8.3-fpm` (flush opcache) → verifikasi login HTTP 200 + aset build baru (`app-xXWBvToK.css`) termaut.
- **Dokumentasi Prosedur:** `DEPLOY.md` (panduan lengkap, verifikasi, dan rollback).

### Phase 22: Filter Bulan di Halaman Kinerja Pegawai (Admin) 🗓️
- **Navigasi Periode:** Halaman `/admin/performance` kini punya navigasi bulan — tombol ◀ ▶, pemilih bulan (`<input type="month">` auto-submit), dan tombol "Bulan Ini" — mengikuti pola filter bulan di Dashboard Tim.
- **Skor Kinerja per Bulan:** Skor kinerja (persentase tugas `Done` dari tugas yang dibuat pada bulan terpilih) kini bisa dilihat untuk bulan mana pun, bukan hanya bulan berjalan. Kartu menampilkan label periode (mis. "Skor Kinerja (Agustus 2026)") + baris "X dari Y tugas selesai"; pegawai tanpa tugas pada bulan itu diberi catatan "Belum ada tugas pada {bulan}".
- **Validasi & Konsistensi:** Parameter `?month=YYYY-MM` divalidasi ketat (`^\d{4}-(0[1-9]|1[0-2])$`); parameter tidak valid fallback ke bulan berjalan. Kartu statistik Total/Pending/Selesai/Terlambat tetap akumulatif (konsisten dengan Dashboard Tim). Terverifikasi: hitungan tiap pegawai cocok 100% dengan query DB untuk 3 bulan uji (Mei/Agu/Okt 2026, 62 pegawai).
- **Tanpa Rebuild Aset:** Seluruh class CSS yang dipakai sudah ada di bundle (`app-xXWBvToK.css`) — tidak perlu `npm run build`.
- **Deploy ke VPS:** File yang berubah disinkronkan ke VPS → `php artisan optimize:clear` → restart `php8.3-fpm` → verifikasi render + checksum.

---

## ✅ Current Features
- [x] **Category Management:** Buat, lihat, dan hapus kategori dengan custom warna.
- [x] **Task Management:** CRUD tugas lengkap dengan deskripsi, prioritas, dan due date.
- [x] **GTD Workflow:** Perpindahan status tugas (Inbox $\rightarrow$ In Progress $\rightarrow$ Done).
- [x] **Kanban View:** Tampilan kolom yang memudahkan monitoring progres kerja.
- [x] **Responsive UI:** Navigasi sidebar dengan menu burger untuk perangkat mobile.
- [x] **Notification Engine:** Infrastruktur pengiriman email pengingat pagi.
- [x] **Data Seeding (Tasks):** Pengisian data awal untuk testing fungsionalitas.
- [x] **Employee Hierarchy System:** Manajemen struktur organisasi atasan-bawahan.
- [x] **Role-Based Task Assignment:** Atasan assign tugas ke bawahan dengan validasi hierarki.
- [x] **Auto Email Notification:** Notifikasi otomatis saat tugas baru di-assign.
- [x] **Daily Morning Reminder:** Pengingat harian via email per-employee (terjadwal 07:00).
- [x] **Admin Panel:** Role admin dengan akses manajemen pegawai, kategori, jabatan.
- [x] **Admin Middleware:** Proteksi route admin + redirect admin dari halaman tugas.
- [x] **Kinerja Pegawai:** Dashboard statistik produktivitas per pegawai.
- [x] **Position Management:** CRUD jabatan dengan dropdown di form pegawai.
- [x] **Drag & Drop Reorder:** Urutkan jabatan via drag-and-drop (SortableJS).
- [x] **DataTables:** Search, sort, pagination pada semua tabel admin.
- [x] **SweetAlert2:** Notifikasi flash & konfirmasi hapus pakai SweetAlert2.
- [x] **Position Hierarchy:** Level & parent hirarki many-to-many untuk jabatan.
- [x] **Footer:** Informasi credit di footer aplikasi.
- [x] **Login NIP:** Autentikasi menggunakan NIP + password.
- [x] **Select2:** Dropdown Jabatan dengan pencarian di form pegawai.
- [x] **Preview Foto:** Pratinjau foto profil sebelum upload.
- [x] **Multi-Level Hierarchy:** Atasan lihat tugas semua level bawahan.
- [x] **Manager Dashboard:** Dashboard atasan dengan statistik & daftar tugas tim.
- [x] **In-App Notifications:** Notifikasi bell merah dengan polling 30 detik.
- [x] **SMTP Mailer:** Pengiriman email via SMTP Gmail.
- [x] **Status Update Notifications:** Notifikasi ke atasan saat bawahan update status tugas.
- [x] **Messenger:** Fitur chat pegawai dengan dukungan file/gambar/video.
- [x] **Admin Logout:** Logout terpisah untuk guard admin.
- [x] **Advanced Analytics Dashboard:** Grafik interaktif (Chart.js) dengan completion rate, tren bulanan, overdue tracking, dan kinerja per jabatan.
- [x] **Laporan Kinerja per Periode:** Filter tanggal + tabel rekap tugas (Inbox/Progress/Done) per pegawai + Print-friendly.
- [x] **Dashboard Statistik Pribadi:** Kartu statistik (Total, Selesai, Pending, Terlambat, Rate) di Kanban pegawai.
- [x] **Upload Foto Profil:** Upload & ubah foto profil dari halaman profil pegawai.
- [x] **Riwayat Tugas Selesai:** Halaman arsip tugas Done dengan pagination.
- [x] **Struktur Hirarki:** Modal lihat atasan & bawahan dari sidebar.
- [x] **Filter Tugas by Periode:** Filter tanggal di Kanban pegawai.
- [x] **Notifikasi Email Messenger:** Email otomatis saat menerima pesan baru.
- [x] **Dashboard Tim (Manager):** Menu khusus atasan menampilkan statistik & tugas semua level bawahan.
- [x] **Lihat Tugas Bawahan via Sidebar:** Filter `employee_id` dari menu "Tim Saya" dengan banner konteks.
- [x] **Otorisasi Akses Manager:** Admin & user tanpa bawahan diarahkan dengan benar.
- [x] **Audit Link & Redirect:** Seluruh route, aset, dan CDN terpantau OK (tanpa 404/error).

---

## 🚀 Future Roadmap (Yang Tinggal Dibuat)
- [ ] **Context Labels:** Penambahan label konteks (misal: `@computer`, `@phone`) untuk memfilter tugas berdasarkan lokasi/kondisi.
- [ ] **Dark Mode:** Dukungan tema gelap untuk kenyamanan mata saat ngoding malam 🌙.
- [ ] **Task Comments & Activity Log:** Diskusi di task tertentu + riwayat perubahan status.
- [ ] **Mobile App (PWA):** Aplikasi mobile-friendly atau Progressive Web App untuk akses dari HP.

---

## ⚙️ System Config
- **Server Port:** 8000
- **Database Name:** `gtd_db`
- **DB User:** `budi`
- **Base URL Lokal:** `http://localhost/gtd`
- **Base URL VPS (Produksi):** `http://103.133.222.157/gtd`
- **Panduan Update ke VPS:** lihat `DEPLOY.md` 🚀

**Dokumentasi ini dikelola oleh Joki AI.** 😎🔥

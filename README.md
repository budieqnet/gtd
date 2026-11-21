# GTD App — Get Things Done

**GTD App** adalah aplikasi manajemen tugas berbasis metodologi
**Getting Things Done (GTD)** — *Capture → Process → Do*. Dibangun dengan
Laravel untuk mengelola tugas, hierarki organisasi, dan kolaborasi tim
secara efisien.

## 🎯 Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **Kanban Board GTD** | Drag-and-drop: Inbox → In Progress → Done. Filter tanggal, prioritas, kategori. |
| **Hierarki Jabatan** | Level jabatan, parent many-to-many, delegasi tugas lintas level bawahan. |
| **Dual Auth & RBAC** | Employee login via **NIP** (guard `web`), Admin panel login via **username** (guard `admin`). Middleware terpisah. |
| **Notifikasi Real-time** | In-app (bell icon + polling 30 detik) + Email SMTP (Gmail). |
| **Messenger Internal** | Chat 1-1 & grup, lampiran file, unread badge, notifikasi email. |
| **Analytics & Laporan** | Chart.js: completion rate, on-time rate, laporan performa per periode (print-friendly). |
| **Profil Pegawai** | Foto profil, ubah password, riwayat tugas selesai. |
| **Manajemen Admin** | CRUD pegawai, admin, jabatan (drag-drop sort), kategori tugas. |

## ⚙️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Framework** | Laravel 11.x (PHP 8.3+) |
| **Database** | MySQL (`gtd_db`) |
| **Frontend** | Tailwind CSS (CDN), Blade, jQuery, SweetAlert2, DataTables, Chart.js, SortableJS, Select2 |
| **Auth** | Laravel Breeze (dual guard: `web` & `admin`) |
| **Email** | SMTP Gmail (config di `.env`) |
| **Queue/Jobs** | Database driver (daily reminder, notifications) |
| **Testing** | PHPUnit (Feature + Unit) |

## 🗄️ Database Schema

| Tabel | Fungsi |
|-------|--------|
| `employees` | Pegawai (NIP, password, posisi, foto, superior) |
| `admins` | Akun admin panel |
| `positions` | Jabatan + hierarki (level, parent many-to-many) |
| `position_parent` | Pivot hierarki jabatan |
| `categories` | Kategori tugas (nama, warna, ikon) |
| `tasks` | Tugas (status, prioritas, assigned_to, due_date, created_by) |
| `notifications` | Notifikasi in-app (Laravel Notifications) |
| `conversations` | Percakapan messenger |
| `conversation_participants` | Peserta percakapan |
| `messages` | Pesan messenger |
| `message_attachments` | Lampiran pesan |

## 🧩 Model & Relasi Utama

```php
Employee → allSubordinates()          // Rekursif: semua bawahan (langsung & tidak langsung)
Employee → superior()                 // Atasan langsung
Employee → position()                 // Jabatan (belongsTo Position)
Position → descendantIds()            // Semua jabatan di bawah (rekursif)
Position → ancestorIds()              // Semua jabatan di atas (rekursif)
Task → belongsTo Category, Employee (assignedTo), Employee (createdBy)
Category → hasMany Task
Conversation → participants (many-to-many), messages
Message → belongsTo Conversation, hasMany MessageAttachment
```

## 🌐 Route Utama

### Pegawai (auth + verified, login via NIP)

| Method | URI | Nama Route | Fungsi |
|--------|-----|------------|--------|
| GET | `/dashboard` | `dashboard` | Halaman utama pegawai |
| GET | `/tasks` | `tasks.index` | Kanban board GTD |
| GET/POST | `/tasks/create` | `tasks.create/store` | Buat tugas baru |
| PATCH | `/tasks/{task}/status` | `tasks.update-status` | Ubah status tugas (drag-drop) |
| PUT | `/tasks/{task}` | `tasks.update` | Edit tugas |
| DELETE | `/tasks/{task}` | `tasks.destroy` | Hapus tugas |
| GET | `/tasks/manager/dashboard` | `tasks.manager-dashboard` | Dashboard atasan (semua level bawahan) |
| GET | `/tasks/history` | `tasks.history` | Riwayat tugas selesai |
| GET/PATCH/DELETE | `/profile` | `profile.*` | Kelola profil & foto |
| GET/POST | `/notifications/*` | `notifications.*` | Notifikasi in-app (data, read, read-all) |
| GET/POST | `/messenger*` | `messenger.*` | Chat antar pegawai |

### Admin (auth guard `admin`, prefix `/admin`)

| URI | Nama Route | Fungsi |
|-----|------------|--------|
| `/admin` | `admin.dashboard` | Dashboard admin |
| `/admin/employees` | `admin.employees` | Kelola pegawai (CRUD) |
| `/admin/admins` | `admin.admins` | Kelola akun admin |
| `/admin/positions` | `admin.positions` | Kelola jabatan (CRUD + drag-drop sort) |
| `/admin/categories` | `admin.categories` | Kelola kategori tugas |
| `/admin/performance` | `admin.performance` | Kinerja pegawai |
| `/admin/analytics` | `admin.analytics` | Analytics grafik (Chart.js) |
| `/admin/reports/performance` | `admin.reports.performance` | Laporan kinerja per periode (print) |

## 🚀 Cara Menjalankan

```bash
# 1. Clone repository
git clone https://github.com/budieqnet/gtd.git
cd gtd

# 2. Install dependencies
composer install
npm install && npm run build   # optional: untuk Vite/Tailwind build

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi .env (database & email)
# DB_DATABASE=gtd_db
# DB_USERNAME=budi
# DB_PASSWORD=your_password
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=your_email@gmail.com
# MAIL_PASSWORD=your_app_password
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS=your_email@gmail.com

# 5. Migrasi & seeding
php artisan migrate --seed

# 6. Jalankan server
php artisan serve          # http://localhost:8000

# 7. (Opsional) Jalankan queue worker untuk notifikasi & reminder
php artisan queue:work
```

> **Catatan:** Database `gtd_db` harus sudah dibuat di MySQL. User `budi`
> perlu akses penuh. Email menggunakan SMTP Gmail — gunakan *App Password*
> bukan password utama.

## 📁 Struktur Proyek Penting

```text
app/
├── Http/Controllers/
│   ├── TaskController.php      # Logika tugas & kanban
│   ├── AdminController.php     # Admin panel CRUD
│   ├── MessengerController.php # Chat internal
│   └── ProfileController.php   # Profil pegawai
├── Models/
│   ├── Employee.php            # Pegawai + hierarki
│   ├── Position.php            # Jabatan + tree hierarchy
│   ├── Task.php                # Tugas GTD
│   ├── Category.php            # Kategori tugas
│   ├── Conversation.php        # Percakapan
│   └── Message.php             # Pesan
├── Services/
│   └── WhatsAppService.php     # Integrasi WhatsApp (opsional)
└── Notifications/              # Notifikasi email & database

resources/views/
├── tasks/index.blade.php       # Kanban board utama
├── tasks/dashboard-atasan.blade.php
├── admin/                      # Semua view admin panel
├── messenger/index.blade.php   # Chat UI
└── emails/                     # Template email notifikasi

routes/
├── web.php                     # Route pegawai + admin
└── auth.php                    # Route auth (Breeze)
```

## 📚 Dokumentasi Tambahan

| File | Deskripsi |
|------|-----------|
| [`INDEX.md`](./INDEX.md) | Navigasi cepat seluruh proyek (tech stack, route, model, view) |
| [`PROJECT_HISTORY.md`](./PROJECT_HISTORY.md) | Riwayat pengembangan per fase + daftar fitur lengkap |
| [`ACCOUNTS.md`](./ACCOUNTS.md) | Daftar akun pegawai/admin untuk testing |
| [`DEPLOY.md`](./DEPLOY.md) | Panduan deployment production |
| [`joki-bug.md`](./joki-bug.md) | Catatan bug & solusi selama development |

## 🔐 Akun Default (Setelah `migrate --seed`)

| Role | Login | Password |
|------|-------|----------|
| Admin | `admin` | `password` |
| Employee | NIP dari seeder | `password` |

> Lihat [`ACCOUNTS.md`](./ACCOUNTS.md) untuk daftar lengkap NIP pegawai.

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Atau spesifik
php artisan test --filter=TaskTest
php artisan test tests/Feature/Auth/
```

## 📄 License

Proyek ini open-source di bawah lisensi **MIT**. Silakan gunakan,
modifikasi, dan distribusikan sesuai kebutuhan.

---

**Dibangun dengan ❤️ menggunakan Laravel** — *Framework PHP untuk Web Artisans*

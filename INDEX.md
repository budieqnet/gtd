# 📌 INDEX — GTD App (Get Things Done)

Dokumen index ringkas untuk navigasi cepat seluruh proyek **GTD App**, aplikasi manajemen tugas berbasis metodologi *Getting Things Done* (Capture → Process → Do).

## 🧭 Isi Proyek

| Bagian | Lokasi | Keterangan |
| --- | --- | --- |
| Dokumentasi lengkap | [`PROJECT_HISTORY.md`](./PROJECT_HISTORY.md) | Riwayat pengembangan per fase + daftar fitur |
| Akun & kredensial | [`ACCOUNTS.md`](./ACCOUNTS.md) | Daftar akun pegawai/admin untuk testing |
| Catatan teknis | [`joki-bug.md`](./joki-bug.md) | Kumpulan bug & solusinya |
| Root route | [`routes/web.php`](./routes/web.php) | Semua route aplikasi |
| Migrasi DB | [`database/migrations/`](./database/migrations/) | Struktur seluruh tabel |
| Model | [`app/Models/`](./app/Models/) | ORM Eloquent |
| Controller | [`app/Http/Controllers/`](./app/Http/Controllers/) | Logika bisnis |
| Views | [`resources/views/`](./resources/views/) | Blade template |
| Email template | [`resources/views/emails/`](./resources/views/emails/) | Template notifikasi email |

---

## ⚙️ Tech Stack

- **Framework:** Laravel (PHP 8.3.x)
- **Database:** MySQL (`gtd_db`, user `budi`)
- **Frontend:** Tailwind CSS (CDN), Blade, jQuery, SweetAlert2, DataTables, Chart.js, SortableJS, Select2
- **Auth:** Laravel Breeze (guard `web` untuk Employee via NIP, guard `admin` untuk Admin)

---

## 🗄️ Database (Tabel)

| Tabel | Fungsi |
| --- | --- |
| `employees` | Pegawai (NIP, password, posisi, foto) |
| `admins` | Akun admin panel |
| `positions` | Jabatan + hierarki (level, parent many-to-many) |
| `position_parent` | Pivot hierarki jabatan |
| `categories` | Kategori tugas (nama, warna, ikon) |
| `tasks` | Tugas (status, prioritas, assigned_to, due_date) |
| `notifications` | Notifikasi in-app |
| `conversations` | Percakapan messenger |
| `conversation_participants` | Peserta percakapan |
| `messages` | Pesan messenger |
| `message_attachments` | Lampiran pesan |

---

## 🧩 Model & Relasi Utama

| Model | Relasi kunci |
| --- | --- |
| `Employee` | `allSubordinates()`, `superior`, posisi, tugas (`assignedTo`) |
| `Position` | `descendantIds()`, `ancestorIds()`, parent many-to-many |
| `Task` | belongsTo `Category`, `Employee` (assigned/created) |
| `Category` | hasMany `Task` |
| `Conversation` | participants (many-to-many via pivot), messages |
| `Message` | belongsTo `Conversation`, hasMany `MessageAttachment` |

---

## 🌐 Route Utama

### Pegawai (auth + verified, login via NIP)
| Method | URI | Nama | Fungsi |
| --- | --- | --- | --- |
| GET | `/dashboard` | `dashboard` | Halaman utama pegawai |
| GET | `/tasks` | `tasks.index` | Kanban board |
| GET/POST | `/tasks/create` | `tasks.create/store` | Buat tugas |
| PATCH | `/tasks/{task}/status` | `tasks.update-status` | Ubah status |
| GET | `/tasks/manager/dashboard` | `tasks.manager-dashboard` | Dashboard atasan (semua level bawahan) |
| GET | `/tasks/history` | `tasks.history` | Riwayat tugas selesai |
| GET/POST | `/profile` | `profile.*` | Kelola profil & foto |
| GET/POST | `/notifications/*` | `notifications.*` | Notifikasi in-app |
| GET/POST | `/messenger*` | `messenger.*` | Chat antar pegawai |

### Admin (auth guard `admin`, prefix `/admin`)
| URI | Nama | Fungsi |
| --- | --- | --- |
| `/admin` | `admin.dashboard` | Dashboard admin |
| `/admin/employees` | `admin.employees` | Kelola pegawai (CRUD) |
| `/admin/admins` | `admin.admins` | Kelola akun admin |
| `/admin/positions` | `admin.positions` | Kelola jabatan (CRUD + drag-drop) |
| `/admin/categories` | `admin.categories` | Kelola kategori |
| `/admin/performance` | `admin.performance` | Kinerja pegawai |
| `/admin/analytics` | `admin.analytics` | Analytics grafik (Chart.js) |
| `/admin/reports/performance` | `admin.reports.performance` | Laporan kinerja per periode (print) |

---

## 📄 Views Utama

- **Layout:** `layouts/app.blade.php` (pegawai), `layouts/admin.blade.php` (admin), `layouts/guest.blade.php` (auth)
- **Auth:** `auth/login.blade.php` (login NIP), register, forgot/reset password, verify email
- **Pegawai:** `tasks/index.blade.php` (kanban), `tasks/dashboard-atasan.blade.php`, `tasks/history.blade.php`, `profile/edit.blade.php`, `messenger/index.blade.php`
- **Admin:** `admin/` — dashboard, employees, admins, positions, categories, performance, analytics, report-performance
- **Email:** `emails/daily_reminder`, `task_assigned`, `task_status_updated`, `new_message`

---

## ✅ Fitur Utama

1. **Kanban GTD** — Inbox → In Progress → Done, filter tanggal/prioritas/kategori.
2. **Hierarki Jabatan** — Level jabatan, parent many-to-many, tugas lintas level bawahan.
3. **Role & RBAC** — Employee (NIP) vs Admin panel, middleware terpisah.
4. **Notifikasi** — In-app (bell + polling 30 detik) & email SMTP.
5. **Messenger** — Chat + lampiran file, unread badge, email notifikasi.
6. **Analytics & Laporan** — Chart.js, completion rate, on-time rate, laporan periode (print-friendly).
7. **Profil Pegawai** — Foto profil, ubah password, riwayat tugas selesai.

---

## 🚀 Cara Menjalankan

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan serve          # http://localhost:8000
```

> Konfigurasi `.env` aktif memakai MySQL `gtd_db`. Email via SMTP Gmail (lihat `PROJECT_HISTORY.md` Phase 14).

---

## 📚 Dokumen Terkait

- [PROJECT_HISTORY.md](./PROJECT_HISTORY.md) — detail semua fase pengembangan
- [ACCOUNTS.md](./ACCOUNTS.md) — akun untuk testing
- [README.md](./README.md) — README bawaan Laravel

_Dokumen ini dihasilkan oleh Joki AI Agent._

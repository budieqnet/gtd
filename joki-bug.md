# 🚩 Daftar Temuan Bug GTD App

Berikut adalah hasil analisa potensi bug dan celah keamanan yang ditemukan oleh Joki.

## 🚨 High Priority
### 1. Insecure Direct Object Reference (IDOR) pada Manajemen Tugas
- **Lokasi:** `TaskController` (`updateStatus`, `updateTask`, `destroy`)
- **Deskripsi:** Pengecekan hak akses saat ini mengizinkan siapa pun yang menjadi `assigned_to` (bawahan) untuk mengubah detail tugas, termasuk judul, deskripsi, dan prioritas.
- **Dampak:** Bawahan dapat memodifikasi instruksi tugas atau mengubah deadline tanpa sepengetahuan atasan.
- **Rekomendasi:** Pisahkan hak akses. Bawahan hanya boleh mengakses `updateStatus`, sedangkan `updateTask` dan `destroy` hanya boleh diakses oleh `created_by` (atasan).

## ⚠️ Medium Priority
### 2. N+1 Query Problem pada Dashboard Atasan
- **Lokasi:** `TaskController::dashboard()`
- **Deskripsi:** Terdapat query `Task::where('assigned_to', $sub->id)->get()` di dalam loop `foreach ($subordinates as $sub)`.
- **Dampak:** Penurunan performa yang signifikan (latency tinggi) seiring bertambahnya jumlah bawahan.
- **Rekomendasi:** Gunakan Eager Loading atau agregasi query (misal: `withCount`) untuk mengambil statistik tugas dalam satu atau dua query saja.

## ℹ️ Low Priority
### 3. Validasi Assign Tugas
- **Lokasi:** `TaskController::store()`
- **Deskripsi:** Validasi `assigned_to` hanya mengecek keberadaan ID dalam array subordinates, namun tidak secara eksplisit memvalidasi keberadaan ID tersebut di tabel `employees` melalui request validation (meskipun secara logika aman karena data diambil dari database).
- **Dampak:** Potensi error minor jika terjadi inkonsistensi data.
- **Rekomendasi:** Tambahkan rule `exists:employees,id` pada validasi `assigned_to`.

---
*Dicatat oleh Joki AI Agent* 🚀*

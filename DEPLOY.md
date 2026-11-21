# 🚀 Panduan Deploy Update GTD ke VPS

Panduan ini dipakai untuk mengirim update aplikasi GTD dari komputer lokal ke VPS produksi.

| | Lokal (Development) | VPS (Produksi) |
|---|---|---|
| Path | `/var/www/html/gtd` | `/var/www/html/gtd` |
| URL | `http://localhost/gtd` | `http://103.133.222.157/gtd` |
| SSH | — | `root@103.133.222.157` |
| PHP | 8.4 | 8.3 (`php8.3-fpm` + Apache) |
| MySQL | `gtd_db` (user `budi`) | `gtd_db` (user `budi`) |

> ⚠️ **Prinsip utama:** update hanya menimpa **kode aplikasi, view, route, dan aset build**.
> Jangan pernah menimpa `.env`, `vendor/`, `node_modules/`, `storage/` (berisi foto profil & lampiran
> chat), `db/`, serta file adaptasi subfolder VPS (`index.php` & `.htaccess` di root — keduanya
> berbeda dari lokal karena VPS diakses lewat URL `/gtd`).
>
> ⚠️ **Database VPS = data produksi.** Jangan impor DB lokal ke VPS. Migrasi hanya
> menambah/mengubah struktur tabel, bukan menimpa data.

## Alat yang Dibutuhkan
- `sshpass` dan `rsync` (v3.1+ agar mendukung `--chown`)
- Password SSH VPS (ada di `akun.txt` — jangan disebarkan)

---

## Langkah 1 — Backup di VPS (WAJIB sebelum sync)

```bash
ssh root@103.133.222.157
D=$(date +%Y%m%d-%H%M)
mkdir -p /root/gtd-backup-$D
cd /var/www/html/gtd
tar czf /root/gtd-backup-$D/gtd-code-sebelum-update.tar.gz --exclude=./vendor --exclude=./node_modules .
MYSQL_PWD='<password mysql vps>' mysqldump -ubudi gtd_db > /root/gtd-backup-$D/gtd_db-sebelum-update.sql
ls -la /root/gtd-backup-$D/
```

---

## Langkah 2 — Sinkronisasi Kode (dari lokal)

Jika ada perubahan CSS/JS/Tailwind, jalankan dulu di lokal: `npm run build`

```bash
cd /var/www/html/gtd
SSHPASS='<password vps>' sshpass -e rsync -a --chown=1000:1000 --itemize-changes \
  --exclude='.env'          --exclude='/vendor/'        --exclude='/node_modules/' \
  --exclude='/storage/'     --exclude='/db/'            --exclude='hot' \
  --exclude='/build'        --exclude='/public/build/'  --exclude='/.git/' \
  --exclude='/index.php'    --exclude='/.htaccess'      --exclude='/bootstrap/cache/' \
  /var/www/html/gtd/ root@103.133.222.157:/var/www/html/gtd/
```

Tanpa `--delete`, jadi file yang hanya ada di VPS (mis. `akun.txt`, `ACCOUNTS.md`, `db/`)
tidak akan ikut terhapus.

## Langkah 3 — Sinkronisasi Aset Build (`public/build`)

```bash
SSHPASS='<password vps>' sshpass -e rsync -a --chown=1000:1000 --itemize-changes \
  /var/www/html/gtd/public/build/ root@103.133.222.157:/var/www/html/gtd/public/build/
```

---

## Langkah 4 — Pasca-Sync di VPS

```bash
ssh root@103.133.222.157
cd /var/www/html/gtd
php artisan optimize:clear     # bersihkan cache config/route/view
php artisan migrate --force    # jalankan migrasi baru (jika ada)
systemctl restart php8.3-fpm   # flush opcache agar kode baru aktif
```

---

## Langkah 5 — Verifikasi

```bash
curl -s -o /dev/null -w "login: %{http_code}\n" http://127.0.0.1/gtd/login
curl -s http://127.0.0.1/gtd/login | grep -o "build/assets/app-[A-Za-z0-9_-]*\.\(css\|js\)" | sort -u
tail -5 storage/logs/laravel.log
```

Lalu dari browser: buka `http://103.133.222.157/gtd`, login, dan uji fitur yang baru di-update.

Untuk memastikan file benar-benar sama, bandingkan checksum file kunci
(hitungan di VPS harus sama dengan di lokal):

```bash
md5sum app/Http/Controllers/TaskController.php resources/views/tasks/index.blade.php \
       app/Notifications/NewMessage.php routes/web.php public/build/manifest.json
```

---

## Rollback (jika terjadi masalah)

```bash
ssh root@103.133.222.157
cd /var/www/html/gtd
tar xzf /root/gtd-backup-<tanggal>/gtd-code-sebelum-update.tar.gz
MYSQL_PWD='<password mysql vps>' mysql -ubudi gtd_db < /root/gtd-backup-<tanggal>/gtd_db-sebelum-update.sql
php artisan optimize:clear && systemctl restart php8.3-fpm
```

---

## Troubleshooting Singkat

| Gejala | Solusi |
|---|---|
| Halaman 500 / blank | Cek `storage/logs/laravel.log`, jalankan `php artisan optimize:clear` |
| Aset CSS/JS 404 | Pastikan `public/build/manifest.json` ikut ter-sync & symlink `build -> public/build` ada di root proyek VPS |
| Kode lama masih tampil | `systemctl restart php8.3-fpm` (flush opcache), lalu hard-refresh browser (Ctrl+Shift+R) |
| Foto/lampiran hilang | Jangan sync folder `storage/`; cek `public/storage` symlink di VPS |

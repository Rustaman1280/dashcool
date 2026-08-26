# 🚀 Panduan Lengkap Deploy Dashcool ke Render.com

Panduan ini berisi langkah demi langkah untuk mendeploy aplikasi Laravel **Dashcool** ke platform cloud **Render.com** menggunakan **Docker (PHP 8.2 + Nginx + Node.js Vite)**.

---

## 📁 Struktur Konfigurasi yang Telah Disiapkan

File-file berikut sudah dibuat otomatis di repositori ini:
1. `Dockerfile`: Multi-stage build (Node.js untuk compile aset Vite, Composer untuk dependensi PHP, dan PHP 8.2-FPM + Nginx + Supervisor untuk production runtime).
2. `docker/nginx.conf.template`: Konfigurasi Nginx siap pakai dengan port dinamis Render (`$PORT`).
3. `docker/entrypoint.sh`: Menjalankan symlink storage, caching konfigurasi/rute, dan migrasi database otomatis.
4. `docker/supervisord.conf`: Mengelola proses Nginx dan PHP-FPM di dalam container.
5. `docker/php.ini`: Optimasi PHP & OPcache untuk kecepatan maksimal.
6. `render.yaml`: Blueprint konfigurasi otomatis untuk Render.
7. `.dockerignore`: Memastikan ukuran build image ramping dan efisien.

---

## 🛠️ Langkah-Langkah Deploy

### Langkah 1: Push Perubahan Terbaru ke GitHub

Pastikan semua file konfigurasi Docker dan perubahan terbaru sudah di-commit dan di-push ke GitHub:

```bash
git add .
git commit -m "feat: setup Docker and Render deployment configuration"
git push origin main
```

---

### Langkah 2: Siapkan Database Cloud

Karena Dashcool menggunakan **MySQL**, Anda membutuhkan database MySQL cloud yang bisa diakses publik oleh Render.

#### Rekomendasi Penyedia Database MySQL Gratis:
1. **TiDB Cloud (Serverless MySQL)**: [https://tidbcloud.com](https://tidbcloud.com) (Gratis 5GB, sangat stabil & kompatibel 100% dengan MySQL).
2. **Aiven for MySQL**: [https://aiven.io](https://aiven.io) (Free tier tersedia).
3. **Clever Cloud / Railway**: Mendukung MySQL free tier.

> **Catatan jika ingin menggunakan PostgreSQL bawaan Render:**
> Jika Anda membuat database PostgreSQL gratis langsung di Render (**New +** -> **PostgreSQL**), ubah `DB_CONNECTION` menjadi `pgsql`, dan sesuaikan `DB_PORT=5432`.

Simpan informasi koneksi database Anda:
- Host (`DB_HOST`)
- Port (`DB_PORT`, default 3306 atau 4000 untuk TiDB)
- Database Name (`DB_DATABASE`)
- Username (`DB_USERNAME`)
- Password (`DB_PASSWORD`)

---

### Langkah 3: Buat Web Service di Render.com

1. Buka [https://dashboard.render.com](https://dashboard.render.com) dan login/daftar.
2. Klik tombol **New +** di pojok kanan atas, lalu pilih **Web Service**.
3. Pilih opsi **"Build and deploy from a Git repository"** lalu klik **Next**.
4. Hubungkan akun GitHub Anda dan pilih repositori **`Dashcool`** (atau `Rustaman1280/dashcool`).
5. Isi konfigurasi dasar berikut:
   - **Name**: `dashcool` (atau nama pilihan Anda)
   - **Region**: `Singapore` (Paling dekat dengan Indonesia agar akses cepat)
   - **Branch**: `main`
   - **Language / Runtime**: **`Docker`** *(PENTING: Pilih Docker)*
   - **Dockerfile Path**: `./Dockerfile`
   - **Instance Type**: **`Free`**

---

### Langkah 4: Isi Environment Variables di Render

Scroll ke bagian **Environment Variables** (atau tab **Environment** pada dashboard service), lalu tambahkan variabel berikut:

| Key | Value | Keterangan |
| :--- | :--- | :--- |
| `APP_NAME` | `Dashcool` | Nama aplikasi |
| `APP_ENV` | `production` | Mode production |
| `APP_DEBUG` | `false` | Matikan debug untuk keamanan |
| `APP_KEY` | `base64:UpiUKjvNLD9LuwlyIPR99yTfgFLGo6s4EnsqFoTtWsQ=` | Kunci enkripsi Laravel (atau buat baru via `php artisan key:generate --show`) |
| `APP_URL` | `https://dashcool.onrender.com` | Sesuaikan dengan domain *.onrender.com Anda |
| `LOG_CHANNEL` | `stderr` | Agar log tampil di dashboard Render |
| `DB_CONNECTION` | `mysql` | Tipe database (`mysql` atau `pgsql`) |
| `DB_HOST` | `gateway01.ap-southeast-1.prod.aws.tidbcloud.com` | Host database cloud Anda |
| `DB_PORT` | `3306` | Port database (3306 / 4000 / 5432) |
| `DB_DATABASE` | `dashcool` | Nama database |
| `DB_USERNAME` | `dashcool_user` | User database |
| `DB_PASSWORD` | `password_anda` | Password database |
| `SESSION_DRIVER` | `database` | Penyimpanan session di database |
| `CACHE_STORE` | `database` | Cache di database |
| `QUEUE_CONNECTION` | `database` | Queue worker di database |
| `RUN_MIGRATIONS` | `true` | Otomatis jalankan `php artisan migrate --force` saat start |
| `RUN_SEEDER` | `true` | Jalankan `db:seed` pertama kali (ubah ke `false` setelah seeding sukses) |

---

### Langkah 5: Deploy & Selesai!

1. Klik tombol **Deploy Web Service** (atau **Create Web Service**).
2. Render akan mulai mengunduh repository, membangun image Docker (build node/vite, composer, setup nginx & php), dan menyalakan container.
3. Pantau log pada tab **Logs**.
4. Setelah muncul log `--> Starting Supervisor (Nginx + PHP-FPM)...` dan status berubah menjadi **Live (Hijau)**, klik URL web service Anda (contoh: `https://dashcool.onrender.com`).
5. Selamat! Aplikasi Dashcool sudah online dan dapat diakses publik.

---

## 💡 Troubleshooting & Tips

- **Migrasi Database Ulang**: Jika ingin menjalankan migrasi ulang atau seeder tertentu, Anda bisa memanfaatkan tab **Shell** di dashboard Render untuk menjalankan perintah artisan langsung:
  ```bash
  php artisan migrate --status
  php artisan db:seed
  ```
- **Custom Domain**: Anda dapat menghubungkan domain pribadi (misal: `spmb.sekolah.sch.id`) secara gratis di menu **Settings -> Custom Domains** pada dashboard Render.

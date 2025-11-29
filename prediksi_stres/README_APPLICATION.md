# Aplikasi Prediksi Stres Mahasiswa

Aplikasi web untuk memprediksi tingkat stres akademik mahasiswa menggunakan machine learning dan Explainable AI (XAI).

## Fitur

### User (Mahasiswa)
- **Isi Kuesioner Stres**: Mengisi kuesioner yang berisi 10 pertanyaan terkait faktor-faktor penyebab stres
- **Prediksi Tingkat Stres**: Sistem memproses data dan mengklasifikasikan tingkat stres ke dalam 3 kategori: Low, Moderate, dan High
- **Tampilkan Faktor Penyebab Utama**: Menampilkan analisis XAI mengenai faktor-faktor yang paling memengaruhi hasil prediksi
- **Lihat History Prediksi**: Melihat riwayat hasil prediksi sebelumnya

### Admin
- **Kelola Data Pengguna**: Melihat dan mengelola data pengguna yang telah menggunakan sistem
- **Cari Data Mahasiswa**: Mencari data pengguna berdasarkan nama, email, atau NIM
- **Lihat History Prediksi**: Melihat seluruh riwayat prediksi mahasiswa, termasuk hasil klasifikasi dan isi kuesioner

## Instalasi

1. Install dependencies:
```bash
composer install
npm install
```

2. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Setup database:
```bash
php artisan migrate
php artisan db:seed
```

4. Build assets:
```bash
npm run build
```

5. Jalankan server:
```bash
php artisan serve
```

## Akun Default

### Admin
- Email: `admin@example.com`
- Password: `password`

### User Test
- Email: `test@example.com`
- Password: `password`

## Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS 4.0
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Authentication**: Laravel Session-based Authentication

## Struktur Database

### Tables
- `users`: Data pengguna (user dan admin)
- `predictions`: Hasil prediksi tingkat stres
- `questionnaires`: Jawaban kuesioner
- `stress_factors`: Faktor penyebab stres (hasil XAI)

## Algoritma Prediksi

Aplikasi menggunakan algoritma sederhana untuk memprediksi tingkat stres berdasarkan:
- Beban akademik
- Jam tidur
- Dukungan sosial
- Stres finansial
- Manajemen waktu
- Kondisi kesehatan
- Masalah keluarga
- Status hubungan
- Lingkungan belajar
- Kecemasan masa depan

Skor dihitung berdasarkan bobot masing-masing faktor, kemudian diklasifikasikan menjadi:
- **Low**: Skor < 30
- **Moderate**: Skor 30-50
- **High**: Skor > 50

## Routes

### Public
- `/` - Home (redirect ke login/dashboard)
- `/login` - Halaman login
- `/register` - Halaman registrasi

### User (Auth Required)
- `/dashboard` - Dashboard user
- `/questionnaire` - Form kuesioner
- `/prediction/{id}/result` - Hasil prediksi
- `/history` - History prediksi

### Admin (Auth Required)
- `/admin/dashboard` - Dashboard admin
- `/admin/users` - Daftar pengguna
- `/admin/users/{id}` - Detail pengguna
- `/admin/predictions` - Daftar prediksi
- `/admin/predictions/{id}` - Detail prediksi

## Catatan

- Algoritma prediksi saat ini adalah versi sederhana (mock). Untuk produksi, disarankan menggunakan model machine learning yang lebih canggih.
- XAI analysis saat ini menggunakan perhitungan sederhana berdasarkan importance score. Untuk implementasi yang lebih baik, gunakan library XAI seperti SHAP atau LIME.


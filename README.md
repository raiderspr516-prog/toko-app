# 🛍️ TokoKu — E-Commerce Full-Stack (Laravel 12)

Status: **Phase 1–16 selesai** (Phase 17 Testing & Phase 18 Deployment belum — sesuai permintaan, project ini disiapkan untuk kamu uji coba sendiri dulu).

Aplikasi e-commerce nyata & berfungsi penuh: customer bisa belanja dari browse produk sampai pembayaran QRIS
terverifikasi, admin punya panel lengkap untuk kelola semuanya. Bukan mockup — setiap tombol terhubung ke
database sungguhan.

---

## 🧱 Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12 |
| Frontend | Blade + Tailwind CSS 4 (server-rendered, tanpa Livewire per komponen — lihat catatan di bawah) |
| Database | SQLite (default dev) / MySQL (disarankan production) |
| Auth | Dua guard terpisah: `web` (customer, tabel `users`) & `admin` (admin panel, tabel `admins`) |
| Payment | Abstraction layer (`PaymentGatewayInterface`) — aktif: QRIS manual; skeleton: Midtrans |

> **Catatan penyesuaian dari desain awal:** di Phase 1 saya rencanakan Livewire untuk interaksi cart/checkout.
> Untuk mempercepat penyelesaian penuh sampai Phase 16 dalam satu rangkaian kerja, saya implementasikan
> memakai **Controller + Blade klasik** (form submit biasa) — tetap full-stack nyata, cuma tanpa reaktivitas
> tanpa reload halaman. `livewire/livewire` tetap ada di `composer.json` kalau nanti mau dikonversi bertahap
> per halaman.

---

## 🚀 Instalasi

```bash
cd tokoku
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`

> Mau MySQL? Ubah `.env`: `DB_CONNECTION=mysql`, isi `DB_HOST`/`DB_DATABASE=tokoku`/`DB_USERNAME`/`DB_PASSWORD`,
> buat database `tokoku` dulu, baru `php artisan migrate --seed`.

**Setelah migrate+seed, WAJIB upload gambar QRIS** di **Admin → Pengaturan** sebelum customer bisa membayar
(seeder sengaja tidak mengisi gambar QRIS karena itu aset milik toko asli, bukan data dummy).

---

## 🔑 Akun Default (dari seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | password |
| Customer | customer@example.com | password |

⚠️ **Ganti password ini sebelum production.**

---

## 🗺️ Alur Lengkap yang Sudah Berfungsi

**Customer:**
Register/Login → Browse produk (search/filter/sort) → Detail produk → Tambah ke keranjang / Beli sekarang →
Checkout (pilih alamat, kupon) → Order dibuat (stok berkurang real, harga dihitung ulang di server) →
Halaman QRIS → Upload bukti bayar → Menunggu verifikasi admin → (approved) → Lihat tracking pengiriman →
Order selesai. Bisa juga batalkan order (stok balik), lihat riwayat, notifikasi, kelola alamat.

**Admin:**
Login terpisah → Dashboard (statistik real + grafik) → CRUD Produk & Kategori (dengan histori stok) →
Kelola Order (ubah status, input resi) → Verifikasi Pembayaran (approve/reject dengan alasan wajib) →
Kelola Customer (nonaktifkan akun) → CRUD Kupon → Laporan (penjualan per periode, produk terlaris, top
customer) → Pengaturan (info toko, QRIS, rekening bank) → Notifikasi.

---

## 🔒 Keamanan yang Sudah Diterapkan

- **Guard terpisah total** — akun customer tidak mungkin akses admin sama sekali (tabel beda, session beda)
- **Harga & stok SELALU dihitung ulang di server** saat checkout (`OrderService`), tidak pernah percaya request dari frontend
- **DB transaction + row locking** (`lockForUpdate`) saat kurangi stok — anti overselling
- **Upload bukti bayar**: validasi MIME asli (bukan cuma ekstensi), max 2MB, nama file di-UUID-kan, disimpan di disk **private** (`storage/app/private`, bukan `public`) — hanya bisa diakses admin lewat route terautentikasi
- **Rate limiting**: login (customer & admin), checkout, upload bukti bayar, webhook
- **Audit log** (`audit_logs`) mencatat setiap aksi admin penting (approve/reject payment, ubah status order, nonaktifkan customer, dst) dengan IP & user agent
- **HTTP security headers** (X-Frame-Options, X-Content-Type-Options, Referrer-Policy, HSTS saat HTTPS)
- **Password tidak pernah diekspos** — `$hidden` di semua model auth, admin tidak bisa lihat password customer
- **Policy** (`OrderPolicy`) mencegah customer melihat/membatalkan order milik user lain
- **Webhook endpoint** (`/api/webhooks/payment/{gateway}`) sudah terstruktur dengan pencatatan payload mentah + validasi signature (didelegasikan ke gateway aktif) — siap dipakai begitu provider sungguhan (Midtrans dkk) diaktifkan

---

## 💳 Tentang Payment Gateway

Provider aktif saat ini: **QRIS Manual** (`App\Services\Payment\QRISManualPaymentService`) — gambar QRIS
statis diupload admin, customer upload bukti transfer, admin verifikasi manual. Ini alur **nyata**, bukan
simulasi — status order & stok benar-benar berubah di database sesuai aksi admin.

`App\Services\Payment\MidtransPaymentService` adalah **skeleton belum aktif** (akan melempar exception kalau
dipanggil) karena kredensial Midtrans belum tersedia. Untuk mengaktifkan gateway sungguhan nanti:
1. Isi `MIDTRANS_SERVER_KEY` & `MIDTRANS_CLIENT_KEY` di `.env`
2. Implementasikan method di `MidtransPaymentService` (generatePayment, checkStatus, handleWebhook + validasi signature)
3. Ubah `PAYMENT_PROVIDER=midtrans` di `.env`

Tidak ada kode lain yang perlu diubah — itulah gunanya `PaymentGatewayInterface`.

---

## 📁 Struktur Folder Kunci

```
app/
├── Events/ & Listeners/        Notifikasi (OrderCreated, PaymentApproved, dst)
├── Http/Controllers/{Customer,Admin,Api}/
├── Models/
├── Notifications/               Notification classes (channel: database)
├── Policies/                    OrderPolicy
└── Services/
    ├── ProductService, CategoryService, CartService, OrderService,
    │   CouponService, ReportService, AuditLogService
    └── Payment/
        ├── PaymentGatewayInterface, PaymentService
        ├── QRISManualPaymentService (aktif)
        ├── MidtransPaymentService (skeleton)
        └── PaymentProofService, PaymentVerificationService

routes/
├── web.php, customer.php, admin.php, auth.php, admin-auth.php, api.php
```

---

## ⚠️ Yang BELUM Dikerjakan (di luar permintaan sampai Phase 16)

- **Phase 17 — Testing**: belum ada automated test (PestPHP/PHPUnit). Kamu diminta untuk testing manual dulu.
- **Phase 18 — Deployment**: belum ada konfigurasi server/CI-CD.
- SEO (sitemap, meta tags dinamis, structured data) belum diimplementasikan.
- Multi-image gallery produk: tabel `product_images` sudah ada, tapi UI upload multi-gambar di admin belum dibuat (baru single `image` utama).
- Livewire belum dipakai (lihat catatan Tech Stack di atas).

Kalau nanti nemu bug pas testing manual, kirim pesan errornya (atau screenshot) — saya bantu perbaiki.

# SIMKESRA - Sistem Informasi Kesejahteraan Rakyat

Aplikasi web berbasis Laravel untuk mengelola program kesejahteraan rakyat dengan fitur multiple user roles, manajemen bantuan, tracking distribusi, dan reporting komprehensif.

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Requirement](#requirement)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Penggunaan](#penggunaan)
- [User Roles](#user-roles)
- [Database](#database)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)

## ✨ Fitur Utama

### Admin Panel
- Dashboard analytics dengan statistik real-time
- Manajemen user dan staff
- Pengaturan sistem dan konfigurasi
- Monitoring aktivitas keseluruhan

### Kesejahteraan Rakyat (KESRA)
- Manajemen periode bantuan
- Data penerima bantuan dengan kelurahan
- Upload dan validasi data penerima
- Tracking status bantuan (pending, approved, distributed)
- Kartu bantuan dan berita acara
- Export laporan (Excel/PDF)

### Bank
- Dashboard dengan statistik real-time
- Manajemen teller/staff bank
- Validasi pemenang/penerima bantuan
- Scan QR code untuk verifikasi
- Pivot table analysis
- Laporan transaksi

### Validator
- Dashboard validasi
- Review dan approval pemenang
- Bukti validasi dengan upload dokumen
- Status tracking
- Laporan validasi detail

### Features Tambahan
- **Authentikasi**: Login dengan email/password, Google reCAPTCHA
- **Authorization**: Role-based access control (RBAC) dengan Spatie Permission
- **Excel Import/Export**: Maatwebsite Excel untuk bulk operations
- **QR Code**: Simplesoftwareio untuk scanning verification
- **Payment Gateway**: Midtrans integration untuk pembayaran
- **Real-time Updates**: Livewire untuk interaktif UI
- **Admin Panel**: Filament untuk CRUD management
- **Security**: Content Security Policy (CSP) headers, HTTPS enforcement

## 📦 Requirement

### Sistem
- **OS**: Linux/macOS/Windows
- **Web Server**: Apache/Nginx
- **PHP**: 8.1+ (dengan extensions: curl, mbstring, sqlite, bcmath, gd, pdo_mysql)
- **Database**: MySQL 5.7+ atau MariaDB 10.2+
- **Node.js**: 18+ (untuk asset compilation)

### Software
- Composer 2.0+
- Git
- NPM atau Yarn

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://git.karawangkab.go.id/dika/setda-simkresa.git
cd setda-simkresa
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
# atau
yarn install
```

### 3. Setup Environment

```bash
# Copy .env.example ke .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Setup Database

Buat database baru:

```bash
# MySQL CLI
mysql -u root -p
CREATE DATABASE simkesra;
exit;
```

Update `.env` dengan database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simkesra
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrate Database

```bash
# Run migrations
php artisan migrate

# Seed data awal (optional)
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Setup Storage & Permissions

```bash
# Link storage
php artisan storage:link

# Set permissions (Linux/macOS)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Publish Assets

```bash
# Publish Livewire assets
php artisan livewire:publish --assets

# Publish Filament assets
php artisan filament:publish
```

## ⚙️ Konfigurasi

### Konfigurasi Penting di .env

```env
# App
APP_NAME="SIMKESRA"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://simkesra.local

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simkesra
DB_USERNAME=root
DB_PASSWORD=

# Email (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=no-reply@simkesra.local
MAIL_FROM_NAME="SIMKESRA"

# Google reCAPTCHA
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key

# Midtrans (Payment Gateway)
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key

# Filesystem
FILESYSTEM_DISK=local
```

### Konfigurasi Content Security Policy

CSP configuration sudah di-setup di `config/csp.php` untuk security headers:

```php
'directives' => [
    'script-src' => ['self', 'unsafe-inline', 'unsafe-eval', 
                     'https://www.google.com', 'https://www.gstatic.com', ...],
    'style-src' => ['self', 'unsafe-inline', 
                    'https://fonts.googleapis.com', 'https://cdn.jsdelivr.net', ...],
    // ... more directives
]
```

## 💻 Penggunaan

### Development Server

```bash
# Start Laravel development server
php artisan serve
# App akan berjalan di http://localhost:8000

# Dalam terminal lain, start asset watcher
npm run dev
```

### Production Build

```bash
# Optimize application
php artisan optimize

# Build assets
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
```

### Artisan Commands Berguna

```bash
# View active routes
php artisan route:list

# Run scheduled tasks
php artisan schedule:run

# Queue worker
php artisan queue:work

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Database operations
php artisan migrate:refresh --seed
php artisan tinker
```

## 👥 User Roles

Aplikasi memiliki beberapa roles yang dapat dikonfigurasi:

| Role | Akses | Deskripsi |
|------|-------|-----------|
| Admin | Full | Manage semua sistem, user, dan konfigurasi |
| KESRA Manager | Limited | Manage data penerima, bantuan, laporan KESRA |
| Bank Officer | Limited | Manage teller, validasi, transaksi bank |
| Validator | Limited | Review dan approve pemenang bantuan |
| Unit Head | Limited | View dashboard dan laporan unit |
| Guest | Minimal | Login only, view profile |

Setup roles bisa dilakukan melalui:
1. Admin Panel → User Management → Roles
2. Atau via Artisan: `php artisan make:role admin`

## 🗄️ Database

### Schema Utama

```
Users
├── Profiles (data diri user)
├── User_Bantuan (penerima bantuan)
├── Pemenangan (status pemenang)
└── Roles/Permissions (RBAC)

Bantuan
├── Periods (periode bantuan)
├── Skemas (skema/jenis bantuan)
├── Kelurahans (data kelurahan)
└── Bantuan_Kelurahans (distribusi per kelurahan)

Staff
├── Banks
├── Tellers
└── Units
```

### Migration

Semua migrations sudah tersimpan di `database/migrations/`. Untuk custom migration:

```bash
php artisan make:migration create_table_name
php artisan migrate
```

## 📤 Deployment

### Via cPanel/Hosting

1. Upload file ke public_html
2. Setup domain pointing
3. Configure .env di remote server
4. Run migrations: `php artisan migrate`
5. Set permissions: `chmod -R 755 storage bootstrap/cache`

### Via Docker

```dockerfile
FROM php:8.1-fpm

# Install extensions
RUN apt-get update && apt-get install -y \
    libmysqlclient-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd

WORKDIR /app
COPY . /app

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

CMD ["php-fpm"]
```

### Via Deployment Service (Laravel Forge, etc)

```bash
# Push ke repository
git push origin main

# Server otomatis deploy dengan webhook
```

## 🔧 Troubleshooting

### Error: "Class not found"

```bash
# Clear and regenerate autoload
composer dump-autoload -o
php artisan cache:clear
```

### Error: "SQLSTATE[HY000]"

```bash
# Check database connection
php artisan tinker
# DB::connection()->getPdo();
```

### Error: "File permission denied"

```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Livewire/Assets out of date

```bash
# Publish latest assets
php artisan livewire:publish --assets --force
npm run build
```

### CSP Policy Errors

Jika mendapat CSP errors di browser console:
1. Update `config/csp.php` dengan domain yang diperlukan
2. Run: `php artisan config:cache`
3. Clear browser cache

### Queue/Jobs tidak berjalan

```bash
# Check queue setup
php artisan queue:work

# Atau gunakan supervisor untuk production
```

## 🔍 Error Monitoring (Production)

Aplikasi dilengkapi dengan **Log Viewer** untuk monitoring error langsung dari web tanpa perlu akses ke server file.

### Akses Log Viewer

URL: `https://your-app.com/logs`

**Persyaratan**:
- User harus login
- User harus memiliki role **Admin**

### Fitur Log Viewer

- ✅ Lihat semua error log real-time
- ✅ Filter log berdasarkan tanggal dan level (error, warning, notice)
- ✅ Download dan delete log file
- ✅ Search dalam log message
- ✅ Stack trace lengkap untuk debugging

### Konfigurasi Environment

**Production (.env)**:
```
APP_DEBUG=false          # Jangan aktifkan debug mode di production
APP_ENV=production
LOG_LEVEL=error          # Log hanya error messages
LOG_CHANNEL=stack        # Menggunakan stack logging
```

**Development (.env)**:
```
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug          # Log semua messages untuk development
```

### Troubleshooting Error Log

1. **"SQLSTATE[HY000]"** - Database connection error
   - Cek DB_HOST, DB_USERNAME, DB_PASSWORD di .env
   - Pastikan database sudah berjalan

2. **"View not found"** - Template/view tidak ditemukan
   - Cek file exists di `resources/views/`
   - Cek route mana yang error

3. **"Class not found"** - Class/namespace tidak ditemukan
   - Run `composer dump-autoload`
   - Cek namespace dan use statement

4. **Logs folder penuh**
   - Clear logs: `php artisan log:clear`
   - Atau di log viewer, click "Delete" button

## 📚 Dokumentasi Tambahan

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com)
- [Livewire Documentation](https://livewire.laravel.com)
- [Spatie Permissions](https://github.com/spatie/laravel-permission)

## 🤝 Contributing

Untuk contribute ke project:

1. Fork repository
2. Buat feature branch: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add AmazingFeature'`
4. Push ke branch: `git push origin feature/AmazingFeature`
5. Open Pull Request

## 🔒 Security Scan Results (Laravel Enlightn)

**Overall Score: 90% (60/67)**

### ✅ Passed Checks (60)
- Performance: 14/18 ✓
- Reliability: 28/28 ✓ (100% Perfect!)
- Security: 18/20

### ⚠️ Recommendations

**1. PHP Configuration** (Server-level)
```ini
# File: php.ini (MAMP/bin/php/php8.x/conf/php.ini)
allow_url_fopen = Off
expose_php = Off
display_startup_errors = Off
```

**2. Content Security Policy (XSS Protection)**

Current status: Using `unsafe-inline` dan `unsafe-eval` untuk Livewire compatibility.

⚠️ **Note**: Aplikasi saat ini menggunakan `unsafe-inline` dan `unsafe-eval` karena persyaratan Livewire 3.5 untuk dynamic component rendering. Ini adalah trade-off antara:
- **Security**: CSP lebih ketat
- **Functionality**: Livewire reactivity

Untuk production dengan security lebih ketat, pertimbangkan:
- Update Livewire ke versi terbaru yang support nonce-based CSP
- Implement trusted types untuk XSS protection lebih lanjut
- Gunakan CSP report-only mode untuk monitoring

**Current CSP Config**: `/config/csp.php`

### 📋 Security Checklist
- ✅ CSRF protection enabled
- ✅ Secure cookies (HttpOnly)
- ✅ Password hashing configured
- ✅ Login throttling activated
- ✅ Mass assignment protection
- ✅ .env properly secured
- ✅ Debug mode disabled in production
- ✅ Dependencies up-to-date

## 📄 License

Project ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail.

## 📞 Support

Untuk bantuan atau pertanyaan:
- Email: support@simkesra.local
- Issues: [GitLab Issues](https://git.karawangkab.go.id/dika/setda-simkresa/-/issues)
- Dokumentasi: Lihat folder `/docs`

---

**Last Updated**: November 25, 2025  
**Version**: 1.0.0  
**Maintainer**: TA Karawang Cerdas 2023

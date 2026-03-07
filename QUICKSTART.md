# Quick Start Guide - OPhim

Hướng dẫn nhanh để chạy project lần đầu.

## ⚡ 5 Phút Setup

### 1. Clone & Install

```bash
# Vào thư mục project
cd d:\JOBs\web\ophim

# Cài PHP dependencies
composer install

# Cài Node dependencies
npm install
```

### 2. Setup Environment

```bash
# Copy .env
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

### 3. Database

**MySQL:**
```bash
# Tạo database
mysql -u root -e "CREATE DATABASE ophim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Update .env:**
```
DB_DATABASE=ophim
DB_USERNAME=root
DB_PASSWORD=
```

**Chạy migration:**
```bash
php artisan migrate
```

### 4. Build & Serve

```bash
# Build CSS/JS
npm run build

# या cho development
npm run dev  # (terminal 1)

# Trong terminal 2
php artisan serve
```

**Truy cập:** http://localhost:8000

---

## 📝 First Time Setup (Chi tiết)

### Bước 1: Clone Repository

```bash
cd d:\JOBs\web\ophim
```

### Bước 2: Install Composer

```bash
composer install
```

> **Lỗi "Composer not found?**
> ```bash
> # Windows: Download từ https://getcomposer.org/download/
> # Hoặc dùng:
> php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
> php composer-setup.php
> php -r "unlink('composer-setup.php');"
> ```

### Bước 3: Copy .env

```bash
copy .env.example .env
```

Hoặc PowerShell:
```powershell
Copy-Item .env.example -Destination .env
```

### Bước 4: Generate Key

```bash
php artisan key:generate
```

✅ Sẽ thấy: `Application key set successfully.`

### Bước 5: Setup Database

**Tạo Database (MySQL):**

```
User: root
Password: (để trống hoặc password của bạn)
```

```bash
# Command line
mysql -u root -p
# Nhập password nếu có

# Hoặc GUI: MySQL Workbench
```

```sql
CREATE DATABASE ophim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Update .env:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ophim
DB_USERNAME=root
DB_PASSWORD=
```

> **Nếu MySQL có password:**
> ```env
> DB_PASSWORD=your_password
> ```

### Bước 6: Run Migrations

```bash
php artisan migrate
```

✅ Output: `Migrated:  ...` (tables được tạo)

### Bước 7: Install Node Dependencies

```bash
npm install
```

> **Node not found?**
> Download từ https://nodejs.org/ (LTS)

### Bước 8: Build Frontend

```bash
npm run build
```

> **Hoặc dùng watch mode (live reload):**
> ```bash
> npm run dev
> ```
> Chỉnh sửa CSS/JS sẽ tự update

### Bước 9: Serve Project

```bash
php artisan serve
```

✅ Output: `Laravel development server started at http://127.0.0.1:8000`

### Bước 10: Open Browser

```
http://localhost:8000
```

---

## 🔑 Tạo Admin Account

Sau khi bạn đã serve project, tạo tài khoản admin:

### Method 1: Tinker Console

```bash
php artisan tinker
```

```php
$user = \App\Models\User::find(1);
$user->is_admin = true;
$user->save();
exit
```

### Method 2: Trực tiếp Migration

Edit [2024_01_01_000001_add_is_admin_to_users_table.php]:

```php
// Trong up() method, add:
DB::table('users')->where('id', 1)->update(['is_admin' => true]);
```

---

## 🎨 Customization

### 1. Logo

Edit [resources/views/components/navbar.blade.php]:

```html
<span class="text-2xl font-bold text-red-600">OPhim</span>
<!-- Đổi thành logo của bạn -->
<img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
```

### 2. Màu sắc

Edit [resources/css/app.css] hoặc tailwind.config.js

Màu chính: `red-600`

### 3. API URL

Edit [app/Services/OphimService.php]:

```php
private string $baseUrl = 'https://ophim1.com/v1/api';
// Hoặc từ .env:
private string $baseUrl = env('OPHIM_API_BASE_URL', 'https://ophim1.com/v1/api');
```

---

## 📊 Kiểm tra Setup

### 1. Database Connection

```bash
php artisan tinker
>>> DB::connection()->getPdo()
=> PDOConnection { #...
>>> exit
```

✅ Nếu không có error = OK

### 2. Cache

```bash
php artisan cache:clear
```

✅ `Application cache cleared successfully.`

### 3. Routes

```bash
php artisan route:list
```

✅ Sẽ list tất cả routes

### 4. API Test

```bash
php artisan tinker
>>> Http::get('https://ophim1.com/v1/api/home')
```

✅ Response OK = API reachable

---

## 🆘 Troubleshooting

### ❌ "Class not found: OphimService"

**Solution:**
```bash
composer dump-autoload
php artisan cache:clear
```

### ❌ "SQLSTATE[HY000]: General error"

**Check:**
1. MySQL running? `mysql -u root`
2. Database exists? `SHOW DATABASES;`
3. .env correct? `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`

**Fix:**
```bash
php artisan migrate:refresh
```

### ❌ "Npm not found"

**Install Node.js:**
- Download: https://nodejs.org/
- Restart terminal sau install

```bash
node --version
npm --version
```

### ❌ "Port 8000 already in use"

```bash
php artisan serve --port=8001
```

### ❌ "File permissions"

```powershell
# Windows: Run as Administrator
# Hoặc thêm quyền cho storage
```

```bash
# Linux/Mac:
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## 📁 Project Structure (Quick)

```
ophim/
├── app/              # PHP code
│   ├── Services/     # OphimService
│   ├── Controllers/  # Controllers
│   └── Models/       # DB models
├── resources/views/  # Blade templates
├── database/
│   └── migrations/   # Database schema
├── routes/
│   └── web.php       # Routes
├── storage/          # Logs, cache
├── public/           # Assets, images
├── .env              # Environment config
├── package.json      # Node dependencies
└── composer.json     # PHP dependencies
```

---

## 🚀 Next Steps

1. **Tạo tài khoản test:** Dùng register button
2. **Test tìm kiếm:** Search "avengers"
3. **Xem phim:** Click vào movie card
4. **Lưu yêu thích:** Click ❤️ button (khi login)
5. **Vào admin:** `/admin/dashboard` (nếu is_admin=true)

---

## 🔧 Development Tips

### Auto-reload Frontend

Terminal 1:
```bash
npm run dev
```

Terminal 2:
```bash
php artisan serve
```

### Debug Mode

Bật `.env`:
```env
APP_DEBUG=true
```

Error sẽ show detail hơn

### Database GUI

**MySQL Workbench:**
- Connect: localhost:3306
- User: root
- Database: ophim

### Laravel Debugbar

```bash
composer require --dev barryvdh/laravel-debugbar
```

---

## 📚 Học thêm

- **Laravel Docs:** https://laravel.com/docs
- **Blade Docs:** https://laravel.com/docs/blade
- **Tailwind CSS:** https://tailwindcss.com/docs
- **OPhim API:** Xem API_DOCS.md

---

## ✅ Checklist

- [ ] PHP 8.2+ installed
- [ ] MySQL created (ophim)
- [ ] .env configured
- [ ] Migrations ran
- [ ] npm run build completed
- [ ] php artisan serve running
- [ ] http://localhost:8000 accessible
- [ ] Composer dependencies installed

---

**Chúc mừng! 🎉 Project đã sẵn sàng.**

Bắt đầu customize theo nhu cầu của bạn!

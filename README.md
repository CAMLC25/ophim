# OPhim - Website Xem Phim Online

Một website xem phim hoàn chỉnh xây dựng bằng Laravel 12, sử dụng API từ OPhim.

## Giới thiệu

**OPhim** là một nền tảng streaming phim phong cách Netflix/Motion, được phát triển với:

- **Backend:** Laravel 12
- **Frontend:** Blade Templates + TailwindCSS
- **Database:** MySQL
- **Authentication:** Laravel Breeze
- **Video:** HLS.js (M3U8) + Iframe streaming
- **API:** OPhim v1 API (https://ophim1.com/v1/api)

## Tính năng

✅ **Duyệt phim:**
- Trang chủ với phim nổi bật
- Danh sách phim theo bộ lọc
- Lọc theo thể loại, quốc gia, năm
- Tìm kiếm phim

✅ **Xem phim:**
- Video player HLS (M3U8)
- Iframe streaming
- Danh sách tập phim
- Tự động lưu lịch sử xem

✅ **Hệ thống người dùng:**
- Đăng ký/Đăng nhập (Laravel Breeze)
- Lưu danh sách yêu thích
- Lịch sử xem phim
- Bình luận phim

✅ **Hệ thống Affiliate:**
- Quản lý affiliate links
- Redirect tự động trước xem phim
- Custom affiliate theo từng phim

✅ **Admin Panel:**
- Quản lý người dùng
- Duyệt bình luận
- Quản lý affiliate links
- Phân quyền admin

## Cài đặt

### 1. Yêu cầu

- **PHP 8.2+**
- **Composer**
- **Node.js 18+**
- **MySQL 8.0+**
- **Git**

### 2. Clone và Setup

```bash
# Clone repository
git clone <repository-url> ophim
cd ophim

# Cài đặt dependencies
composer install
npm install

# Copy file env
cp .env.example .env

# Generate app key
php artisan key:generate
```

### 3. Cấu hình Database

Tạo database MySQL:
```sql
CREATE DATABASE ophim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Cập nhật `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ophim
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Chạy Migration

```bash
php artisan migrate
```

### 5. Tạo Admin User (Optional)

```bash
php artisan tinker
>>> $user = \App\Models\User::find(1);
>>> $user->is_admin = true;
>>> $user->save();
>>> exit
```

### 6. Build Frontend

```bash
npm run build
```

### 7. Chạy Development Server

```bash
php artisan serve
```

Truy cập: http://localhost:8000

## Cấu trúc Project

```
ophim/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── MovieController.php
│   │   │   ├── UserController.php
│   │   │   ├── CommentController.php
│   │   │   └── AdminController.php
│   │   └── Middleware/
│   │       └── adminMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Favorite.php
│   │   ├── WatchHistory.php
│   │   ├── Comment.php
│   │   └── AffiliateLink.php
│   ├── Services/
│   │   └── OphimService.php
│   └── Policies/
│       └── CommentPolicy.php
├── resources/
│   ├── views/
│   │   ├── components/
│   │   │   ├── navbar.blade.php
│   │   │   ├── footer.blade.php
│   │   │   ├── movie-card.blade.php
│   │   │   └── pagination.blade.php
│   │   ├── user/
│   │   │   ├── favorites.blade.php
│   │   │   └── watch-history.blade.php
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── users.blade.php
│   │   │   ├── comments.blade.php
│   │   │   └── affiliate-links.blade.php
│   │   ├── app.blade.php
│   │   ├── home.blade.php
│   │   ├── movie.blade.php
│   │   ├── watch.blade.php
│   │   ├── category.blade.php
│   │   ├── country.blade.php
│   │   ├── year.blade.php
│   │   └── search.blade.php
│   └── css/
│       └── app.css
├── routes/
│   └── web.php
├── database/
│   └── migrations/
└── .env
```

## API Endpoints

### Public Routes

- `GET /` - Trang chủ
- `GET /danh-sach/{slug}` - Danh sách phim theo bộ lọc
- `GET /phim/{slug}` - Chi tiết phim
- `GET /xem-phim/{slug}/{episode}` - Xem phim
- `GET /the-loai/{slug}` - Phim theo thể loại
- `GET /quoc-gia/{slug}` - Phim theo quốc gia
- `GET /nam/{year}` - Phim theo năm
- `GET /tim-kiem?q=query` - Tìm kiếm phim

### User Routes (Protected)

- `GET /yeu-thich` - Danh sách yêu thích
- `POST /yeu-thich/add` - Thêm yêu thích
- `DELETE /yeu-thich/{movieSlug}` - Xóa yêu thích
- `GET /lich-su` - Lịch sử xem
- `DELETE /lich-su/clear` - Xóa lịch sử
- `POST /comment` - Gửi bình luận
- `DELETE /comment/{id}` - Xóa bình luận

### Admin Routes (Protected + Admin Only)

- `GET /admin/dashboard` - Dashboard
- `GET /admin/users` - Quản lý người dùng
- `PUT /admin/users/{id}/admin` - Cập nhật quyền admin
- `DELETE /admin/users/{id}` - Xóa người dùng
- `GET /admin/comments` - Quản lý bình luận
- `DELETE /admin/comments/{id}` - Xóa bình luận
- `GET /admin/affiliate-links` - Quản lý affiliate
- `POST /admin/affiliate-links` - Tạo affiliate link
- `PUT /admin/affiliate-links/{id}` - Cập nhật affiliate link
- `DELETE /admin/affiliate-links/{id}` - Xóa affiliate link

## OPhim Service

### Methods

```php
$ophimService = new OphimService();

// Lấy danh sách phim trang chủ
$movies = $ophimService->getHomeMovies();

// Lấy phim theo bộ lọc
$data = $ophimService->getMoviesByFilter('phim-moi', $page = 1, $limit = 24);

// Tìm kiếm phim
$data = $ophimService->searchMovies('avengers', $page = 1);

// Lấy danh sách thể loại
$categories = $ophimService->getCategories();

// Lấy danh sách quốc gia
$countries = $ophimService->getCountries();

// Lấy phim theo thể loại
$data = $ophimService->getMoviesByCategory('hanh-dong', $page = 1);

// Lấy phim theo quốc gia
$data = $ophimService->getMoviesByCountry('tu-nhan', $page = 1);

// Lấy phim theo năm
$data = $ophimService->getMoviesByYear(2024, $page = 1);

// Lấy chi tiết phim
$movie = $ophimService->getMovieDetail('op-la-cao-tay');

// Lấy hình ảnh phim
$images = $ophimService->getMovieImages('op-la-cao-tay');

// Lấy diễn viên
$actors = $ophimService->getMovieActors('op-la-cao-tay');

// Lấy keywords
$keywords = $ophimService->getMovieKeywords('op-la-cao-tay');
```

## Database Schema

### Users Table

```sql
- id (primary key)
- name
- email (unique)
- password
- is_admin (boolean)
- email_verified_at
- remember_token
- timestamps
```

### Favorites Table

```sql
- id (primary key)
- user_id (foreign key)
- movie_slug
- movie_name
- poster_url
- unique(user_id, movie_slug)
- timestamps
```

### Watch Histories Table

```sql
- id (primary key)
- user_id (foreign key)
- movie_slug
- movie_name
- poster_url
- episode
- watched_at
- timestamps
```

### Comments Table

```sql
- id (primary key)
- user_id (foreign key)
- movie_slug
- content
- timestamps
```

### Affiliate Links Table

```sql
- id (primary key)
- name (unique)
- url (text)
- season
- active (boolean)
- timestamps
```

## Video Player

### HLS (M3U8)

Tự động phát hiện và sử dụng HLS.js cho các link M3U8:

```html
<video id="player" controls>
    <source src="https://example.com/video.m3u8" type="application/x-mpegURL">
</video>
```

### Iframe

Cho các link iframe embed:

```html
<iframe src="https://example.com/embed" frameborder="0" allowfullscreen></iframe>
```

## Caching

Categories và Countries được cache 1 giờ để tăng performance:

```php
$categories = cache()->remember('categories', 3600, function() {
    return $ophimService->getCategories();
});
```

Xóa cache khi cần:
```bash
php artisan cache:clear
```

## Deploy sang VPS

### 1. Setup Server

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3
sudo apt install -y php8.3 php8.3-{fpm,cli,curl,mysql,zip,gd,mbstring,xml,bcmath}

# Install MySQL
sudo apt install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install Nginx
sudo apt install -y nginx
```

### 2. Clone Project

```bash
cd /var/www
git clone <repository-url> ophim
cd ophim

# Set permissions
sudo chown -R www-data:www-data /var/www/ophim
sudo chmod -R 755 /var/www/ophim
```

### 3. Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 4. Configure .env

```bash
cp .env.example .env
php artisan key:generate

# Edit .env với production settings
# DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
# APP_URL=https://youromain.com
# APP_DEBUG=false
```

### 5. Setup Database

```bash
php artisan migrate --force
```

### 6. Configure Nginx

Tạo `/etc/nginx/sites-available/ophim`:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/ophim/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/ophim /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 7. SSL Certificate (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 8. Setup Queue & Cron (Optional)

Thêm vào crontab:
```bash
0 * * * * cd /var/www/ophim && php artisan schedule:run >> /dev/null 2>&1
```

### 9. Setup Supervisor (Optional, cho queue)

```bash
sudo apt install -y supervisor

# Create config
sudo nano /etc/supervisor/conf.d/ophim.conf
```

```ini
[program:ophim-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ophim/artisan queue:work
autostart=true
autorestart=true
numprocs=4
stdout_logfile=/var/log/ophim-queue.log
stderr_logfile=/var/log/ophim-queue-error.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
```

### 10. Performance Optimization

```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

## Troubleshooting

### Lỗi "Class OphimService not found"

```bash
php artisan cache:clear
composer dump-autoload
```

### Lỗi "SQLSTATE[HY000]..."

Kiểm tra kết nối database trong `.env`

### Lỗi "No routes found"

```bash
php artisan route:clear
php artisan route:cache
```

### Video không phát

- Kiểm tra URL link_embed hoặc link_m3u8
- Nếu CORS error, có thể server không hỗ trợ CORS

## Security

✅ Bảo mật đã được cấu hình:
- CSRF protection
- XSS protection
- SQL injection prevention (Eloquent ORM)
- Password hashing (Bcrypt)
- Authorization policies
- Rate limiting (optional)

## License

MIT License - Tự do sử dụng cho dự án cá nhân và thương mại.

## Support

Có vấn đề? Tạo issue tại GitHub repository.

## Cập nhật

Kiểm tra cập nhật thường xuyên từ OPhim API:

```bash
php artisan tinker
>>> \Illuminate\Support\Facades\Cache::flush()
>>> exit
```

---

**Phát triển bởi:** Senior Laravel Developer  
**Website:** https://cam-ophim.infinityfreeapp.com/
**API:** https://ophim1.com

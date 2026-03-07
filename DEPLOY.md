# Hướng dẫn Deploy OPhim lên VPS

## I. Chọn VPS

Recommend:
- **Provider:** Linode, DigitalOcean, Vultr, AWS Lightsail, Heroku
- **OS:** Ubuntu 22.04 LTS hoặc Ubuntu 20.04 LTS
- **Specs:**
  - 2GB RAM (minimum)
  - 20GB Storage (hoặc hơn)
  - 1-2 vCPU

## II. Setup Ban Đầu

### 1. SSH vào Server

```bash
ssh root@your_server_ip
```

### 2. Update System

```bash
apt update && apt upgrade -y
```

### 3. Cài đặt Dependencies

```bash
# PHP 8.3 + Extensions
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php
apt update
apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-curl php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-intl

# MySQL
apt install -y mysql-server

# Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Nginx
apt install -y nginx

# Git
apt install -y git

# Essentials
apt install -y curl wget unzip zip
```

### 4. Cấu hình Firewall

```bash
apt install -y ufw
ufw enable
ufw default deny incoming
ufw default allow outgoing
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
```

## III. Setup Database

### 1. Tạo Database

```bash
mysql -u root

# Trong MySQL:
CREATE DATABASE ophim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ophim_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON ophim.* TO 'ophim_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2. Backup Database (Optional)

```bash
# Backup
mysqldump -u ophim_user -p ophim > ophim_backup.sql

# Restore
mysql -u ophim_user -p ophim < ophim_backup.sql
```

## IV. Clone & Setup Project

### 1. Clone Repository

```bash
cd /var/www
git clone https://github.com/yourname/ophim.git
cd ophim

# Or nếu sử dụng private repo:
git clone https://yourtoken@github.com/yourname/ophim.git
```

### 2. Set Permissions

```bash
chown -R www-data:www-data /var/www/ophim
chmod -R 755 /var/www/ophim
chmod -R 775 /var/www/ophim/storage
chmod -R 775 /var/www/ophim/bootstrap/cache
```

### 3. Install PHP Dependencies

```bash
cd /var/www/ophim
composer install --optimize-autoloader --no-dev
```

### 4. Build Frontend

```bash
npm install
npm run build
```

> **Nếu build fail:** Tăng memory limit
> ```bash
> npm run build -- --max-old-space-size=2048
> ```

### 5. Setup Environment

```bash
cp .env.example .env

# Edit .env
nano .env
```

Cập nhật:
```env
APP_NAME=OPhim
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ophim
DB_USERNAME=ophim_user
DB_PASSWORD=strong_password_here

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### 6. Generate App Key

```bash
php artisan key:generate
```

### 7. Run Migrations

```bash
php artisan migrate --force
```

## V. Cấu hình Nginx

### 1. Xóa Default Config

```bash
rm /etc/nginx/sites-enabled/default
```

### 2. Tạo Config File

```bash
nano /etc/nginx/sites-available/ophim.conf
```

Thêm:

```nginx
upstream php-fpm {
    server unix:/var/run/php/php8.3-fpm.sock;
}

server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;

    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    # SSL Certificates (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    # SSL Configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Root
    root /var/www/ophim/public;
    index index.php;

    # Logging
    access_log /var/log/nginx/ophim_access.log;
    error_log /var/log/nginx/ophim_error.log;

    # Gzip
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss;

    # Client timeout
    client_max_body_size 20M;

    # Locations
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
    }

    location ~ \.php$ {
        fastcgi_pass php-fpm;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param HTTPS on;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\. {
        deny all;
    }
}
```

### 3. Enable & Test

```bash
ln -s /etc/nginx/sites-available/ophim.conf /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

## VI. SSL Certificate (Let's Encrypt)

```bash
apt install -y certbot python3-certbot-nginx

# Generate cert
certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto renew
certbot renew --dry-run
```

Cert sẽ tự update mỗi 90 ngày.

## VII. Cấu hình PHP-FPM

```bash
nano /etc/php/8.3/fpm/php.ini
```

Lưu ý:
```
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
memory_limit = 512M
```

```bash
systemctl restart php8.3-fpm
```

## VIII. Performance Optimization

### 1. Cache Config

```bash
cd /var/www/ophim
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Enable OPcache

Edit `/etc/php/8.3/fpm/php.ini`:

```ini
[opcache]
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
```

### 3. Setup Cron Job

```bash
crontab -e

# Add:
0 * * * * cd /var/www/ophim && php artisan schedule:run >> /dev/null 2>&1
```

## IX. Monitoring & Logs

### 1. Check Logs

```bash
# Nginx
tail -f /var/log/nginx/ophim_error.log

# PHP-FPM
tail -f /var/log/php8.3-fpm.log

# Laravel
tail -f /var/www/ophim/storage/logs/laravel.log
```

### 2. Setup Auto-restart

```bash
systemctl enable nginx
systemctl enable php8.3-fpm
systemctl enable mysql
```

## X. Backup & Recovery

### 1. Automatic Backup Script

Tạo `/usr/local/bin/backup-ophim.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/backups"
PROJECT_DIR="/var/www/ophim"
DB_NAME="ophim"
DB_USER="ophim_user"
DB_PASS="strong_password_here"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup Database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/ophim_db_$DATE.sql

# Backup Files
tar -czf $BACKUP_DIR/ophim_files_$DATE.tar.gz $PROJECT_DIR

# Delete old backups (older than 30 days)
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completed at $DATE"
```

```bash
chmod +x /usr/local/bin/backup-ophim.sh

# Add to cron (daily at 2 AM)
crontab -e
# 0 2 * * * /usr/local/bin/backup-ophim.sh
```

## XI. Troubleshooting

### Lỗi: "Permission denied"

```bash
chown -R www-data:www-data /var/www/ophim
chmod -R 755 /var/www/ophim
```

### Lỗi: "Database connection refused"

```bash
# Check MySQL status
systemctl status mysql

# Restart MySQL
systemctl restart mysql

# Test connection
mysql -u ophim_user -p ophim -e "SELECT 1;"
```

### Lỗi: "No space left on device"

```bash
# Check disk space
df -h

# Find large files
du -sh /var/www/*
du -sh /var/log/*
```

### Lỗi: "502 Bad Gateway"

```bash
# Check PHP-FPM
systemctl status php8.3-fpm

# Check logs
tail -f /var/log/php8.3-fpm.log
tail -f /var/log/nginx/ophim_error.log

# Restart
systemctl restart php8.3-fpm
systemctl restart nginx
```

### High CPU Usage

```bash
# Check processes
top

# Check PHP processes
ps aux | grep php

# Restart PHP-FPM
systemctl restart php8.3-fpm
```

## XII. Maintenance

### Regular Tasks

```bash
# Clear cache
php artisan cache:clear

# Clear logs
php artisan tinker
>>> \Illuminate\Support\Facades\Log::delete();

# Update dependencies
composer update
npm update
npm run build

# Check storage permissions
chmod -R 775 /var/www/ophim/storage
chmod -R 775 /var/www/ophim/bootstrap/cache
```

## XIII. Domain & Email Setup

### 1. Cấu hình DNS

Trỏ DNS record:
- **A Record:** yourdomain.com → server_ip
- **CNAME:** www.yourdomain.com → yourdomain.com

### 2. Email Sending (Optional)

```bash
# Setup Postfix
apt install -y postfix

# Configure in .env
MAIL_DRIVER=smtp
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## XIV. Scaling (Advanced)

### Load Balancing

```bash
# Multiple PHP-FPM processes
nano /etc/php/8.3/fpm/pool.d/www.conf

# Increase:
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 35
```

### Redis Cache (Optional)

```bash
apt install -y redis-server

# Update .env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## Monitor Performance

```bash
# Real-time monitoring
watch -n 1 'free -h && echo && df -h && echo && ps aux | head -5'

# Check uptime
uptime

# Network speed
speedtest-cli
```

---

**Selamat! Website anda sudah online! 🚀**

Akses di: https://yourdomain.com

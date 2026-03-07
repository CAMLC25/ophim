# Customization Guide - OPhim

Hướng dẫn tùy chỉnh website theo nhu cầu của bạn.

## 🎨 1. Giao diện (UI/UX)

### Thay đổi Màu sắc

**File:** `resources/css/app.css` hoặc `tailwind.config.js`

Editor tại `resources/css/app.css` bạn sẽ thấy CSS variables:

```css
:root {
    --primary-color: #dc2626;      /* Đỏ */
    --secondary-color: #1f2937;    /* Xám tối */
    --dark-bg: #111827;             /* Background đen */
}
```

**Đổi màu chủ yếu:**

Find & Replace `red-600` → color của bạn trong tất cả `.blade.php`:

```
red-600 → blue-600 (xanh)
red-600 → purple-600 (tím)
red-600 → pink-600 (hồng)
red-600 → green-600 (xanh lá)
```

### Thay đổi Font

File: `resources/views/app.blade.php`

```html
<head>
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
```

CSS:
```css
body {
    font-family: 'Inter', sans-serif;
}
```

### Dark/Light Mode

Thêm toggle:

```html
<button onclick="toggleDarkMode()" class="flex items-center">
    <span id="mode-icon">🌙</span> <!-- Moon for dark, Sun for light -->
</button>
```

JavaScript:
```js
function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}
```

---

## 📱 2. Layout & Components

### Thay đổi Logo

File: `resources/views/components/navbar.blade.php`

```html
<!-- Current -->
<span class="text-2xl font-bold text-red-600">OPhim</span>

<!-- Change to image -->
<img src="{{ asset('images/logo.png') }}" alt="OPhim" height="40">
```

### Thay đổi Footer

File: `resources/views/components/footer.blade.php`

```html
<!-- Thêm social links -->
<a href="https://facebook.com/yourpage" target="_blank">
    <i class="fab fa-facebook"></i> Facebook
</a>

<!-- Thêm contact -->
<p>Email: contact@yourdomain.com</p>
```

### Customize Navbar Menu

File: `resources/views/components/navbar.blade.php`

Thêm menu item:
```html
<div class="group relative">
    <button class="hover:text-red-600 transition">
        Menu ▼
    </button>
    <div class="hidden group-hover:block absolute left-0 mt-2 w-48 bg-black border border-gray-800 rounded-lg py-2 shadow-lg">
        <a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-gray-800">Home</a>
        <a href="{{ route('search') }}" class="block px-4 py-2 hover:bg-gray-800">Search</a>
    </div>
</div>
```

---

## 🎬 3. Movie Display

### Thay đổi Movie Card

File: `resources/views/components/movie-card.blade.php`

```html
<!-- Thêm rating -->
<div class="flex justify-between items-center mt-2">
    <span>⭐ 8.5/10</span>
    <span>{{ $movie['year'] }}</span>
</div>

<!-- Thêm status badge -->
@if($movie['status'] === 'ongoing')
    <span class="badge badge-success">Ongoing</span>
@else
    <span class="badge badge-secondary">Completed</span>
@endif
```

### Thay đổi Grid Layout

File: `resources/views/home.blade.php`

```html
<!-- Default 6 columns -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

<!-- Change to 4 columns -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

<!-- Or responsive -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
```

### Lazy Loading Images

```html
<img
    src="{{ $movie['poster_url'] }}"
    alt="{{ $movie['name'] }}"
    loading="lazy"
    class="w-full h-full object-cover"
>
```

---

## 🎥 4. Video Player

### Thay đổi Player Ukuran

File: `resources/views/watch.blade.php`

```html
<!-- Change height -->
<iframe
    width="100%"
    height="800"  <!-- từ 600 thành 800 -->
    src="{{ $playUrl }}"
></iframe>
```

### Thêm Custom Controls

```html
<div id="video-player" class="bg-black rounded-lg overflow-hidden mb-6">
    <!-- Custom player buttons before video -->
    <div class="bg-gray-800 p-4 flex gap-2">
        <button onclick="playPrevious()" class="px-4 py-2 bg-red-600">← Previous</button>
        <button onclick="playNext()" class="px-4 py-2 bg-red-600">Next →</button>
        <button onclick="toggleFullscreen()" class="px-4 py-2 bg-red-600">⛶ Fullscreen</button>
    </div>
    
    <video id="player" class="w-full h-full" controls>
        <source src="{{ $playUrl }}" type="application/x-mpegURL">
    </video>
</div>
```

---

## 🔐 5. Authentication

### Customize Login Form

Breeze templates: `resources/views/auth/login.blade.php`

```html
<!-- Add company logo -->
<img src="{{ asset('images/logo.png') }}" alt="OPhim" class="mx-auto h-12 mb-4">

<!-- Add terms -->
<label class="flex items-center mt-4">
    <input type="checkbox" required>
    <span class="ml-2 text-sm">Tôi đồng ý với <a href="#" class="text-red-600">Điều khoản</a></span>
</label>
```

### Custom Registration Message

File: `resources/views/auth/register.blade.php`

```html
<div class="mb-4 text-center">
    <p class="text-gray-400">
        Đã có tài khoản?
        <a href="{{ route('login') }}" class="text-red-600 hover:underline">Đăng nhập</a>
    </p>
</div>

<div class="mb-4 bg-blue-900 p-3 rounded">
    <p class="text-blue-200">✅ Miễn phí, không cần thẻ tín dụng</p>
</div>
```

---

## 🔗 6. Links & URLs

### Thay đổi Social Links

File: `resources/views/components/footer.blade.php`

```html
<div class="flex space-x-4">
    <a href="https://facebook.com/yourpage" class="hover:text-red-600">
        <i class="fab fa-facebook"></i> Facebook
    </a>
    <a href="https://twitter.com/yourhandle" class="hover:text-red-600">
        <i class="fab fa-twitter"></i> Twitter
    </a>
    <a href="https://youtube.com/yourchannel" class="hover:text-red-600">
        <i class="fab fa-youtube"></i> YouTube
    </a>
</div>
```

### Thêm Contact Form

```html
<form action="{{ route('contact.store') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <textarea name="message" placeholder="Message" required></textarea>
    <button type="submit">Gửi</button>
</form>
```

---

## 📊 7. Admin Panel

### Thêm Dashboard Stats

File: `resources/views/admin/dashboard.blade.php`

```html
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-lg">
        <h3 class="text-gray-400 text-sm">Total Users</h3>
        <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
        <p class="text-sm text-green-600 mt-2">↑ 5% from last month</p>
    </div>
    
    <!-- Thêm stats khác -->
</div>
```

---

## 🔔 8. Notifications

### Add Toast Notifications

```html
@if($message = Session::get('success'))
    <div class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 toast" role="alert">
        {{ $message }}
    </div>
@endif

@if($message = Session::get('error'))
    <div class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 toast" role="alert">
        {{ $message }}
    </div>
@endif

<script>
    document.querySelectorAll('.toast').forEach(element => {
        setTimeout(() => element.remove(), 3000);
    });
</script>
```

---

## 📧 9. Email Configuration

File: `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

Create notification:
```bash
php artisan make:notification MovieCommented
```

---

## 🚀 10. Performance

### Enable Caching

File: `config/cache.php`

```php
'default' => env('CACHE_DRIVER', 'file'),
```

Use cache everywhere:
```php
$categories = cache()->remember('categories', 3600, function() {
    return $ophimService->getCategories();
});
```

### Image Optimization

```bash
composer require intervention/image
```

Usage:
```php
use Intervention\Image\ImageManager;

$manager = new ImageManager(new Driver);
$image = $manager->read(storage_path('image.jpg'))->scale(width: 300, height: 300)->toPng();
```

### Database Queries

```php
// Enable query logging
DB::enableQueryLog();

// Check queries
dd(DB::getQueryLog());
```

---

## 🌍 11. Multi-language (Optional)

### Setup i18n

```bash
php artisan make:lang vi
php artisan make:lang en
```

File: `resources/lang/vi/messages.php`

```php
return [
    'home' => 'Trang chủ',
    'movie' => 'Phim',
    'search' => 'Tìm kiếm',
];
```

Usage:
```blade
{{ __('messages.home') }}
```

---

## 🔍 12. SEO Optimization

### Meta Tags

```html
<meta name="description" content="{{ $movie['content'] }}">
<meta property="og:title" content="{{ $movie['name'] }}">
<meta property="og:description" content="{{ substr($movie['content'], 0, 160) }}">
<meta property="og:image" content="{{ $movie['poster_url'] }}">
<meta name="keywords" content="{{ implode(',', array_column($keywords, 'name')) }}">
```

### Sitemap

```bash
composer require laravel/sitemap
```

---

## 📱 13. Mobile Optimization

Ensure responsive:
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

Test on mobile:
```bash
# Run on network accessible IP
php artisan serve --host 0.0.0.0
```

---

## 🎯 Common Customizations

### 1. Change brand name

Search & Replace: `OPhim` → `YourName`

### 2. Change primary color

Search & Replace: `red-600` → `blue-600`

### 3. Change homepage content

Edit: `resources/views/home.blade.php`

### 4. Add new category

Edit: `resources/views/components/navbar.blade.php`

### 5. Disable comments

Remove comment section from: `resources/views/movie.blade.php`

### 6. Hide admin panel

Remove menu item from navbar

### 7. Change affiliate timer

Edit: `resources/views/watch.blade.php`

```js
countdown = 5; // Change 3 to 5 seconds
```

---

## 📚 Resources

- **Tailwind CSS:** https://tailwindcss.com/
- **Laravel Docs:** https://laravel.com/docs
- **Blade:** https://laravel.com/docs/blade
- **FontAwesome Icons:** https://fontawesome.com/

---

Happy customizing! 🎨

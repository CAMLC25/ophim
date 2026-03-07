# OPhim API Documentation

Tài liệu chi tiết cho OPhim Service và cách sử dụng.

## Base URL

```
https://ophim1.com/v1/api
```

## Response Format

Tất cả API trả về JSON:

```json
{
  "status": "success",
  "message": "...",
  "data": {
    "seoOnPage": { ... },
    "items": [ ... ],
    "params": {
      "pagination": {
        "currentPage": 1,
        "totalItems": 100,
        "totalPages": 5
      }
    }
  }
}
```

## Endpoints

### 1. Trang chủ

```
GET /home
```

**Response Data:**
- `items` - Array danh sách phim

**Fields mỗi phim:**
```
- _id: string
- name: string (Tên phim)
- slug: string
- origin_name: string (Tên gốc)
- alternative_names: array
- type: string (single, series)
- thumb_url: string (URL thumbnail)
- poster_url: string (URL poster)
- year: number
- category: array (Thể loại)
- country: array (Quốc gia)
```

**Usage:**
```php
$ophimService->getHomeMovies()
```

---

### 2. Danh sách phim theo bộ lọc

```
GET /danh-sach/{slug}?page=1&limit=24
```

**Parameters:**
- `slug` - Slug danh sách (ví dụ: `phim-moi`, `phim-hot`, `phim-le`, `phim-bo`)
- `page` - Trang (default: 1)
- `limit` - Số phim/trang (default: 24)

**Response:**
```json
{
  "data": {
    "items": [ ... ],
    "params": {
      "pagination": {
        "currentPage": 1,
        "totalItems": 500,
        "totalPages": 21
      }
    }
  }
}
```

**Usage:**
```php
$data = $ophimService->getMoviesByFilter('phim-moi', 1, 24);
// $data['items'] - danh sách phim
// $data['pagination'] - thông tin phân trang
```

---

### 3. Tìm kiếm phim

```
GET /tim-kiem?keyword=avengers&page=1&limit=24
```

**Parameters:**
- `keyword` - Từ khóa tìm kiếm
- `page` - Trang (optional)
- `limit` - Số kết quả/trang (optional)

**Response:** như danh sách phim

**Usage:**
```php
$data = $ophimService->searchMovies('avengers', $page = 1);
```

---

### 4. Danh sách thể loại

```
GET /the-loai
```

**Response:**
```json
{
  "data": [
    {
      "_id": "61cf9d95e6375e00c653a3cd",
      "slug": "hanh-dong",
      "name": "Hành động"
    },
    {
      "_id": "61cf9d95e6375e00c653a3ce",
      "slug": "hai-huoc",
      "name": "Hài hước"
    }
    // ...
  ]
}
```

**Usage:**
```php
$categories = $ophimService->getCategories();

foreach ($categories as $cat) {
    echo $cat['name']; // Hành động, Hài hước, etc
}
```

---

### 5. Phim theo thể loại

```
GET /the-loai/{slug}?page=1&limit=24
```

**Parameters:**
- `slug` - Slug thể loại (ví dụ: `hanh-dong`)
- `page` - Trang (optional)

**Response:** like danh sách phim

**Usage:**
```php
$data = $ophimService->getMoviesByCategory('hanh-dong', 1);
```

---

### 6. Danh sách quốc gia

```
GET /quoc-gia
```

**Response:**
```json
{
  "data": [
    {
      "_id": "61cf9d95e6375e00c653a3d5",
      "slug": "au-my",
      "name": "Âu Mỹ"
    },
    {
      "_id": "61cf9d95e6375e00c653a3d6",
      "slug": "han-quoc",
      "name": "Hàn Quốc"
    }
    // ...
  ]
}
```

**Usage:**
```php
$countries = $ophimService->getCountries();
```

---

### 7. Phim theo quốc gia

```
GET /quoc-gia/{slug}?page=1&limit=24
```

**Usage:**
```php
$data = $ophimService->getMoviesByCountry('au-my', 1);
```

---

### 8. Danh sách năm phát hành

```
GET /nam-phat-hanh
```

**Response:**
```json
{
  "data": [
    {
      "_id": "61cf9d95e6375e00c653a413",
      "name": "2024"
    },
    {
      "_id": "61cf9d95e6375e00c653a412",
      "name": "2023"
    }
    // ...
  ]
}
```

---

### 9. Phim theo năm

```
GET /nam-phat-hanh/{year}?page=1&limit=24
```

**Usage:**
```php
$data = $ophimService->getMoviesByYear(2024, 1);
```

---

### 10. Chi tiết phim

```
GET /phim/{slug}
```

**Response:**
```json
{
  "data": {
    "item": {
      "_id": "...",
      "name": "Ốp Lá Cao Tay",
      "origin_name": "One Piece",
      "slug": "op-la-cao-tay",
      "content": "Mô tả phim...",
      "type": "series",
      "status": "ongoing",
      "thumb_url": "...",
      "poster_url": "...",
      "quality": "HD",
      "lang": "Vietsub",
      "year": 1999,
      "category": [
        { "_id": "...", "name": "Hành động", "slug": "hanh-dong" },
        { "_id": "...", "name": "Phiêu Lưu", "slug": "phieu-luu" }
      ],
      "country": [
        { "_id": "...", "name": "Nhật Bản", "slug": "nhat-ban" }
      ],
      "actor": [
        { "name": "Toru Furuya", "slug": "toru-furuya" }
      ],
      "director": [
        { "name": "Toei Animation", "slug": "toei-animation" }
      ],
      "episodes": [
        {
          "server_name": "VIP",
          "server_data": [
            {
              "name": "Tập 1",
              "slug": "1",
              "link_embed": "https://...",
              "link_m3u8": "https://...m3u8"
            },
            {
              "name": "Tập 2",
              "slug": "2",
              "link_embed": "https://...",
              "link_m3u8": null
            }
          ]
        },
        {
          "server_name": "Vietsub",
          "server_data": [ /* ... */ ]
        }
      ]
    },
    "seoOnPage": {
      "og_image": "...",
      "og_type": "website",
      "title": "Ốp Lá Cao Tay",
      "description": "Xem phim Ốp Lá Cao Tay online..."
    }
  ]
}
```

**Usage:**
```php
$movie = $ophimService->getMovieDetail('op-la-cao-tay');

echo $movie['name'];              // Ốp Lá Cao Tay
echo $movie['content'];           // Mô tả phim
echo count($movie['episodes']);   // Số server
echo count($movie['episodes'][0]['server_data']); // Số tập
```

**Phát hiện link phát:**
```php
foreach ($movie['episodes'] as $server) {
    echo "Server: " . $server['server_name']; // VIP, Vietsub
    
    foreach ($server['server_data'] as $episode) {
        if (!empty($episode['link_embed'])) {
            // Sử dụng iframe
            echo "<iframe src='{$episode['link_embed']}'></iframe>";
        } elseif (!empty($episode['link_m3u8'])) {
            // Sử dụng HLS player
            echo "<video src='{$episode['link_m3u8']}'></video>";
        }
    }
}
```

---

### 11. Hình ảnh phim

```
GET /phim/{slug}/images
```

**Response:**
```json
{
  "data": {
    "items": [
      {
        "image_url": "https://...",
        "thumb_url": "https://..."
      }
    ]
  }
}
```

**Usage:**
```php
$images = $ophimService->getMovieImages('op-la-cao-tay');

foreach ($images as $image) {
    echo "<img src='{$image['thumb_url']}'>";
}
```

---

### 12. Diễn viên

```
GET /phim/{slug}/peoples
```

**Response:**
```json
{
  "data": {
    "items": [
      {
        "name": "Toru Furuya",
        "slug": "toru-furuya",
        "image": "https://..."
      }
    ]
  }
}
```

**Usage:**
```php
$actors = $ophimService->getMovieActors('op-la-cao-tay');

foreach ($actors as $actor) {
    echo $actor['name']; // Toru Furuya
}
```

---

### 13. Keywords

```
GET /phim/{slug}/keywords
```

**Response:**
```json
{
  "data": {
    "items": [
      {
        "name": "anime"
      },
      {
        "name": "adventure"
      }
    ]
  }
}
```

**Usage:**
```php
$keywords = $ophimService->getMovieKeywords('op-la-cao-tay');
```

---

## Lưu ý quan trọng

### 1. Caching

Nên cache dữ liệu categories, countries vì ít thay đổi:

```php
// Tự động caching 1 giờ
$categories = cache()->remember('categories', 3600, function() {
    return $ophimService->getCategories();
});

// Xóa cache khi cần
cache()->forget('categories');
```

### 2. Error Handling

```php
try {
    $movie = $ophimService->getMovieDetail('slug');
    
    if (empty($movie)) {
        abort(404, 'Phim không tìm thấy');
    }
} catch (\Exception $e) {
    Log::error('API Error: ' . $e->getMessage());
    abort(500, 'Lỗi khi lấy dữ liệu');
}
```

### 3. Pagination

```php
$data = $ophimService->getMoviesByFilter('phim-moi', 1, 24);

if (!empty($data['pagination'])) {
    echo "Trang: " . $data['pagination']['currentPage'];
    echo "Tổng: " . $data['pagination']['totalItems'];
    echo "Trang cuối: " . $data['pagination']['totalPages'];
}
```

### 4. Video Link Priority

Priority phát video:
1. `link_embed` (iframe) - phát trực tiếp
2. `link_m3u8` (HLS) - sử dụng HLS.js player
3. Nếu không có cả hai, bỏ qua tập đó

### 5. Slug Format

Slug thường là lowercase, có dấu gạch ngang:
- Ốp Lá Cao Tay → `op-la-cao-tay`
- Avengers Endgame → `avengers-endgame`

### 6. SEO Metadata

Lấy từ `seoOnPage`:

```php
$movie = $ophimService->getMovieDetail('slug');

@section('title', $movie['name'] . ' - OPhim')
@section('description', substr($movie['content'], 0, 160))
@section('og_image', $movie['poster_url'])
```

---

## Rate Limiting

API không có rate limiting chính thức, nhưng nên:
- Tối đa 100 request/phút
- Cache kết quả
- Sử dụng queue cho background jobs

---

## Troubleshooting

### API Timeout

```php
// Tăng timeout
Http::timeout(30)->get($url);

// Hoặc retry
Http::retry(3, 1000)->get($url);
```

### Connection Error

```php
// Check DNS
nslookup ophim1.com

// Test API
curl -I https://ophim1.com/v1/api/home

// Proxy (nếu cần)
Http::withOptions([
    'proxy' => 'proxy.example.com:8080'
])->get($url);
```

---

Cập nhật cuối: 2024  
API Version: v1  
Base URL: https://ophim1.com/v1/api

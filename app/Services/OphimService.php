<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OphimService
{
    private string $baseUrl = 'https://ophim1.com/v1/api';

    /**
     * Lấy danh sách phim trang chủ
     */
    public function getHomeMovies()
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/home");

            if ($response->successful()) {
                return $response->json('data.items', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getHomeMovies: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách phim theo bộ lọc
     */
    public function getMoviesByFilter(string $slug, int $page = 1, int $limit = 24)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/danh-sach/{$slug}", [
                'page' => $page,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                return [
                    'items' => $response->json('data.items', []),
                    'pagination' => $response->json('data.params.pagination', [])
                ];
            }

            return ['items' => [], 'pagination' => []];
        } catch (\Exception $e) {
            Log::error('OphimService@getMoviesByFilter: ' . $e->getMessage());
            return ['items' => [], 'pagination' => []];
        }
    }

    /**
     * Tìm kiếm phim
     */
    public function searchMovies(string $keyword, int $page = 1, int $limit = 24)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/tim-kiem", [
                'keyword' => $keyword,
                'page' => $page,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                return [
                    'items' => $response->json('data.items', []),
                    'pagination' => $response->json('data.params.pagination', [])
                ];
            }

            return ['items' => [], 'pagination' => []];
        } catch (\Exception $e) {
            Log::error('OphimService@searchMovies: ' . $e->getMessage());
            return ['items' => [], 'pagination' => []];
        }
    }

    /**
     * Lấy danh sách thể loại
     */
    public function getCategories()
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/the-loai");

            if ($response->successful()) {
                return $response->json('data.items', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getCategories: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy danh sách quốc gia
     */
    public function getCountries()
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/quoc-gia");

            if ($response->successful()) {
                return $response->json('data.items', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getCountries: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy phim theo thể loại
     */
    public function getMoviesByCategory(string $slug, int $page = 1, int $limit = 24)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/the-loai/{$slug}", [
                'page' => $page,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                return [
                    'items' => $response->json('data.items', []),
                    'pagination' => $response->json('data.params.pagination', [])
                ];
            }

            return ['items' => [], 'pagination' => []];
        } catch (\Exception $e) {
            Log::error('OphimService@getMoviesByCategory: ' . $e->getMessage());
            return ['items' => [], 'pagination' => []];
        }
    }

    /**
     * Lấy phim theo quốc gia
     */
    public function getMoviesByCountry(string $slug, int $page = 1, int $limit = 24)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/quoc-gia/{$slug}", [
                'page' => $page,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                return [
                    'items' => $response->json('data.items', []),
                    'pagination' => $response->json('data.params.pagination', [])
                ];
            }

            return ['items' => [], 'pagination' => []];
        } catch (\Exception $e) {
            Log::error('OphimService@getMoviesByCountry: ' . $e->getMessage());
            return ['items' => [], 'pagination' => []];
        }
    }

    /**
     * Lấy danh sách năm phát hành
     */
    public function getYears()
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/nam-phat-hanh");

            if ($response->successful()) {
                return $response->json('data', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getYears: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy phim theo năm
     */
    public function getMoviesByYear(int $year, int $page = 1, int $limit = 24)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/nam-phat-hanh/{$year}", [
                'page' => $page,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                return [
                    'items' => $response->json('data.items', []),
                    'pagination' => $response->json('data.params.pagination', [])
                ];
            }

            return ['items' => [], 'pagination' => []];
        } catch (\Exception $e) {
            Log::error('OphimService@getMoviesByYear: ' . $e->getMessage());
            return ['items' => [], 'pagination' => []];
        }
    }

    /**
     * Lấy chi tiết phim
     */
    public function getMovieDetail(string $slug)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/phim/{$slug}");

            if ($response->successful()) {
                return $response->json('data.item', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getMovieDetail: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy hình ảnh phim
     */
    public function getMovieImages(string $slug)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/phim/{$slug}/images");

            if ($response->successful()) {
                return $response->json('data.items', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getMovieImages: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy diễn viên của phim
     */
    public function getMovieActors(string $slug)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/phim/{$slug}/peoples");

            if ($response->successful()) {
                return $response->json('data.items', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getMovieActors: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy keywords của phim
     */
    public function getMovieKeywords(string $slug)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/phim/{$slug}/keywords");

            if ($response->successful()) {
                return $response->json('data.items', []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('OphimService@getMovieKeywords: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate poster URL from thumb URL
     * Converts: uploads/movies/movie-name-thumb.jpg → uploads/movies/movie-name-poster.jpg
     */
    private function generatePosterUrl($thumbUrl)
    {
        if (!$thumbUrl) {
            return null;
        }

        // Replace -thumb with -poster
        return str_replace('-thumb.', '-poster.', $thumbUrl);
    }

    /**
     * Format image URL to use ophim.live cdn
     * @param string $imageType 'thumb' for thumbnail priority, 'poster' for poster priority
     */
    public function formatImageUrl($thumbUrl = null, $posterUrl = null, $imageType = 'thumb')
    {
        // Nếu không có URL, return null
        if (!$thumbUrl && !$posterUrl) {
            return null;
        }

        // Nếu loại là poster, tạo poster_url từ thumb_url
        if ($imageType === 'poster') {
            // Nếu không có poster_url, tạo từ thumb_url
            if (!$posterUrl && $thumbUrl) {
                $posterUrl = $this->generatePosterUrl($thumbUrl);
                // Debug log
                \Log::info('Poster URL Generated', [
                    'thumb' => $thumbUrl,
                    'poster' => $posterUrl
                ]);
            }
            // Ưu tiên poster_url, fallback về thumb_url
            $imageUrl = $posterUrl ?? $thumbUrl;
        } else {
            // Ưu tiên thumb_url (mặc định)
            $imageUrl = $thumbUrl ?? $posterUrl;
        }

        // Nếu URL đã là đường dẫn đầy đủ từ TMDB, giữ nguyên
        if (strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }

        // Nếu là path, wrap với ophim.live cdn
        return 'https://img.ophim.live/uploads/movies/' . ltrim($imageUrl, '/');
    }

    /**
     * Transform movies data with formatted image URLs
     * @param string $imageType 'thumb' for thumbnail, 'poster' for poster images
     */
    public function transformMovies($movies, $isSingleMovie = false, $imageType = 'thumb')
    {
        // Nếu là single movie (associative array)
        if ($isSingleMovie) {
            $movies['poster_url'] = $this->formatImageUrl(
                $movies['thumb_url'] ?? null,
                $movies['poster_url'] ?? null,
                $imageType
            );
            return $movies;
        }

        // Nếu là array of movies
        if (!is_array($movies)) {
            return $movies;
        }

        return array_map(function($movie) use ($imageType) {
            if (is_array($movie)) {
                $movie['poster_url'] = $this->formatImageUrl(
                    $movie['thumb_url'] ?? null,
                    $movie['poster_url'] ?? null,
                    $imageType
                );
            }
            return $movie;
        }, $movies);
    }

    /**
     * Format pagination data from API response
     */
    public function formatPagination($paginationData)
    {
        if (empty($paginationData)) {
            return [
                'currentPage' => 1,
                'totalPages' => 1,
                'totalItems' => 0,
                'totalItemsPerPage' => 24
            ];
        }

        $totalItems = $paginationData['totalItems'] ?? 0;
        $totalItemsPerPage = $paginationData['totalItemsPerPage'] ?? 24;
        $currentPage = $paginationData['currentPage'] ?? 1;

        return [
            'currentPage' => $currentPage,
            'totalPages' => ceil($totalItems / $totalItemsPerPage),
            'totalItems' => $totalItems,
            'totalItemsPerPage' => $totalItemsPerPage
        ];
    }

    /**
     * Lấy link affiliate nếu có
     */
    public function getAffiliateLink(string $slug)
    {
        try {
            return \App\Models\AffiliateLink::where('name', $slug)
                ->where('active', true)
                ->first();
        } catch (\Exception $e) {
            Log::error('OphimService@getAffiliateLink: ' . $e->getMessage());
            return null;
        }
    }
}

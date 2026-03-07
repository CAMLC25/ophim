<?php

namespace App\Http\Controllers;

use App\Services\OphimService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected OphimService $ophimService;

    public function __construct(OphimService $ophimService)
    {
        $this->ophimService = $ophimService;
    }

    /**
     * Trang chủ
     */
    public function home()
    {
        $movies = $this->ophimService->getHomeMovies();
        $movies = $this->ophimService->transformMovies($movies, false, 'poster');

        return view('home', [
            'movies' => $movies
        ]);
    }

    /**
     * Danh sách phim theo bộ lọc
     */
    public function filterList(string $slug, Request $request)
    {
        $page = $request->get('page', 1);
        $data = $this->ophimService->getMoviesByFilter($slug, $page);
        $data['items'] = $this->ophimService->transformMovies($data['items']);

        return view('filter-list', [
            'movies' => $data['items'],
            'pagination' => $this->ophimService->formatPagination($data['pagination']),
            'slug' => $slug
        ]);
    }

    /**
     * Chi tiết phim
     */
    public function movieDetail(string $slug)
    {
        $movie = $this->ophimService->getMovieDetail($slug);
        $movie = $this->ophimService->transformMovies($movie, true);
        $images = $this->ophimService->getMovieImages($slug);
        $actors = $this->ophimService->getMovieActors($slug);
        $keywords = $this->ophimService->getMovieKeywords($slug);

        if (empty($movie)) {
            return abort(404);
        }

        // Lấy comments - chỉ lấy comments gốc (không phải replies)
        $comments = \App\Models\Comment::where('movie_slug', $slug)
            ->whereNull('parent_id')
            ->with('user', 'replies.user', 'replies.replies.user')
            ->latest()
            ->paginate(10);

        // Kiểm tra xem user đã thích phim này chưa
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = \App\Models\Favorite::where('user_id', auth()->id())
                ->where('movie_slug', $slug)
                ->exists();
        }

        return view('movie', [
            'movie' => $movie,
            'images' => $images,
            'actors' => $actors,
            'keywords' => $keywords,
            'comments' => $comments,
            'isFavorited' => $isFavorited
        ]);
    }

    /**
     * Xem phim
     */
    public function watch(string $slug, string $episode = '1')
    {
        $movie = $this->ophimService->getMovieDetail($slug);
        $movie = $this->ophimService->transformMovies($movie, true);

        if (empty($movie)) {
            return abort(404);
        }

        // Tìm server để xem
        $playUrl = null;
        $serverName = null;

        foreach ($movie['episodes'] ?? [] as $ep) {
            if ($ep['server_name'] === 'VIP') continue;

            foreach ($ep['server_data'] ?? [] as $data) {
                if ($data['slug'] === $episode) {
                    $playUrl = $data['link_embed'] ?? $data['link_m3u8'] ?? null;
                    $serverName = $ep['server_name'];
                    break;
                }
            }
        }

        if (!$playUrl) {
            return abort(404, 'Không tìm thấy phim');
        }

        // Lưu history nếu user đã đăng nhập
        if (auth()->check()) {
            \App\Models\WatchHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'movie_slug' => $slug,
                    'episode' => $episode
                ],
                [
                    'movie_name' => $movie['name'],
                    'poster_url' => $movie['poster_url'] ?? null,
                    'watched_at' => now()
                ]
            );
        }

        // Kiểm tra affiliate link
        $affiliateLink = $this->ophimService->getAffiliateLink($slug);

        return view('watch', [
            'movie' => $movie,
            'playUrl' => $playUrl,
            'episode' => $episode,
            'serverName' => $serverName,
            'affiliateLink' => $affiliateLink
        ]);
    }

    /**
     * Trang thể loại
     */
    public function category(string $slug, Request $request)
    {
        $page = $request->get('page', 1);
        $categories = $this->ophimService->getCategories();

        $currentCategory = collect($categories)->firstWhere('slug', $slug);

        $data = $this->ophimService->getMoviesByCategory($slug, $page);
        $data['items'] = $this->ophimService->transformMovies($data['items']);

        return view('category', [
            'movies' => $data['items'],
            'pagination' => $this->ophimService->formatPagination($data['pagination']),
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'slug' => $slug
        ]);
    }

    /**
     * Trang quốc gia
     */
    public function country(string $slug, Request $request)
    {
        $page = $request->get('page', 1);
        $countries = $this->ophimService->getCountries();

        $currentCountry = collect($countries)->firstWhere('slug', $slug);

        $data = $this->ophimService->getMoviesByCountry($slug, $page);
        $data['items'] = $this->ophimService->transformMovies($data['items']);

        return view('country', [
            'movies' => $data['items'],
            'pagination' => $this->ophimService->formatPagination($data['pagination']),
            'countries' => $countries,
            'currentCountry' => $currentCountry,
            'slug' => $slug
        ]);
    }

    /**
     * Trang năm phát hành
     */
    public function year(int $year, Request $request)
    {
        $page = $request->get('page', 1);
        $years = $this->ophimService->getYears();

        $data = $this->ophimService->getMoviesByYear($year, $page);

        return view('year', [
            'movies' => $data['items'],
            'pagination' => $this->ophimService->formatPagination($data['pagination']),
            'years' => $years,
            'year' => $year
        ]);
    }

    /**
     * Trang tìm kiếm
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);

        $data = [];
        if (!empty($query)) {
            $data = $this->ophimService->searchMovies($query, $page);
        }

        return view('search', [
            'movies' => $data['items'] ?? [],
            'pagination' => $this->ophimService->formatPagination($data['pagination'] ?? []),
            'query' => $query
        ]);
    }

    /**
     * Toggle favorite status của phim
     */
    public function toggleFavorite(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Không được xác thực'], 401);
        }

        $slug = $request->get('slug');

        if (!$slug) {
            return response()->json(['success' => false, 'message' => 'Slug không hợp lệ'], 400);
        }

        $favorite = \App\Models\Favorite::where('user_id', auth()->id())
            ->where('movie_slug', $slug)
            ->first();

        if ($favorite) {
            // Nếu đã có yêu thích, xóa đi
            $favorite->delete();
            $isFavorite = false;
        } else {
            // Nếu chưa có, thêm mới
            \App\Models\Favorite::create([
                'user_id' => auth()->id(),
                'movie_slug' => $slug
            ]);
            $isFavorite = true;
        }

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite,
            'message' => $isFavorite ? 'Đã thêm vào yêu thích' : 'Đã xóa khỏi yêu thích'
        ]);
    }
}

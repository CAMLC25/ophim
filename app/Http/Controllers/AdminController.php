<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (!auth()->user()->is_admin) {
                abort(403, 'Bạn không có quyền truy cập trang này');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Quản lý affiliate links
     */
    public function affiliateLinks()
    {
        $links = AffiliateLink::paginate(20);

        return view('admin.affiliate-links', [
            'links' => $links
        ]);
    }

    /**
     * Tạo affiliate link
     */
    public function createAffiliateLink(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'season' => 'nullable|string',
            'active' => 'boolean'
        ]);

        AffiliateLink::create([
            'name' => $request->name,
            'url' => $request->url,
            'season' => $request->season,
            'active' => $request->boolean('active', false)
        ]);

        return redirect()->route('admin.affiliate-links')->with('success', 'Affiliate link đã được tạo');
    }

    /**
     * Cập nhật affiliate link
     */
    public function updateAffiliateLink(Request $request, AffiliateLink $link)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'season' => 'nullable|string',
            'active' => 'boolean'
        ]);

        $link->update([
            'name' => $request->name,
            'url' => $request->url,
            'season' => $request->season,
            'active' => $request->boolean('active', false)
        ]);

        return redirect()->route('admin.affiliate-links')->with('success', 'Affiliate link đã được cập nhật');
    }

    /**
     * Xóa affiliate link
     */
    public function deleteAffiliateLink(AffiliateLink $link)
    {
        $link->delete();

        return redirect()->route('admin.affiliate-links')->with('success', 'Affiliate link đã được xóa');
    }

    /**
     * Áp dụng affiliate link cho nhiều phim
     */
    public function bulkApplyAffiliateLink(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'season' => 'nullable|string',
            'apply_to' => 'required|in:all,selected',
            'movie_slugs' => 'required_if:apply_to,selected|array'
        ]);

        if ($request->apply_to === 'all') {
            // Áp dụng cho tất cả phim
            AffiliateLink::truncate();
            // Tạo một link chung cho tất cả
            AffiliateLink::create([
                'name' => 'all_movies',
                'url' => $request->url,
                'season' => $request->season,
                'active' => true
            ]);

            return redirect()->route('admin.affiliate-links')->with('success', 'Đã áp dụng affiliate link cho tất cả phim');
        } else {
            // Áp dụng cho phim được chọn
            $slugs = $request->movie_slugs ?? [];

            foreach ($slugs as $slug) {
                AffiliateLink::updateOrCreate(
                    ['name' => $slug],
                    [
                        'url' => $request->url,
                        'season' => $request->season,
                        'active' => true
                    ]
                );
            }

            return redirect()->route('admin.affiliate-links')->with('success', 'Đã áp dụng affiliate link cho ' . count($slugs) . ' phim');
        }
    }

    /**
     * Tìm kiếm phim
     */
    public function searchMovies(Request $request)
    {
        $query = $request->get('q', '');
        $service = app(\App\Services\OphimService::class);

        if (empty($query)) {
            return response()->json([]);
        }

        $movies = $service->searchMovies($query, 1, 50);

        // Trích xuất thông tin phim cần thiết
        $results = collect($movies['items'] ?? [])->map(function ($movie) {
            return [
                'slug' => $movie['slug'],
                'name' => $movie['name'],
                'origin_name' => $movie['origin_name'] ?? ''
            ];
        })->toArray();

        return response()->json($results);
    }

    /**
     * Quản lý bình luận
     */
    public function comments()
    {
        $comments = Comment::with(['user'])
            ->latest()
            ->paginate(20);

        return view('admin.comments', [
            'comments' => $comments
        ]);
    }

    /**
     * Xóa bình luận
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Bình luận đã được xóa');
    }

    /**
     * Quản lý người dùng
     */
    public function users()
    {
        $users = User::paginate(20);

        return view('admin.users', [
            'users' => $users
        ]);
    }

    /**
     * Cập nhật quyền admin
     */
    public function updateUserAdmin(Request $request, User $user)
    {
        $request->validate([
            'is_admin' => 'boolean'
        ]);

        $user->update([
            'is_admin' => $request->boolean('is_admin', false)
        ]);

        return redirect()->back()->with('success', 'Quyền admin đã được cập nhật');
    }

    /**
     * Xóa người dùng
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể xóa chính mình');
        }

        $user->comments()->delete();
        $user->favorites()->delete();
        $user->watchHistory()->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Người dùng đã được xóa');
    }
}

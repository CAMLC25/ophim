<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Xem danh sách phim yêu thích
     */
    public function favorites()
    {
        $favorites = auth()->user()->favorites()->paginate(12);

        return view('user.favorites', [
            'favorites' => $favorites
        ]);
    }

    /**
     * Thêm phim vào yêu thích
     */
    public function addFavorite(Request $request)
    {
        $request->validate([
            'movie_slug' => 'required|string',
            'movie_name' => 'required|string',
            'poster_url' => 'nullable|string'
        ]);

        Favorite::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'movie_slug' => $request->movie_slug
            ],
            [
                'movie_name' => $request->movie_name,
                'poster_url' => $request->poster_url
            ]
        );

        return response()->json(['message' => 'Phim đã được thêm vào yêu thích']);
    }

    /**
     * Xóa phim khỏi yêu thích
     */
    public function removeFavorite(string $movieSlug)
    {
        Favorite::where('user_id', auth()->id())
            ->where('movie_slug', $movieSlug)
            ->delete();

        return response()->json(['message' => 'Phim đã được xóa khỏi yêu thích']);
    }

    /**
     * Xem lịch sử xem
     */
    public function watchHistory()
    {
        $history = auth()->user()->watchHistory()
            ->latest('watched_at')
            ->paginate(12);

        return view('user.watch-history', [
            'history' => $history
        ]);
    }

    /**
     * Xóa lịch sử
     */
    public function clearWatchHistory()
    {
        auth()->user()->watchHistory()->delete();

        return redirect()->back()->with('success', 'Lịch sử đã được xóa');
    }

    /**
     * Xem thông tin cá nhân
     */
    public function profile()
    {
        return view('user.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Thông tin cá nhân đã được cập nhật');
    }
}

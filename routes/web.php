<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', [MovieController::class, 'home'])->name('home');

// Movie Routes
Route::get('/danh-sach/{slug}', [MovieController::class, 'filterList'])->name('filter-list');
Route::get('/phim/{slug}', [MovieController::class, 'movieDetail'])->name('movie.detail');
Route::get('/xem-phim/{slug}/{episode?}', [MovieController::class, 'watch'])->name('movie.watch');

// Category, Country, Year Routes
Route::get('/the-loai/{slug}', [MovieController::class, 'category'])->name('category');
Route::get('/quoc-gia/{slug}', [MovieController::class, 'country'])->name('country');
Route::get('/nam/{year}', [MovieController::class, 'year'])->name('year');

// Search Route
Route::get('/tim-kiem', [MovieController::class, 'search'])->name('search');

// Toggle Favorite (AJAX)
Route::post('/yeu-thich/toggle', [MovieController::class, 'toggleFavorite'])->name('movie.toggle-favorite')->middleware('auth');

// User Routes
Route::middleware('auth')->group(function () {
    // Favorites
    Route::get('/yeu-thich', [UserController::class, 'favorites'])->name('user.favorites');
    Route::post('/yeu-thich/add', [UserController::class, 'addFavorite'])->name('user.add-favorite');
    Route::delete('/yeu-thich/{movieSlug}', [UserController::class, 'removeFavorite'])->name('user.remove-favorite');

    // Watch History
    Route::get('/lich-su', [UserController::class, 'watchHistory'])->name('user.watch-history');
    Route::delete('/lich-su/clear', [UserController::class, 'clearWatchHistory'])->name('user.clear-history');

    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');

    // Comments
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Affiliate Links
        Route::get('/affiliate-links', [AdminController::class, 'affiliateLinks'])->name('affiliate-links');
        Route::post('/affiliate-links', [AdminController::class, 'createAffiliateLink'])->name('create-affiliate-link');
        Route::post('/affiliate-links/bulk-apply', [AdminController::class, 'bulkApplyAffiliateLink'])->name('bulk-apply-affiliate-link');
        Route::get('/search-movies', [AdminController::class, 'searchMovies'])->name('search-movies');
        Route::put('/affiliate-links/{link}', [AdminController::class, 'updateAffiliateLink'])->name('update-affiliate-link');
        Route::delete('/affiliate-links/{link}', [AdminController::class, 'deleteAffiliateLink'])->name('delete-affiliate-link');

        // Comments
        Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
        Route::delete('/comments/{comment}', [AdminController::class, 'deleteComment'])->name('delete-comment');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}/admin', [AdminController::class, 'updateUserAdmin'])->name('update-user-admin');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('delete-user');
    });
});



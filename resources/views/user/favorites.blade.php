@extends('app')

@section('title', 'Phim yêu thích - OPhim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Phim yêu thích</h1>

    @if($favorites->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-4 mb-8">
            @foreach($favorites as $favorite)
                <div class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-lg bg-gray-800 aspect-video mb-3">
                        <img
                            src="{{ $favorite->poster_url ?? 'https://img.ophim.live/uploads/movies/tro-lai-nam-2000-theo-duoi-nang-hoa-khoi-cung-ban-thumb.jpg' }}"
                            alt="{{ $favorite->movie_name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                        >
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-center justify-center">
                            <a href="{{ route('movie.detail', $favorite->movie_slug) }}" class="text-white opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('movie.detail', $favorite->movie_slug) }}" class="block">
                        <h3 class="font-semibold group-hover:text-red-600 transition truncate">
                            {{ $favorite->movie_name }}
                        </h3>
                    </a>

                    <form action="{{ route('user.remove-favorite', $favorite->movie_slug) }}" method="POST" onsubmit="return confirm('Xóa khỏi yêu thích?')" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">
                            Xóa khỏi yêu thích
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        {{ $favorites->links() }}
    @else
        <div class="text-center py-12">
            <p class="text-gray-400 mb-4">Bạn chưa thêm phim nào vào yêu thích</p>
            <a href="{{ route('home') }}" class="text-red-600 hover:underline">← Quay lại trang chủ</a>
        </div>
    @endif
</div>
@endsection

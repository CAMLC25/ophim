@extends('app')

@section('title', 'Lịch sử xem - OPhim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Lịch sử xem</h1>
        @if($history->count() > 0)
            <form action="{{ route('user.clear-history') }}" method="POST" onsubmit="return confirm('Xóa toàn bộ lịch sử xem?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 px-4 py-2 rounded hover:bg-red-700 transition">
                    Xóa tất cả
                </button>
            </form>
        @endif
    </div>

    @if($history->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
            @foreach($history as $item)
                <div class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-lg bg-gray-800 aspect-video mb-3">
                        <img
                            src="{{ $item->poster_url ?? 'https://img.ophim.live/uploads/movies/tro-lai-nam-2000-theo-duoi-nang-hoa-khoi-cung-ban-thumb.jpg' }}"
                            alt="{{ $item->movie_name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                        >
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-center justify-center">
                            <a href="{{ route('movie.watch', [$item->movie_slug, $item->episode]) }}" class="text-white opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('movie.detail', $item->movie_slug) }}" class="block">
                        <h3 class="font-semibold group-hover:text-red-600 transition truncate">
                            {{ $item->movie_name }}
                        </h3>
                    </a>

                    <p class="text-sm text-gray-400">Tập {{ $item->episode }}</p>
                    <p class="text-xs text-gray-500">
                        @if(is_object($item->watched_at))
                            {{ $item->watched_at->diffForHumans() }}
                        @else
                            {{ \Carbon\Carbon::parse($item->watched_at)->diffForHumans() }}
                        @endif
                    </p>
                </div>
            @endforeach
        </div>

        {{ $history->links() }}
    @else
        <div class="text-center py-12">
            <p class="text-gray-400 mb-4">Bạn chưa xem phim nào</p>
            <a href="{{ route('home') }}" class="text-red-600 hover:underline">← Quay lại trang chủ</a>
        </div>
    @endif
</div>
@endsection

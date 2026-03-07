@extends('app')

@section('title', 'OPhim - Xem Phim Online')

@section('content')
    <!-- Banner -->
    <div class="mb-12">
        @if (!empty($movies))
            @php $featured = $movies[0] ?? null; @endphp
            @if ($featured)
                {{-- <div class="relative h-96 bg-gradient-to-r from-black to-transparent">
                <img
                    src="{{ $featured['poster_url'] ?? $featured['thumb_url'] }}"
                    alt="{{ $featured['name'] }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-50"
                >
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end pb-12">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $featured['name'] }}</h1>
                        <p class="text-gray-300 mb-6 max-w-2xl">
                            {{ $featured['origin_name'] ?? '' }}
                            • {{ $featured['year'] ?? '' }}
                        </p>
                        <div class="flex space-x-4">
                            <a href="{{ route('movie.watch', $featured['slug']) }}" class="bg-red-600 px-8 py-3 rounded font-bold hover:bg-red-700 transition">
                                ▶ Xem ngay
                            </a>
                            <a href="{{ route('movie.detail', $featured['slug']) }}" class="border border-white px-8 py-3 rounded font-bold hover:bg-white hover:text-black transition">
                                Chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}
                <div class="relative h-64 sm:h-80 md:h-96 lg:h-[500px] overflow-hidden">

                    <!-- Background image -->
                    <img src="{{ $featured['poster_url'] ?? $featured['thumb_url'] }}" alt="{{ $featured['name'] }}"
                        class="absolute inset-0 w-full h-full object-cover scale-110">

                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>

                    <!-- Blur overlay -->
                    <div class="absolute inset-0 backdrop-blur-[2px]"></div>

                    <!-- Content -->
                    <div class="relative mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">

                        <div class="max-w-xl">

                            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-2 sm:mb-4 leading-tight line-clamp-3">
                                {{ $featured['name'] }}
                            </h1>

                            <p class="text-gray-300 mb-4 sm:mb-6 text-sm sm:text-lg">
                                {{ $featured['origin_name'] ?? '' }}
                                <span class="mx-2">•</span>
                                {{ $featured['year'] ?? '' }}
                            </p>

                            <div class="flex flex-wrap gap-2 sm:gap-4">

                                <a href="{{ route('movie.watch', $featured['slug']) }}"
                                    class="bg-red-600 hover:bg-red-700 px-4 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold flex items-center gap-2 transition text-sm sm:text-base">

                                    ▶ Xem ngay
                                </a>

                                <a href="{{ route('movie.detail', $featured['slug']) }}"
                                    class="bg-white/10 hover:bg-white/20 px-4 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold backdrop-blur transition text-sm sm:text-base">

                                    Chi tiết
                                </a>

                            </div>

                        </div>

                    </div>

                </div>
            @endif
        @endif
    </div>

    <!-- Movies Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-6">Phim nổi bật</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($movies as $movie)
                    @include('components.movie-card', ['movie' => $movie])
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-400">Không tìm thấy phim</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

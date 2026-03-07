<div class="group cursor-pointer">
    <div class="relative overflow-hidden rounded-lg bg-gray-800 aspect-video mb-3">
        <img
            {{-- src="{{ $movie['poster_url'] ?? $movie['thumb_url'] ?? 'https://via.placeholder.com/300x400' }}" --}}
            src="https://img.ophim.live/uploads/movies/{{ $movie['thumb_url'] ?? $movie['thumb_url'] }}"
            alt="{{ $movie['name'] }}"
            class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
        >
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition duration-300 flex items-center justify-center">
            <a href="{{ route('movie.detail', $movie['slug']) }}" class="text-white opacity-0 group-hover:opacity-100 transition">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"></path>
                </svg>
            </a>
        </div>
        @if($movie['quality'] ?? false)
            <span class="absolute top-2 right-2 bg-red-600 px-2 py-1 rounded text-sm font-bold">
                {{ $movie['quality'] }}
            </span>
        @endif
    </div>

    <a href="{{ route('movie.detail', $movie['slug']) }}" class="block">
        <h3 class="font-semibold group-hover:text-red-600 transition truncate">
            {{ $movie['name'] }}
        </h3>
    </a>

    <p class="text-sm text-gray-400 truncate">
        {{ $movie['origin_name'] ?? '' }}
    </p>

    <div class="flex justify-between items-center mt-2 text-xs text-gray-400">
        <span>{{ $movie['year'] ?? 'N/A' }}</span>
        <span>
            @foreach(($movie['country'] ?? []) as $country)
                {{ $country['name'] ?? $country }}@if(!$loop->last), @endif
            @endforeach
        </span>
    </div>
</div>

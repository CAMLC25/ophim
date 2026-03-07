@extends('app')

@section('title', ($movie['name'] ?? 'Phim') . ' - Xem Phim - OPhim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-8">
        <!-- Video Player -->
        <div class="lg:col-span-3">
            <!-- Video Player -->
            <div id="video-player" class="bg-black rounded-lg overflow-hidden mb-4">
                @if(strpos($playUrl, 'm3u8') !== false || strpos($playUrl, 'mp4') !== false)
                    <!-- HLS Player -->
                    <video id="player" class="w-full h-full" controls>
                        <source src="{{ $playUrl }}" type="application/x-mpegURL">
                    </video>
                @else
                    <!-- Iframe Player -->
                    <iframe
                        id="iframe-player"
                        width="100%"
                        height="600"
                        src="{{ $playUrl }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                @endif
            </div>

            <!-- Info Bar -->
            <div class="bg-gray-800 rounded-lg p-4 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold">{{ $movie['name'] }}</h1>
                        <p class="text-gray-400 text-sm">
                            {{ $movie['origin_name'] ?? '' }} • Tập {{ $episode }} • {{ $serverName }}
                        </p>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex flex-wrap gap-2">
                    <!-- Favorite Button -->
                    <button id="favorite-btn" class="flex items-center gap-2 px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 transition font-bold">
                        @if(auth()->check() && auth()->user()->favorites()->where('movie_slug', $movie['slug'])->exists())
                            <span>❤️</span> Đã Yêu Thích
                        @else
                            <span>🤍</span> Yêu Thích
                        @endif
                    </button>

                    <!-- Auto Play Next -->
                    <label class="flex items-center gap-2 px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 transition cursor-pointer font-bold">
                        <input type="checkbox" id="autoplay-toggle" class="w-4 h-4">
                        <span>🔄 Tự Chuyển Tập</span>
                    </label>

                    <!-- Prev Episode -->
                    @php
                        $currentServerData = [];
                        if (!empty($movie['episodes'])) {
                            foreach ($movie['episodes'] as $server) {
                                if ($server['server_name'] == $serverName) {
                                    $currentServerData = $server['server_data'];
                                    break;
                                }
                            }
                        }
                        $currentIndex = collect($currentServerData)->search(fn($ep) => $ep['slug'] == $episode);
                        $prevEp = $currentIndex > 0 ? $currentServerData[$currentIndex - 1] : null;
                        $nextEp = isset($currentServerData[$currentIndex + 1]) ? $currentServerData[$currentIndex + 1] : null;
                    @endphp

                    @if($prevEp)
                        <a href="{{ route('movie.watch', [$movie['slug'], $prevEp['slug']]) }}"
                            class="episode-link flex items-center gap-2 px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 transition font-bold">
                            <span>⬅️</span> Tập Trước
                        </a>
                    @else
                        <button disabled class="flex items-center gap-2 px-4 py-2 rounded bg-gray-900 text-gray-500 font-bold opacity-50 cursor-not-allowed">
                            <span>⬅️</span> Tập Trước
                        </button>
                    @endif

                    <!-- Next Episode -->
                    @if($nextEp)
                        <a href="{{ route('movie.watch', [$movie['slug'], $nextEp['slug']]) }}"
                            class="episode-link flex items-center gap-2 px-4 py-2 rounded bg-red-600 hover:bg-red-700 transition font-bold">
                            <span>➡️</span> Tập Tiếp Theo
                        </a>
                    @else
                        <button disabled class="flex items-center gap-2 px-4 py-2 rounded bg-gray-900 text-gray-500 font-bold opacity-50 cursor-not-allowed">
                            <span>➡️</span> Tập Tiếp Theo
                        </button>
                    @endif

                    <!-- Toggle Episode List -->
                    <button id="toggle-episodes-btn" class="flex items-center gap-2 px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 transition font-bold">
                        <span>📺</span> Danh Sách Tập
                    </button>
                </div>
            </div>

            <!-- Affiliate Link Notification (nếu có) -->
            @if($affiliateLink)
                <div class="bg-blue-900 border border-blue-600 p-4 rounded-lg mb-6">
                    <p class="text-blue-200 text-sm">
                        💡 Khi bạn chọn tập, bạn sẽ được chuyển đến affiliate link để xem phim chất lượng cao.
                    </p>
                </div>
            @endif

            <!-- Movie Info -->
            <div class="bg-gray-800 p-6 rounded-lg">
                <h2 class="text-xl font-bold mb-4">Thông tin phim</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Năm</p>
                        <p class="font-bold">{{ $movie['year'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Chất lượng</p>
                        <p class="font-bold">{{ $movie['quality'] ?? 'HD' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Ngôn ngữ</p>
                        <p class="font-bold">{{ $movie['lang'] ?? 'Vietsub' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Server</p>
                        <p class="font-bold">{{ $serverName }}</p>
                    </div>
                </div>
            </div>

            <!-- Episodes List (Toggle) -->
            <div id="episodes-section" class="bg-gray-800 p-6 rounded-lg hidden">
                <h2 class="text-xl font-bold mb-4">Danh Sách Tập</h2>
                @if(!empty($movie['episodes']))
                    @foreach($movie['episodes'] as $server)
                        <div class="mb-6">
                            <p class="font-bold mb-3 text-red-600">{{ $server['server_name'] }}</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                @foreach($server['server_data'] as $ep)
                                    <a
                                        href="{{ route('movie.watch', [$movie['slug'], $ep['slug']]) }}"
                                        class="episode-link px-3 py-2 rounded text-center text-sm font-bold transition
                                        {{ $ep['slug'] == $episode ? 'bg-red-600 text-white' : 'bg-gray-700 hover:bg-gray-600 text-gray-200' }}"
                                    >
                                        {{ $ep['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-400">Không có dữ liệu tập</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Movie Card -->
            <div class="bg-gray-800 p-4 rounded-lg mb-6">
                <img
                    src="{{ $movie['poster_url'] ?? 'https://via.placeholder.com/300x400' }}"
                    alt="{{ $movie['name'] }}"
                    class="w-full rounded-lg mb-4"
                >
                <a href="{{ route('movie.detail', $movie['slug']) }}" class="block w-full bg-red-600 text-center px-4 py-2 rounded font-bold hover:bg-red-700 transition">
                    Chi tiết phim
                </a>
            </div>

            <!-- Related Movies -->
            <div class="bg-gray-800 p-4 rounded-lg">
                <h3 class="font-bold mb-4">Phim liên quan</h3>
                @if(!empty($movie['category']))
                    <div class="space-y-2">
                        @foreach(array_slice($movie['category'], 0, 5) as $category)
                            <a
                                href="{{ route('category', $category['slug']) }}"
                                class="block text-red-600 hover:underline text-sm"
                            >
                                → {{ $category['name'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- HLS.js Script -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    // ===== VIDEO PLAYER SETUP =====
    const video = document.getElementById('player');
    if (video && '{{ strpos($playUrl, "m3u8") }}' !== '') {
        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource('{{ $playUrl }}');
            hls.attachMedia(video);
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = '{{ $playUrl }}';
        }
    }

    // ===== FAVORITE BUTTON =====
    const favoriteBtn = document.getElementById('favorite-btn');
    const movieSlug = '{{ $movie["slug"] }}';
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', async function(e) {
            e.preventDefault();

            // Nếu chưa đăng nhập, redirect đến login
            if (!isAuthenticated) {
                window.location.href = '{{ route("login") }}';
                return;
            }

            try {
                const response = await fetch('{{ route("movie.toggle-favorite") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ slug: movieSlug })
                });

                if (!response.ok) {
                    console.error('Response not OK:', response.status);
                    return;
                }

                const data = await response.json();
                if (data.success) {
                    const isFavorite = data.is_favorite;
                    favoriteBtn.innerHTML = isFavorite
                        ? '<span>❤️</span> Đã Yêu Thích'
                        : '<span>🤍</span> Yêu Thích';
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
            }
        });
    }

    // ===== EPISODE LIST TOGGLE =====
    const toggleBtn = document.getElementById('toggle-episodes-btn');
    const episodesSection = document.getElementById('episodes-section');

    if (toggleBtn && episodesSection) {
        toggleBtn.addEventListener('click', function() {
            episodesSection.classList.toggle('hidden');
            toggleBtn.classList.toggle('bg-red-600');
        });
    }

    // ===== AFFILIATE REDIRECT ON EPISODE CLICK =====
    const affiliateUrl = '{{ $affiliateLink?->url ?? "" }}';
    const episodeLinks = document.querySelectorAll('.episode-link');

    episodeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Nếu có affiliate link, buka di tab mới
            if (affiliateUrl) {
                e.preventDefault();
                const episodeUrl = this.href;  // Lưu href trước
                console.log('Opening affiliate:', affiliateUrl);
                console.log('Episode URL:', episodeUrl);
                window.open(affiliateUrl, '_blank');
                // Sau 1 giây, chuyển đến tập này
                setTimeout(() => {
                    window.location.href = episodeUrl;
                }, 1000);
            }
            // Nếu không có affiliate, cứ để nó load bình thường
        });
    });

    // ===== AUTO-PLAY NEXT EPISODE =====
    const autoplayToggle = document.getElementById('autoplay-toggle');
    const savedAutoplay = localStorage.getItem('autoplay-next');

    if (autoplayToggle) {
        // Restore saved state
        if (savedAutoplay === 'true') {
            autoplayToggle.checked = true;
        }

        autoplayToggle.addEventListener('change', function() {
            localStorage.setItem('autoplay-next', this.checked);
        });

        // Setup auto-play when video ends
        const videoPlayer = document.getElementById('player');
        if (videoPlayer) {
            videoPlayer.addEventListener('ended', function() {
                if (autoplayToggle.checked) {
                    // Tìm link "Tập Tiếp Theo"
                    const nextBtn = Array.from(document.querySelectorAll('a')).find(btn =>
                        btn.textContent.includes('Tập Tiếp Theo') &&
                        !btn.hasAttribute('disabled')
                    );

                    if (nextBtn) {
                        console.log('Auto-playing next episode...');
                        setTimeout(() => {
                            nextBtn.click();
                        }, 2000);
                    }
                }
            });
        }
    }

    // Show episode list by default on mobile
    if (window.innerWidth < 768 && episodesSection) {
        episodesSection.classList.remove('hidden');
    }
</script>
@endsection

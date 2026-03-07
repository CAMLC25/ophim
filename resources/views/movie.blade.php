@extends('app')

@section('title', ($movie['name'] ?? 'Phim') . ' - OPhim')
@section('description', substr($movie['content'] ?? '', 0, 160))
@section('og_image', $movie['poster_url'] ?? '')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <!-- Poster -->
        <div class="md:col-span-1">
            <img
                src="{{ $movie['poster_url'] ?? 'https://via.placeholder.com/300x400' }}"
                alt="{{ $movie['name'] }}"
                class="w-full rounded-lg"
            >

            @auth
                <button
                    onclick="toggleFavorite('{{ $movie['slug'] }}', '{{ $movie['name'] }}', '{{ $movie['poster_url'] ?? '' }}')"
                    class="w-full mt-4 px-4 py-2 rounded font-bold transition
                    {{ $isFavorited ? 'bg-red-600 hover:bg-red-700' : 'border border-red-600 hover:bg-red-600' }}"
                >
                    {{ $isFavorited ? '❤️ Đã thích' : '🤍 Thích' }}
                </button>
            @endauth
        </div>

        <!-- Info -->
        <div class="md:col-span-3">
            <h1 class="text-4xl font-bold mb-2">{{ $movie['name'] }}</h1>
            <p class="text-xl text-gray-400 mb-4">{{ $movie['origin_name'] ?? '' }}</p>

            <div class="space-y-3 mb-6 text-gray-300">
                <p><strong>Năm:</strong> {{ $movie['year'] ?? 'N/A' }}</p>
                <p><strong>Chất lượng:</strong> {{ $movie['quality'] ?? 'HD' }}</p>
                <p><strong>Ngôn ngữ:</strong> {{ $movie['lang'] ?? 'Vietsub' }}</p>

                @if(!empty($movie['category']))
                    <p>
                        <strong>Thể loại:</strong>
                        @foreach($movie['category'] as $cat)
                            <a href="{{ route('category', $cat['slug']) }}" class="text-red-600 hover:underline">
                                {{ $cat['name'] }}
                            </a>@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif

                @if(!empty($movie['country']))
                    <p>
                        <strong>Quốc gia:</strong>
                        @foreach($movie['country'] as $country)
                            <a href="{{ route('country', $country['slug']) }}" class="text-red-600 hover:underline">
                                {{ $country['name'] }}
                            </a>@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif

                @if(!empty($movie['director']))
                    <p>
                        <strong>Đạo diễn:</strong>
                        {{ implode(', ', array_column($movie['director'], 'name')) }}
                    </p>
                @endif

                @if(!empty($movie['actor']))
                    <p>
                        <strong>Diễn viên:</strong>
                        {{ implode(', ', array_slice(array_column($movie['actor'], 'name'), 0, 5)) }}
                        @if(count($movie['actor']) > 5)
                            ...
                        @endif
                    </p>
                @endif
            </div>

            <!-- Watch Button -->
            <a href="{{ route('movie.watch', $movie['slug']) }}" class="inline-block bg-red-600 px-8 py-3 rounded font-bold hover:bg-red-700 transition">
                ▶ XEM PHIM
            </a>
        </div>
    </div>

    <!-- Content -->
    @if($movie['content'] ?? false)
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Nội dung</h2>
            <p class="text-gray-300 leading-relaxed">{{ $movie['content'] }}</p>
        </div>
    @endif

    <!-- Episodes -->
    @if(!empty($movie['episodes']))
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Tập phim</h2>
            @foreach($movie['episodes'] as $server)
                <div class="mb-6">
                    <h3 class="font-bold mb-3 text-lg">{{ $server['server_name'] }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:grid-cols-12 gap-2">
                        @foreach($server['server_data'] as $episode)
                            <a
                                href="{{ route('movie.watch', [$movie['slug'], $episode['slug']]) }}"
                                class="px-3 py-2 bg-gray-800 hover:bg-red-600 rounded text-center text-sm transition"
                            >
                                {{ $episode['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Images -->
    @if(!empty($images))
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Hình ảnh</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($images as $image)
                    <img
                        src="{{ $image['thumb_url'] ?? $image['image_url'] }}"
                        alt="Hình ảnh"
                        class="w-full rounded-lg cursor-pointer hover:opacity-75 transition"
                    >
                @endforeach
            </div>
        </div>
    @endif

    <!-- Comments Section -->
    <div class="mb-8 border-t border-gray-800 pt-8">
        <h2 class="text-2xl font-bold mb-6">Bình luận</h2>

        @auth
            <form action="{{ route('comment.store') }}" method="POST" class="mb-8">
                @csrf
                <input type="hidden" name="movie_slug" value="{{ $movie['slug'] }}">
                <textarea
                    name="content"
                    placeholder="Viết bình luận..."
                    class="w-full bg-gray-800 text-white px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600"
                    rows="4"
                    required
                ></textarea>
                <button type="submit" class="mt-3 bg-red-600 px-6 py-2 rounded font-bold hover:bg-red-700 transition">
                    Gửi
                </button>
            </form>
        @else
            <p class="text-gray-400 mb-6">
                <a href="{{ route('login') }}" class="text-red-600 hover:underline">Đăng nhập</a>
                để bình luận
            </p>
        @endauth

        <div class="space-y-4">
            @include('components.nested-comments', ['comments' => $comments, 'movie' => $movie])
        </div>

        {{ $comments->links() }}
    </div>
</div>

<script>
function toggleFavorite(slug, name, poster) {
    const isFavorited = document.querySelector('[onclick]').textContent.includes('Đã thích');

    if (isFavorited) {
        fetch(`/yeu-thich/${slug}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelecto    r('meta[name="csrf-token"]')?.content
            }
        }).then(() => location.reload());
    } else {
        fetch('/yeu-thich/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                movie_slug: slug,
                movie_name: name,
                poster_url: poster
            })
        }).then(() => location.reload());
    }
}
</script>
@endsection

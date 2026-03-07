@extends('app')

@section('title', 'Tìm kiếm' . ($query ? ': ' . $query : '') . ' - OPhim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-2">Tìm kiếm</h1>
    @if($query)
        <p class="text-gray-400 mb-8">Kết quả cho: "<strong>{{ $query }}</strong>"</p>
    @else
        <p class="text-gray-400 mb-8">Hãy nhập từ khóa để tìm kiếm phim</p>
    @endif

    @if($query)
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($movies as $movie)
                @include('components.movie-card', ['movie' => $movie])
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-400">Không tìm thấy phim nào</p>
                </div>
            @endforelse
        </div>

        @include('components.pagination')
    @endif
</div>
@endsection

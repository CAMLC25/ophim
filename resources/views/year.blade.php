@extends('app')

@section('title', 'Phim năm ' . $year . ' - OPhim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Phim năm {{ $year }}</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($movies as $movie)
            @include('components.movie-card', ['movie' => $movie])
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-400">Không tìm thấy phim</p>
            </div>
        @endforelse
    </div>

    @include('components.pagination')
</div>
@endsection

@extends('app')

@section('title', (($currentCountry['name'] ?? 'Quốc gia')) . ' - OPhim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Countries Filter -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-4">Quốc gia</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($countries as $country)
                <a
                    href="{{ route('country', $country['slug']) }}"
                    class="px-4 py-2 rounded transition
                    {{ $country['slug'] == $slug ? 'bg-red-600' : 'bg-gray-800 hover:bg-gray-700' }}"
                >
                    {{ $country['name'] }}
                </a>
            @endforeach
        </div>
    </div>

    @if($currentCountry)
        <h1 class="text-3xl font-bold mb-8">{{ $currentCountry['name'] }}</h1>
    @endif

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

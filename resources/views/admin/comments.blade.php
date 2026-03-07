@extends('app')

@section('title', 'Quản lý bình luận - OPhim Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Quản lý bình luận</h1>

    <div class="space-y-4">
        @forelse($comments as $comment)
            <div class="bg-gray-800 p-6 rounded-lg">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-bold">{{ $comment->user->name }}</p>
                        <p class="text-sm text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <form action="{{ route('admin.delete-comment', $comment) }}" method="POST" onsubmit="return confirm('Xóa bình luận?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Xóa</button>
                    </form>
                </div>

                <p class="text-gray-300 mb-3">{{ $comment->content }}</p>

                <a href="{{ route('movie.detail', $comment->movie_slug) }}" class="text-red-600 hover:underline text-sm">
                    Xem phim: {{ $comment->movie_slug }}
                </a>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-400">Không có bình luận nào</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>
@endsection

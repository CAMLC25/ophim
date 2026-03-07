@forelse($comments as $comment)
    <div class="bg-gray-800 p-4 rounded-lg">
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold">{{ $comment->user->name }}</p>
                <p class="text-sm text-gray-400">
                    @if(is_object($comment->created_at))
                        {{ $comment->created_at->diffForHumans() }}
                    @else
                        {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                    @endif
                </p>
            </div>
            @can('delete', $comment)
                <form action="{{ route('comment.destroy', $comment) }}" method="POST" onsubmit="return confirm('Xóa bình luận?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline text-sm">Xóa</button>
                </form>
            @endcan
        </div>
        <p class="text-gray-300 mt-3">{{ $comment->content }}</p>

        <!-- Reply Button -->
        <button
            onclick="toggleReplyForm({{ $comment->id }})"
            class="text-red-600 hover:underline text-sm mt-3"
        >
            Trả lời
        </button>

        <!-- Reply Form -->
        <form id="reply-form-{{ $comment->id }}" action="{{ route('comment.store') }}" method="POST" class="mt-3 hidden">
            @csrf
            <input type="hidden" name="movie_slug" value="{{ $movie['slug'] }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea
                name="content"
                placeholder="Viết trả lời..."
                class="w-full bg-gray-700 text-white px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-sm"
                rows="3"
                required
            ></textarea>
            <div class="mt-2 flex gap-2">
                <button type="submit" class="bg-red-600 px-4 py-1 rounded text-sm hover:bg-red-700 transition">
                    Gửi
                </button>
                <button
                    type="button"
                    onclick="toggleReplyForm({{ $comment->id }})"
                    class="bg-gray-700 px-4 py-1 rounded text-sm hover:bg-gray-600 transition"
                >
                    Hủy
                </button>
            </div>
        </form>

        <!-- Replies -->
        @if($comment->replies->count() > 0)
            <div class="mt-4 ml-8 space-y-3 border-l-2 border-gray-700 pl-4">
                @include('components.nested-comments', ['comments' => $comment->replies, 'movie' => $movie])
            </div>
        @endif
    </div>
@empty
    <p class="text-gray-400">Chưa có bình luận</p>
@endforelse

<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.querySelector('textarea').focus();
        }
    }
</script>

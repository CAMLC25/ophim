@if(!empty($pagination))
    <nav class="flex justify-center items-center space-x-2 mt-12 pb-8">
        @if($pagination['currentPage'] > 1)
            <a href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['page' => 1])) }}" class="px-3 py-2 rounded bg-gray-800 hover:bg-red-600 transition">
                First
            </a>
            <a href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['page' => $pagination['currentPage'] - 1])) }}" class="px-3 py-2 rounded bg-gray-800 hover:bg-red-600 transition">
                Previous
            </a>
        @endif

        @for($i = max(1, $pagination['currentPage'] - 2); $i <= min($pagination['totalPages'], $pagination['currentPage'] + 2); $i++)
            @if($i == $pagination['currentPage'])
                <span class="px-3 py-2 rounded bg-red-600">{{ $i }}</span>
            @else
                <a href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['page' => $i])) }}" class="px-3 py-2 rounded bg-gray-800 hover:bg-red-600 transition">
                    {{ $i }}
                </a>
            @endif
        @endfor

        @if($pagination['currentPage'] < $pagination['totalPages'])
            <a href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['page' => $pagination['currentPage'] + 1])) }}" class="px-3 py-2 rounded bg-gray-800 hover:bg-red-600 transition">
                Next
            </a>
            <a href="{{ route(Route::currentRouteName(), array_merge(Route::current()->parameters(), ['page' => $pagination['totalPages']])) }}" class="px-3 py-2 rounded bg-gray-800 hover:bg-red-600 transition">
                Last
            </a>
        @endif
    </nav>
@endif

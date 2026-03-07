@extends('app')

@section('title', 'Quản lý Affiliate Links - OPhim Admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold">Quản lý Affiliate Links</h1>
        <p class="text-gray-400 mt-2">Quản lý liên kết affiliate cho các phim</p>
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 rounded-lg mb-6 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'" class="text-lg">×</button>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="flex flex-wrap gap-2 mb-8 border-b border-gray-700">
        <button onclick="switchTab('create')" class="tab-btn active px-6 py-3 border-b-2 border-red-600 text-red-600 font-bold transition">
            ➕ Tạo Link
        </button>
        <button onclick="switchTab('active')" class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-400 hover:text-white font-bold transition">
            ✅ Đang Hoạt Động
        </button>
        <button onclick="switchTab('inactive')" class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-400 hover:text-white font-bold transition">
            ⏸️ Tạm Dừng
        </button>
    </div>

    <!-- TAB 1: CREATE -->
    <div id="create-tab" class="tab-content">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Create Single Link -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span class="text-red-600">📝</span> Thêm Link Đơn Lẻ
                </h2>
                <form action="{{ route('admin.create-affiliate-link') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold mb-2">Tên Phim (Slug)</label>
                        <input type="text" name="name" required placeholder="ví dụ: avengers-endgame"
                            class="w-full bg-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">URL Affiliate *</label>
                        <input type="url" name="url" required placeholder="https://..."
                            class="w-full bg-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Mùa (Tùy Chọn)</label>
                        <input type="text" name="season" placeholder="S1, S2, S3..."
                            class="w-full bg-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 px-6 py-2 rounded font-bold transition mt-4">
                        Thêm Link
                    </button>
                </form>
            </div>

            <!-- Bulk Apply -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <span class="text-red-600">⚡</span> Áp Dụng Hàng Loạt
                </h2>
                <form action="{{ route('admin.bulk-apply-affiliate-link') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold mb-2">URL Affiliate *</label>
                        <input type="url" name="url" required placeholder="https://..."
                            class="w-full bg-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Mùa (Tùy Chọn)</label>
                        <input type="text" name="season" placeholder="S1, S2, S3..."
                            class="w-full bg-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600">
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center cursor-pointer bg-gray-700 p-3 rounded hover:bg-gray-600 transition">
                            <input type="radio" name="apply_to" value="all" class="w-4 h-4"
                                onchange="document.getElementById('bulk-select').style.display='none'">
                            <span class="ml-3 font-bold">Áp dụng cho TẤT CẢ phim</span>
                        </label>

                        <label class="flex items-center cursor-pointer bg-gray-700 p-3 rounded hover:bg-gray-600 transition">
                            <input type="radio" name="apply_to" value="selected" class="w-4 h-4" checked
                                onchange="document.getElementById('bulk-select').style.display='block'">
                            <span class="ml-3 font-bold">Chọn phim cụ thể</span>
                        </label>
                    </div>

                    <div id="bulk-select" class="space-y-3">
                        <input type="text" id="bulk-search" placeholder="🔍 Tìm phim..."
                            class="w-full bg-gray-700 text-white px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-600">

                        <div id="bulk-results" class="bg-gray-700 rounded p-3 max-h-32 overflow-y-auto hidden"></div>

                        <div id="bulk-selected" class="bg-gray-700 rounded p-3 max-h-40 overflow-y-auto">
                            <p class="text-gray-400 text-sm">Chưa chọn phim nào</p>
                        </div>
                    </div>

                    <div id="bulk-movie-container"></div>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 px-6 py-2 rounded font-bold transition">
                        Áp Dụng
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2: ACTIVE LINKS -->
    <div id="active-tab" class="tab-content hidden">
        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700 bg-gray-900">
                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <span>✅</span> Links Đang Hoạt Động
                </h2>
                <p class="text-gray-400 text-sm mt-1">{{ $links->where('active', true)->count() }} link đang hoạt động</p>
            </div>

            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold">Tên Phim</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Mùa</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">URL</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $activeLinks = $links->where('active', true); @endphp
                    @forelse($activeLinks as $link)
                        <tr class="border-t border-gray-700 hover:bg-gray-700 transition">
                            <td class="px-6 py-4 font-semibold">{{ $link->name }}</td>
                            <td class="px-6 py-4">{{ $link->season ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ $link->url }}" target="_blank" class="text-red-600 hover:text-red-400 truncate max-w-xs block">
                                    {{ substr($link->url, 0, 40) }}...
                                </a>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <form action="{{ route('admin.update-affiliate-link', $link) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $link->name }}">
                                    <input type="hidden" name="url" value="{{ $link->url }}">
                                    <input type="hidden" name="season" value="{{ $link->season }}">
                                    <input type="hidden" name="active" value="0">
                                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 px-3 py-1 rounded text-sm transition">
                                        Tạm Dừng
                                    </button>
                                </form>
                                <form action="{{ route('admin.delete-affiliate-link', $link) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Xóa link này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm transition">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                Không có link nào đang hoạt động
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: INACTIVE LINKS -->
    <div id="inactive-tab" class="tab-content hidden">
        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700 bg-gray-900">
                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <span>⏸️</span> Links Tạm Dừng
                </h2>
                <p class="text-gray-400 text-sm mt-1">{{ $links->where('active', false)->count() }} link tạm dừng</p>
            </div>

            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold">Tên Phim</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Mùa</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">URL</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $inactiveLinks = $links->where('active', false); @endphp
                    @forelse($inactiveLinks as $link)
                        <tr class="border-t border-gray-700 hover:bg-gray-700 transition opacity-75">
                            <td class="px-6 py-4 font-semibold">{{ $link->name }}</td>
                            <td class="px-6 py-4">{{ $link->season ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ $link->url }}" target="_blank" class="text-red-600 hover:text-red-400 truncate max-w-xs block">
                                    {{ substr($link->url, 0, 40) }}...
                                </a>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <form action="{{ route('admin.update-affiliate-link', $link) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $link->name }}">
                                    <input type="hidden" name="url" value="{{ $link->url }}">
                                    <input type="hidden" name="season" value="{{ $link->season }}">
                                    <input type="hidden" name="active" value="1">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-sm transition">
                                        Kích Hoạt
                                    </button>
                                </form>
                                <form action="{{ route('admin.delete-affiliate-link', $link) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Xóa link này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm transition">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                Không có link nào tạm dừng
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let bulkSelectedMovies = [];
    let bulkSearchTimeout;

    function switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-b-2', 'border-red-600', 'text-red-600');
            el.classList.add('border-transparent', 'text-gray-400');
        });

        // Show selected tab
        const tabEl = document.getElementById(tabName + '-tab');
        const btnEl = event.target;

        if (tabEl) tabEl.classList.remove('hidden');
        if (btnEl) {
            btnEl.classList.add('border-b-2', 'border-red-600', 'text-red-600');
            btnEl.classList.remove('border-transparent', 'text-gray-400');
        }
    }

    // Bulk search
    const bulkSearchEl = document.getElementById('bulk-search');
    if (bulkSearchEl) {
        bulkSearchEl.addEventListener('keyup', function() {
            clearTimeout(bulkSearchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                document.getElementById('bulk-results').classList.add('hidden');
                return;
            }

            bulkSearchTimeout = setTimeout(() => {
                fetch(`{{ route('admin.search-movies') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(movies => {
                        const resultsDiv = document.getElementById('bulk-results');
                        resultsDiv.innerHTML = '';

                        if (movies.length === 0) {
                            resultsDiv.innerHTML = '<p class="text-gray-400 text-sm">Không tìm thấy phim</p>';
                        } else {
                            movies.forEach(movie => {
                                const item = document.createElement('div');
                                item.className = 'p-2 hover:bg-gray-600 cursor-pointer rounded flex justify-between items-center mb-1';
                                item.innerHTML = `
                                    <span class="text-sm">${movie.name}</span>
                                    <button type="button" class="bg-red-600 hover:bg-red-700 px-2 py-1 text-xs rounded"
                                        onclick="addBulkMovie('${movie.slug}', '${movie.name}')">
                                        Chọn
                                    </button>
                                `;
                                resultsDiv.appendChild(item);
                            });
                        }
                        resultsDiv.classList.remove('hidden');
                    });
            }, 300);
        });
    }

    function addBulkMovie(slug, name) {
        if (!bulkSelectedMovies.find(m => m.slug === slug)) {
            bulkSelectedMovies.push({ slug, name });
            updateBulkMoviesList();
        }
        document.getElementById('bulk-search').value = '';
        document.getElementById('bulk-results').classList.add('hidden');
    }

    function removeBulkMovie(slug) {
        bulkSelectedMovies = bulkSelectedMovies.filter(m => m.slug !== slug);
        updateBulkMoviesList();
    }

    function updateBulkMoviesList() {
        const div = document.getElementById('bulk-selected');
        const container = document.getElementById('bulk-movie-container');
        container.innerHTML = '';

        if (bulkSelectedMovies.length === 0) {
            div.innerHTML = '<p class="text-gray-400 text-sm">Chưa chọn phim nào</p>';
        } else {
            div.innerHTML = bulkSelectedMovies.map(m => `
                <div class="bg-gray-600 rounded p-2 mb-1 flex justify-between items-center text-sm">
                    <span>${m.name}</span>
                    <button type="button" class="text-red-400 hover:text-red-300 text-xs" onclick="removeBulkMovie('${m.slug}')">
                        ✕
                    </button>
                </div>
            `).join('');

            // Create hidden inputs
            bulkSelectedMovies.forEach(m => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'movie_slugs[]';
                input.value = m.slug;
                container.appendChild(input);
            });
        }
    }
</script>

<style>
    .tab-btn {
        border-bottom-width: 2px;
    }

    .tab-btn.active {
        border-color: rgb(220, 38, 38);
        color: rgb(220, 38, 38);
    }
</style>
@endsection

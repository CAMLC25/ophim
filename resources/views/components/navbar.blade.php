<nav class="bg-black bg-opacity-90 sticky top-0 z-50 border-b border-gray-800">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left: Logo + Main Menu -->
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 flex-shrink-0">
                    <span class="text-2xl font-bold text-red-600">OPhim</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-white hover:text-red-600 transition font-medium">
                        Phim Lẻ
                    </a>
                    <a href="{{ route('home') }}" class="text-white hover:text-red-600 transition font-medium">
                        Phim Bộ
                    </a>

                    <!-- Thể Loại Dropdown -->
                    <div class="relative dropdown-menu group">
                        <button class="text-white hover:text-red-600 transition font-medium flex items-center gap-1">
                            Thể Loại
                            <svg class="w-4 h-4 transition group-hover:rotate-180" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div
                            class="hidden group-hover:block dropdown-content absolute left-0 mt-0 w-96 bg-black border border-gray-800 rounded-lg p-6 shadow-2xl z-50">
                            <div class="grid grid-cols-4 gap-4">
                                @php
                                    $categories = cache()->remember('categories', 3600, function () {
                                        $service = app(\App\Services\OphimService::class);
                                        return $service->getCategories();
                                    });
                                @endphp
                                @foreach ($categories as $category)
                                    <a href="{{ route('category', $category['slug']) }}"
                                        class="text-gray-300 hover:text-red-600 text-sm transition whitespace-nowrap">
                                        {{ $category['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quốc gia Dropdown -->
                    <div class="relative dropdown-menu group">
                        <button class="text-white hover:text-red-600 transition font-medium flex items-center gap-1">
                            Quốc gia
                            <svg class="w-4 h-4 transition group-hover:rotate-180" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div
                            class="hidden group-hover:block dropdown-content absolute left-0 mt-0 w-96 bg-black border border-gray-800 rounded-lg p-6 shadow-2xl max-h-96 overflow-y-auto z-50">
                            <div class="grid grid-cols-4 gap-4">
                                @php
                                    $countries = cache()->remember('countries', 3600, function () {
                                        $service = app(\App\Services\OphimService::class);
                                        return $service->getCountries();
                                    });
                                @endphp
                                @foreach ($countries as $country)
                                    <a href="{{ route('country', $country['slug']) }}"
                                        class="text-gray-300 hover:text-red-600 text-sm transition whitespace-nowrap">
                                        {{ $country['name'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('category', 'short-drama') }}" class="text-white hover:text-red-600 transition font-medium">
                        Short Drama
                    </a>
                </div>
            </div>

            <!-- Right: Search + Auth + Download App -->
            <div class="flex items-center space-x-4">
                <!-- Search for Desktop -->
                <div class="hidden md:block relative">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input type="text" name="q" placeholder="Tìm kiếm..."
                            class="bg-gray-800 text-white px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-sm w-40 lg:w-72"
                            value="{{ request('q') }}">
                        <button type="submit" class="absolute right-3 top-2 text-gray-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Auth Links for Desktop -->
                <div class="hidden md:flex items-center space-x-3">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-2 hover:text-red-600 transition">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div
                                class="hidden group-hover:block absolute right-0 w-48 bg-black border border-gray-800 rounded-lg py-2 shadow-lg z-50">
                                <a href="{{ route('user.profile') }}"
                                    class="block px-4 py-2 hover:bg-gray-800 hover:text-red-600 transition text-sm">
                                    👤 Thông tin cá nhân
                                </a>
                                <a href="{{ route('user.favorites') }}"
                                    class="block px-4 py-2 hover:bg-gray-800 hover:text-red-600 transition text-sm">
                                    ❤️ Yêu thích
                                </a>
                                <a href="{{ route('user.watch-history') }}"
                                    class="block px-4 py-2 hover:bg-gray-800 hover:text-red-600 transition text-sm">
                                    📺 Lịch sử xem
                                </a>
                                @if (auth()->user()->is_admin)
                                    <hr class="border-gray-800 my-2">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 hover:bg-gray-800 hover:text-yellow-600 transition text-sm">
                                        ⚙️ Admin
                                    </a>
                                @endif
                                <hr class="border-gray-800 my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-800 hover:text-red-600 transition text-sm">
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-red-600 transition text-sm">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-red-600 px-4 py-2 rounded hover:bg-red-700 transition text-sm font-medium">
                            Đăng ký
                        </a>
                    @endauth
                </div>

                {{-- <!-- Download App Button -->
                <button
                    class="hidden sm:flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-bold transition cursor-pointer">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 6a1 1 0 012 0v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6z">
                        </path>
                    </svg>
                    <span class="text-sm">Tải ứng dụng</span>
                </button> --}}

                <!-- Mobile menu button -->
                <button class="lg:hidden p-2 rounded-lg hover:bg-gray-800 transition"
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden pb-4 border-t border-gray-800">
            <!-- Mobile Search -->
            <form action="{{ route('search') }}" method="GET" class="mb-4 px-2 sm:px-4 pt-4">
                <input type="text" name="q" placeholder="Tìm kiếm..."
                    class="w-full bg-gray-800 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm" value="{{ request('q') }}">
            </form>

            <!-- Mobile Menu Items -->
            <div class="space-y-1 px-2 sm:px-4">
                <a href="{{ route('home') }}"
                    class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                    🏠 Phim Lẻ
                </a>
                <a href="{{ route('home') }}"
                    class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                    📺 Phim Bộ
                </a>

                <!-- Mobile Categories Accordion -->
                <button onclick="document.getElementById('mobile-categories').classList.toggle('hidden')"
                    class="w-full text-left px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm font-medium flex justify-between items-center">
                    🎬 Thể Loại
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div id="mobile-categories" class="hidden bg-gray-900 rounded-lg p-2 sm:p-3 mb-2">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @php
                            $categories = cache()->remember('categories', 3600, function () {
                                $service = app(\App\Services\OphimService::class);
                                return $service->getCategories();
                            });
                        @endphp
                        @foreach ($categories as $category)
                            <a href="{{ route('category', $category['slug']) }}"
                                class="text-gray-300 hover:text-red-600 text-xs transition">
                                {{ $category['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile Countries Accordion -->
                <button onclick="document.getElementById('mobile-countries').classList.toggle('hidden')"
                    class="w-full text-left px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm font-medium flex justify-between items-center">
                    🌍 Quốc gia
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div id="mobile-countries" class="hidden bg-gray-900 rounded-lg p-2 sm:p-3 mb-2 max-h-48 overflow-y-auto">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @php
                            $countries = cache()->remember('countries', 3600, function () {
                                $service = app(\App\Services\OphimService::class);
                                return $service->getCountries();
                            });
                        @endphp
                        @foreach ($countries as $country)
                            <a href="{{ route('country', $country['slug']) }}"
                                class="text-gray-300 hover:text-red-600 text-xs sm:text-xs transition line-clamp-1">
                                {{ $country['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('home') }}"
                    class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                    🎥 TV Shows
                </a>

                <!-- Mobile Auth -->
                <div class="border-t border-gray-800 mt-4 pt-4 px-2 sm:px-0">
                    @auth
                        <a href="{{ route('user.profile') }}"
                            class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                            👤 {{ auth()->user()->name }}
                        </a>
                        <a href="{{ route('user.favorites') }}"
                            class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                            ❤️ Yêu thích
                        </a>
                        <a href="{{ route('user.watch-history') }}"
                            class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                            📺 Lịch sử xem
                        </a>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-yellow-600 rounded transition text-xs sm:text-sm">
                                ⚙️ Admin
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                                Đăng xuất
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block px-3 sm:px-4 py-2 hover:bg-gray-800 hover:text-red-600 rounded transition text-xs sm:text-sm">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}"
                            class="block px-3 sm:px-4 py-2 bg-red-600 hover:bg-red-700 rounded transition text-xs sm:text-sm font-medium">
                            Đăng ký
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.addEventListener('mouseenter', function() {
                const content = this.querySelector('.dropdown-content');
                if (content) content.classList.remove('hidden');
            });

            menu.addEventListener('mouseleave', function() {
                const content = this.querySelector('.dropdown-content');
                if (content) content.classList.add('hidden');
            });
        });
    });
</script>

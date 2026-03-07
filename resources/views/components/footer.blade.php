<footer class="bg-black border-t border-gray-800 pt-12 pb-8 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <h3 class="text-xl font-bold text-red-600 mb-4">OPhim</h3>
                <p class="text-gray-400">Website xem phim online miễn phí, cập nhật phim mới hàng ngày.</p>
            </div>

            <div>
                <h4 class="font-bold mb-4">Danh sách</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('filter-list', 'phim-moi') }}" class="hover:text-red-600 transition">Phim mới</a></li>
                    <li><a href="{{ route('filter-list', 'phim-le') }}" class="hover:text-red-600 transition">Phim lẻ</a></li>
                    <li><a href="{{ route('filter-list', 'phim-bo') }}" class="hover:text-red-600 transition">Phim bộ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-4">Khác</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-red-600 transition">Liên hệ</a></li>
                    <li><a href="#" class="hover:text-red-600 transition">DMCA</a></li>
                    <li><a href="#" class="hover:text-red-600 transition">Điều khoản</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-4">Theo dõi</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-red-600 transition">Facebook</a>
                    <a href="#" class="text-gray-400 hover:text-red-600 transition">Twitter</a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 text-center text-gray-500">
            <p>&copy; 2024 OPhim. All rights reserved.</p>
        </div>
    </div>
</footer>

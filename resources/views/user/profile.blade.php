@extends('app')

@section('title', 'Thông tin cá nhân - OPhim')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Thông tin cá nhân</h1>

    @if(session('success'))
        <div class="bg-green-600 text-white p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-800 rounded-lg p-8">
        <form action="{{ route('user.update-profile') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-gray-400 mb-2">Tên</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-600"
                    required
                >
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-400 mb-2">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-600"
                    required
                >
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-400 mb-2">Mật khẩu mới (để trống nếu không muốn thay đổi)</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-600"
                >
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-gray-400 mb-2">Xác nhận mật khẩu</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-600"
                >
            </div>

            <div class="flex gap-4">
                <button
                    type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded font-bold hover:bg-red-700 transition"
                >
                    Cập nhật
                </button>
                <a
                    href="{{ route('user.favorites') }}"
                    class="bg-gray-700 text-white px-6 py-2 rounded font-bold hover:bg-gray-600 transition"
                >
                    Quay lại
                </a>
            </div>
        </form>
    </div>

    <!-- User Stats -->
    <div class="mt-12 grid grid-cols-3 gap-4">
        <div class="bg-gray-800 rounded-lg p-6 text-center">
            <p class="text-gray-400 mb-2">Phim yêu thích</p>
            <p class="text-3xl font-bold">{{ auth()->user()->favorites()->count() }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 text-center">
            <p class="text-gray-400 mb-2">Lịch sử xem</p>
            <p class="text-3xl font-bold">{{ auth()->user()->watchHistory()->count() }}</p>
        </div>
        <div class="bg-gray-800 rounded-lg p-6 text-center">
            <p class="text-gray-400 mb-2">Bình luận</p>
            <p class="text-3xl font-bold">{{ auth()->user()->comments()->count() }}</p>
        </div>
    </div>

    <!-- User Links -->
    <div class="mt-8 flex gap-4">
        <a href="{{ route('user.favorites') }}" class="flex-1 text-center bg-gray-800 hover:bg-gray-700 p-4 rounded transition">
            ❤️ Phim yêu thích
        </a>
        <a href="{{ route('user.watch-history') }}" class="flex-1 text-center bg-gray-800 hover:bg-gray-700 p-4 rounded transition">
            📺 Lịch sử xem
        </a>
    </div>
</div>
@endsection

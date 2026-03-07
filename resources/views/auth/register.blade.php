@extends('app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-gray-900 border border-gray-800 rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Đăng Ký</h2>

        @if ($errors->any())
            <div class="bg-red-600 text-white p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-400 mb-2">Tên</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full bg-gray-800 text-white px-4 py-2 rounded border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                    required
                >
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-400 mb-2">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full bg-gray-800 text-white px-4 py-2 rounded border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                    required
                >
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-400 mb-2">Mật khẩu</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full bg-gray-800 text-white px-4 py-2 rounded border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                    required
                >
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-400 mb-2">Xác nhận mật khẩu</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full bg-gray-800 text-white px-4 py-2 rounded border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                    required
                >
            </div>

            <button
                type="submit"
                class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition font-semibold"
            >
                Đăng Ký
            </button>
        </form>

        <p class="text-center text-gray-400 mt-4">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="text-red-600 hover:text-red-500">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection

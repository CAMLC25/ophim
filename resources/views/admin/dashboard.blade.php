@extends('app')

@section('title', 'Admin Dashboard - OPhim')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800 p-6 rounded-lg">
            <h3 class="text-gray-400 text-sm">Tổng người dùng</h3>
            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg">
            <h3 class="text-gray-400 text-sm">Tổng bình luận</h3>
            <p class="text-3xl font-bold">{{ \App\Models\Comment::count() }}</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg">
            <h3 class="text-gray-400 text-sm">Affiliate links</h3>
            <p class="text-3xl font-bold">{{ \App\Models\AffiliateLink::count() }}</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg">
            <h3 class="text-gray-400 text-sm">Active Affiliates</h3>
            <p class="text-3xl font-bold">{{ \App\Models\AffiliateLink::where('active', true)->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('admin.users') }}" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition">
            <h3 class="text-xl font-bold mb-2">👥 Quản lý người dùng</h3>
            <p class="text-gray-400">Xem, chỉnh sửa và xóa người dùng</p>
        </a>

        <a href="{{ route('admin.comments') }}" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition">
            <h3 class="text-xl font-bold mb-2">💬 Quản lý bình luận</h3>
            <p class="text-gray-400">Kiểm duyệt và xóa bình luận</p>
        </a>

        <a href="{{ route('admin.affiliate-links') }}" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition">
            <h3 class="text-xl font-bold mb-2">🔗 Affiliate Links</h3>
            <p class="text-gray-400">Quản lý đường dẫn affiliate</p>
        </a>

        <a href="{{ route('home') }}" class="bg-gray-800 p-6 rounded-lg hover:bg-gray-700 transition">
            <h3 class="text-xl font-bold mb-2">← Quay lại</h3>
            <p class="text-gray-400">Về trang chủ</p>
        </a>
    </div>
</div>
@endsection

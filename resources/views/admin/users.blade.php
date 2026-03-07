@extends('app')

@section('title', 'Quản lý người dùng - OPhim Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Quản lý người dùng</h1>

    <div class="bg-gray-800 rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Tên</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Quyền</th>
                    <th class="px-6 py-3 text-left">Ngày tạo</th>
                    <th class="px-6 py-3 text-left">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-t border-gray-700 hover:bg-gray-700 transition">
                        <td class="px-6 py-4">{{ $user->id }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.update-user-admin', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center space-x-2">
                                    <input type="hidden" name="is_admin" value="0">
                                    <input type="checkbox" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span>{{ $user->is_admin ? 'Admin' : 'User' }}</span>
                                </div>
                            </form>
                        </td>
                        <td class="px-6 py-4">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.delete-user', $user) }}" method="POST" onsubmit="return confirm('Xóa người dùng này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection

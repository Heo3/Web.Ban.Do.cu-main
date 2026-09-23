@extends('layouts.app')

@section('title', 'Quản Lý Tài Khoản Người Dùng - Admin Chợ Tốt')

@push('styles')
<style>
    .admin-page {
        padding: 30px 0 60px;
    }

    .admin-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .admin-title {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-nav {
        display: flex;
        gap: 10px;
        background: #ffffff;
        padding: 8px 12px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .admin-nav-item {
        padding: 8px 18px;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .admin-nav-item:hover {
        background: #f1f5f9;
        color: var(--primary);
    }

    .admin-nav-item.active {
        background: var(--primary);
        color: #ffffff;
    }

    /* Filters */
    .filter-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
    }

    .filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-input {
        padding: 8px 14px;
        font-size: 14px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        outline: none;
        min-width: 220px;
    }

    .filter-input:focus {
        border-color: var(--primary);
    }

    /* Table */
    .table-container {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        overflow-x: auto;
        box-shadow: var(--shadow-sm);
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    .users-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .users-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .users-table tr:hover td {
        background: #fcfcfd;
    }

    .user-avatar-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .action-btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 6px;
        border: 1px solid var(--border);
        background: #ffffff;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        color: var(--dark);
        transition: all 0.15s;
    }

    .action-btn-sm:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1 class="admin-title">
                <i class="fa-solid fa-users-gear" style="color: #3b82f6;"></i>
                Quản Lý Tài Khoản Người Dùng ({{ $users->total() }})
            </h1>
        </div>

        <!-- Navigation Tabs -->
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item">
                <i class="fa-solid fa-chart-pie"></i> Bảng điều khiển
            </a>
            <a href="{{ route('admin.products') }}" class="admin-nav-item">
                <i class="fa-solid fa-newspaper"></i> Quản lý bài viết
            </a>
            <a href="{{ route('admin.users') }}" class="admin-nav-item active">
                <i class="fa-solid fa-users-gear"></i> Quản lý tài khoản ({{ $users->total() }})
            </a>
            <a href="{{ route('home') }}" class="admin-nav-item" target="_blank" style="margin-left: auto;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem website
            </a>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form action="{{ route('admin.users') }}" method="GET" class="filter-form">
                <input type="text" 
                       name="search" 
                       class="filter-input" 
                       placeholder="Tìm theo tên, email, SĐT..." 
                       value="{{ request('search') }}">

                <select name="role" class="filter-input" style="min-width: 140px;">
                    <option value="">-- Tất cả vai trò --</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Thành viên</option>
                </select>

                <select name="status" class="filter-input" style="min-width: 140px;">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Đã bị khóa</option>
                </select>

                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 13.5px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                </button>

                @if(request('search') || request('role') || request('status'))
                    <a href="{{ route('admin.users') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 13.5px;">
                        <i class="fa-solid fa-rotate-left"></i> Đặt lại
                    </a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div class="table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Thông tin liên hệ</th>
                        <th>Địa chỉ</th>
                        <th>Số tin đăng</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th style="text-align: right;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td style="font-weight: 700; color: var(--muted);">#{{ $user->id }}</td>
                            <td>
                                <div class="user-avatar-cell">
                                    <div class="user-avatar">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="Avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                            <i class="fa-solid fa-user" style="color: #94a3b8; display: none;"></i>
                                        @else
                                            <i class="fa-solid fa-user" style="color: #94a3b8;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b;">
                                            {{ $user->profile->name ?? 'Chưa đặt tên' }}
                                        </div>
                                        @if($user->id === Auth::id())
                                            <span style="font-size: 11px; color: var(--primary); font-weight: 600;">(Tài khoản của bạn)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="fa-regular fa-envelope" style="color: var(--muted); width: 14px;"></i> {{ $user->email ?? 'Chưa có' }}</div>
                                <div style="color: var(--muted); margin-top: 2px;">
                                    <i class="fa-solid fa-phone" style="color: var(--muted); width: 14px;"></i> {{ $user->sdt ?? 'Chưa có' }}
                                </div>
                            </td>
                            <td style="font-size: 12.5px; color: #475569; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $user->profile->dia_chi ?? 'Chưa cập nhật' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.products', ['search' => $user->email ?? $user->sdt]) }}" style="font-weight: 700; color: var(--primary); text-decoration: none;">
                                    {{ $user->products_count }} tin
                                </a>
                            </td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 700;">
                                        👑 Admin
                                    </span>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #475569;">
                                        Thành viên
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->isBanned())
                                    <span class="badge badge-danger">Đã bị khóa</span>
                                @else
                                    <span class="badge badge-success">Hoạt động</span>
                                @endif
                            </td>
                            <td style="font-size: 12px; color: var(--muted); white-space: nowrap;">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                    @if($user->id !== Auth::id())
                                        <!-- Khóa / Mở khóa -->
                                        <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn {{ $user->isBanned() ? 'MỞ KHÓA' : 'TẠM KHÓA' }} tài khoản này?')">
                                            @csrf
                                            <button type="submit" class="action-btn-sm" style="color: {{ $user->isBanned() ? '#10b981' : '#b45309' }};">
                                                <i class="fa-solid fa-{{ $user->isBanned() ? 'unlock' : 'lock' }}"></i>
                                                {{ $user->isBanned() ? 'Mở' : 'Khóa' }}
                                            </button>
                                        </form>

                                        <!-- Xóa tài khoản -->
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa tài khoản này sẽ xóa toàn bộ dữ liệu liên quan. Bạn có chắc chắn?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn-sm" style="color: #dc2626;" title="Xóa tài khoản">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span style="font-size: 11.5px; color: var(--muted); font-style: italic;">Đang đăng nhập</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: var(--muted);">
                                Không tìm thấy người dùng nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px;">
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection

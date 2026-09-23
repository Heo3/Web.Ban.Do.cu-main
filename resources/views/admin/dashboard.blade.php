@extends('layouts.app')

@section('title', 'Bảng Điều Khiển Quản Trị - Chợ Tốt')

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

    /* Admin Nav */
    .admin-nav {
        display: flex;
        gap: 10px;
        background: #ffffff;
        padding: 8px 12px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        margin-bottom: 28px;
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

    /* Stat Cards */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 20px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-number {
        font-size: 26px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.1;
    }

    .stat-label {
        font-size: 13px;
        color: var(--muted);
        font-weight: 500;
        margin-top: 4px;
    }

    /* Tables Grid */
    .dashboard-tables {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 24px;
    }

    .admin-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        padding: 20px;
        box-shadow: var(--shadow-sm);
    }

    .card-title {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    .admin-table th {
        text-align: left;
        padding: 10px 12px;
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        border-bottom: 1px solid var(--border);
    }

    .admin-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .admin-table tr:hover td {
        background: #f8fafc;
    }

    @media (max-width: 1024px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .dashboard-tables {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .stat-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="container">

        <div class="admin-header">
            <h1 class="admin-title">
                <i class="fa-solid fa-crown" style="color: #f59e0b;"></i>
                Trang Quản Trị Hệ Thống
            </h1>

            <div>
                <span class="badge" style="background: #fef3c7; color: #b45309; padding: 6px 12px; font-size: 13px;">
                    <i class="fa-solid fa-shield-halved"></i> Quyền Quản trị viên: {{ Auth::user()->email }}
                </span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item active">
                <i class="fa-solid fa-chart-pie"></i> Bảng điều khiển
            </a>
            <a href="{{ route('admin.products') }}" class="admin-nav-item">
                <i class="fa-solid fa-newspaper"></i> Quản lý bài viết ({{ $totalProducts }})
            </a>
            <a href="{{ route('admin.users') }}" class="admin-nav-item">
                <i class="fa-solid fa-users-gear"></i> Quản lý tài khoản ({{ $totalUsers }})
            </a>
            <a href="{{ route('home') }}" class="admin-nav-item" target="_blank" style="margin-left: auto;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem website
            </a>
        </div>

        <!-- Stats -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fff4ed; color: var(--primary);">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $totalProducts }}</div>
                    <div class="stat-label">Tổng bài viết</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #ecfdf5; color: #10b981;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $activeProducts }}</div>
                    <div class="stat-label">Bài đang hiển thị</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $totalUsers }}</div>
                    <div class="stat-label">Tổng tài khoản</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fdf2f8; color: #ec4899;">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $soldProducts }}</div>
                    <div class="stat-label">Bài viết đã bán</div>
                </div>
            </div>
        </div>

        <!-- Tables -->
        <div class="dashboard-tables">

            <!-- Recent Products -->
            <div class="admin-card">
                <div class="card-title">
                    <span><i class="fa-solid fa-newspaper" style="color: var(--primary); margin-right: 6px;"></i> Bài viết đăng gần đây</span>
                    <a href="{{ route('admin.products') }}" style="font-size: 13px; color: var(--primary); text-decoration: none;">Xem tất cả &rarr;</a>
                </div>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tin đăng</th>
                            <th>Giá</th>
                            <th>Người đăng</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProducts as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('products.show', $item->id) }}" target="_blank" style="font-weight: 600; color: #1e293b; text-decoration: none;">
                                        {{ Str::limit($item->title, 35) }}
                                    </a>
                                    <div style="font-size: 11.5px; color: var(--muted);">{{ $item->time_ago }}</div>
                                </td>
                                <td style="font-weight: 700; color: #e11d48; white-space: nowrap;">
                                    {{ $item->formatted_price }}
                                </td>
                                <td style="font-size: 12.5px;">
                                    {{ $item->user->profile->name ?? ($item->user->email ?? $item->user->sdt) }}
                                </td>
                                <td>
                                    @if($item->status === 'active')
                                        <span class="badge badge-success">Đang bán</span>
                                    @elseif($item->status === 'sold')
                                        <span class="badge badge-danger">Đã bán</span>
                                    @else
                                        <span class="badge badge-warning">Tạm ẩn</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Recent Users -->
            <div class="admin-card">
                <div class="card-title">
                    <span><i class="fa-solid fa-user-plus" style="color: #3b82f6; margin-right: 6px;"></i> Tài khoản mới đăng ký</span>
                    <a href="{{ route('admin.users') }}" style="font-size: 13px; color: #3b82f6; text-decoration: none;">Xem tất cả &rarr;</a>
                </div>

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tài khoản</th>
                            <th>Liên hệ</th>
                            <th>Vai trò</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $user->profile->name ?? 'Người dùng' }}</div>
                                    <div style="font-size: 12px; color: var(--muted);">Tham gia {{ $user->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td style="font-size: 12.5px;">
                                    <div>{{ $user->email ?? '-' }}</div>
                                    <div style="color: var(--muted);">{{ $user->sdt ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge" style="background: #fef3c7; color: #b45309;">👑 Admin</span>
                                    @else
                                        <span class="badge" style="background: #f1f5f9; color: #475569;">Thành viên</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
@endsection

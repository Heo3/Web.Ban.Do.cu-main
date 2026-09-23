@extends('layouts.app')

@section('title', 'Quản Lý Bài Viết / Tin Đăng - Admin Chợ Tốt')

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

    .products-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }

    .products-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .products-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .products-table tr:hover td {
        background: #fcfcfd;
    }

    .post-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        background: #f1f5f9;
    }

    .post-title-link {
        font-weight: 700;
        color: #1e293b;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 300px;
        line-height: 1.4;
    }

    .post-title-link:hover {
        color: var(--primary);
    }

    .table-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
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
                <i class="fa-solid fa-newspaper" style="color: var(--primary);"></i>
                Quản Lý Bài Viết / Tin Đăng ({{ $products->total() }})
            </h1>

            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-circle-plus"></i> Đăng tin mới
            </a>
        </div>

        <!-- Navigation Tabs -->
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item">
                <i class="fa-solid fa-chart-pie"></i> Bảng điều khiển
            </a>
            <a href="{{ route('admin.products') }}" class="admin-nav-item active">
                <i class="fa-solid fa-newspaper"></i> Quản lý bài viết ({{ $products->total() }})
            </a>
            <a href="{{ route('admin.users') }}" class="admin-nav-item">
                <i class="fa-solid fa-users-gear"></i> Quản lý tài khoản
            </a>
            <a href="{{ route('home') }}" class="admin-nav-item" target="_blank" style="margin-left: auto;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem website
            </a>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form action="{{ route('admin.products') }}" method="GET" class="filter-form">
                <input type="text" 
                       name="search" 
                       class="filter-input" 
                       placeholder="Tìm theo tiêu đề, người đăng..." 
                       value="{{ request('search') }}">

                <select name="status" class="filter-input" style="min-width: 140px;">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hiển thị</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Đã bán</option>
                    <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Tạm ẩn</option>
                </select>

                <select name="category_id" class="filter-input" style="min-width: 160px;">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 13.5px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Lọc
                </button>

                @if(request('search') || request('status') || request('category_id'))
                    <a href="{{ route('admin.products') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 13.5px;">
                        <i class="fa-solid fa-rotate-left"></i> Đặt lại
                    </a>
                @endif
            </form>
        </div>

        <!-- Products Table -->
        <div class="table-container">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Giá bán</th>
                        <th>Danh mục</th>
                        <th>Người đăng</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th style="text-align: right;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td style="font-weight: 700; color: var(--muted);">#{{ $product->id }}</td>
                            <td>
                                <img src="{{ $product->display_image }}" alt="Thumb" class="post-thumb">
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" target="_blank" class="post-title-link">
                                    {{ $product->title }}
                                </a>
                                <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">
                                    <i class="fa-solid fa-location-dot"></i> {{ $product->province ?? 'Toàn quốc' }}
                                    &bull; <i class="fa-solid fa-eye"></i> {{ $product->views_count }} views
                                </div>
                            </td>
                            <td style="font-weight: 800; color: #e11d48; white-space: nowrap;">
                                {{ $product->formatted_price }}
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #475569;">
                                    {{ $product->category->name ?? 'Khác' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $product->user->profile->name ?? 'Người dùng' }}</div>
                                <div style="font-size: 11.5px; color: var(--muted);">{{ $product->phone ?? ($product->user->sdt ?? $product->user->email) }}</div>
                            </td>
                            <td style="font-size: 12px; color: var(--muted); white-space: nowrap;">
                                {{ $product->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                @if($product->status === 'active')
                                    <span class="badge badge-success">🟢 Đang bán</span>
                                @elseif($product->status === 'sold')
                                    <span class="badge badge-danger">🔴 Đã bán</span>
                                @else
                                    <span class="badge badge-warning">⚪ Tạm ẩn</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions" style="justify-content: flex-end;">
                                    <!-- Xem -->
                                    <a href="{{ route('products.show', $product->id) }}" target="_blank" class="action-btn-sm" title="Xem bài">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>

                                    <!-- Đổi trạng thái -->
                                    <form action="{{ route('admin.products.status', $product->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" style="padding: 4px 6px; font-size: 12px; border-radius: 6px; border: 1px solid var(--border); background: #fff; cursor: pointer;">
                                            <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Đang bán</option>
                                            <option value="sold" {{ $product->status === 'sold' ? 'selected' : '' }}>Đã bán</option>
                                            <option value="hidden" {{ $product->status === 'hidden' ? 'selected' : '' }}>Tạm ẩn</option>
                                        </select>
                                    </form>

                                    <!-- Sửa -->
                                    <a href="{{ route('products.edit', $product->id) }}" class="action-btn-sm" title="Sửa bài">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <!-- Xóa -->
                                    <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn bài viết này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn-sm" style="color: #dc2626;" title="Xóa bài">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: var(--muted);">
                                <i class="fa-solid fa-box-open" style="font-size: 36px; margin-bottom: 8px; color: #cbd5e1;"></i>
                                <div>Không tìm thấy bài viết nào phù hợp.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 24px;">
            {{ $products->links() }}
        </div>

    </div>
</div>
@endsection

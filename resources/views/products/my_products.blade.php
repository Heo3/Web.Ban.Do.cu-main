@extends('layouts.app')

@section('title', 'Quản Lý Tin Đăng Của Tôi - Chợ Tốt')

@push('styles')
<style>
    .my-products-page {
        padding: 30px 0 50px;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Status Tabs */
    .status-tabs {
        display: flex;
        gap: 8px;
        background: #ffffff;
        padding: 6px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        width: fit-content;
    }

    .tab-btn {
        padding: 8px 18px;
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        color: var(--muted);
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: var(--primary);
    }

    .tab-btn.active {
        background: var(--primary);
        color: #ffffff;
    }

    /* Product Item Card */
    .my-product-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        padding: 16px;
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 14px;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s;
    }

    .my-product-card:hover {
        box-shadow: var(--shadow-md);
        border-color: #cbd5e1;
    }

    .item-thumbnail {
        width: 110px;
        height: 110px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f1f5f9;
    }

    .item-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-info {
        flex: 1;
        min-width: 0;
    }

    .item-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 6px;
    }

    .item-title:hover {
        color: var(--primary);
    }

    .item-price {
        font-size: 16px;
        font-weight: 800;
        color: #e11d48;
        margin-bottom: 8px;
    }

    .item-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 12.5px;
        color: var(--muted);
        flex-wrap: wrap;
    }

    .item-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .my-product-card {
            flex-direction: column;
            align-items: flex-start;
        }
        .item-thumbnail {
            width: 100%;
            height: 180px;
        }
        .item-actions {
            flex-direction: row;
            width: 100%;
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<div class="my-products-page">
    <div class="container">

        <div class="page-header">
            <h1 class="page-title">
                <i class="fa-solid fa-boxes-stacked" style="color: var(--primary);"></i>
                Quản lý tin đăng của tôi
            </h1>

            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-circle-plus"></i> ĐĂNG TIN MỚI
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="status-tabs">
            <a href="{{ route('products.my', ['status' => 'all']) }}" class="tab-btn {{ $status === 'all' ? 'active' : '' }}">
                Tất cả tin ({{ Auth::user()->products()->count() }})
            </a>
            <a href="{{ route('products.my', ['status' => 'active']) }}" class="tab-btn {{ $status === 'active' ? 'active' : '' }}">
                Đang hiển thị ({{ Auth::user()->products()->where('status', 'active')->count() }})
            </a>
            <a href="{{ route('products.my', ['status' => 'sold']) }}" class="tab-btn {{ $status === 'sold' ? 'active' : '' }}">
                Đã bán ({{ Auth::user()->products()->where('status', 'sold')->count() }})
            </a>
        </div>

        <!-- List -->
        @if($products->count() > 0)
            <div class="product-list">
                @foreach($products as $product)
                    <div class="my-product-card">
                        <div class="item-thumbnail">
                            <img src="{{ $product->display_image }}" alt="{{ $product->title }}">
                        </div>

                        <div class="item-info">
                            <div style="margin-bottom: 6px;">
                                @if($product->status === 'active')
                                    <span class="badge badge-success">🟢 Đang hiển thị</span>
                                @elseif($product->status === 'sold')
                                    <span class="badge badge-danger">🔴 Đã bán</span>
                                @else
                                    <span class="badge badge-warning">⚪ Tạm ẩn</span>
                                @endif
                                
                                @if($product->category)
                                    <span class="badge" style="background: #f1f5f9; color: #475569;">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product->id) }}" class="item-title">
                                {{ $product->title }}
                            </a>

                            <div class="item-price">
                                {{ $product->formatted_price }}
                            </div>

                            <div class="item-meta">
                                <span><i class="fa-regular fa-clock"></i> Đăng {{ $product->time_ago }}</span>
                                <span><i class="fa-regular fa-eye"></i> {{ $product->views_count }} lượt xem</span>
                                <span><i class="fa-solid fa-location-dot"></i> {{ $product->province ?? 'Toàn quốc' }}</span>
                            </div>
                        </div>

                        <div class="item-actions">
                            <form action="{{ route('products.toggleStatus', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; width: 100%;">
                                    <i class="fa-solid fa-rotate"></i>
                                    {{ $product->status === 'active' ? 'Đã bán' : 'Bán lại' }}
                                </button>
                            </form>

                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; width: 100%;">
                                <i class="fa-solid fa-pen-to-square"></i> Sửa tin
                            </a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin này vĩnh viễn?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; width: 100%; color: #dc2626;">
                                    <i class="fa-solid fa-trash-can"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 24px;">
                {{ $products->links() }}
            </div>
        @else
            <div style="background: #fff; padding: 50px 20px; text-align: center; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-solid fa-box-open" style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                <h3 style="margin-bottom: 6px;">Chưa có tin đăng nào</h3>
                <p style="color: var(--muted); margin-bottom: 18px;">Bạn chưa đăng tin bán món đồ nào trong mục này.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-circle-plus"></i> Đăng tin bán ngay
                </a>
            </div>
        @endif

    </div>
</div>
@endsection

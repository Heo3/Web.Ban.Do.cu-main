@extends('layouts.app')

@section('title', 'Chợ Tốt - Mua Bán Đồ Cũ, Rao Vặt Trực Tuyến Hàng Đầu')

@push('styles')
<style>
    /* Hero Banner Section */
    .hero-banner {
        background: linear-gradient(135deg, #fff4ec 0%, #fffbf7 100%);
        border-bottom: 1px solid #f1e4d8;
        padding: 24px 0 28px;
        margin-bottom: 24px;
    }

    .hero-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
        align-items: center;
    }

    .hero-content h1 {
        font-size: 28px;
        font-weight: 800;
        color: #1f2933;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .hero-content h1 span {
        color: var(--primary);
    }

    .hero-content p {
        color: var(--muted);
        font-size: 15px;
        margin-bottom: 20px;
    }

    .hero-highlights {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    .hero-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        padding: 8px 16px;
        border-radius: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #f0e6dd;
        font-size: 13px;
        font-weight: 600;
    }

    .hero-item i {
        color: var(--primary);
        font-size: 16px;
    }

    .hero-action-card {
        background: linear-gradient(135deg, #f26a3d 0%, #d85429 100%);
        color: #ffffff;
        padding: 24px;
        border-radius: 18px;
        box-shadow: 0 12px 28px rgba(242, 106, 61, 0.25);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .hero-action-card h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .hero-action-card p {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 16px;
    }

    .hero-post-btn {
        background: #ffffff;
        color: var(--primary-hover);
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hero-post-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    /* Content Area */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        background: #ffffff;
        padding: 16px 20px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }

    .filter-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-light);
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .filter-tag a {
        color: var(--primary);
        text-decoration: none;
        font-size: 14px;
        line-height: 1;
    }

    .sort-box {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sort-box label {
        font-size: 14px;
        color: var(--muted);
        font-weight: 500;
    }

    .sort-select {
        padding: 8px 14px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: #ffffff;
        font-size: 14px;
        font-weight: 500;
        color: var(--dark);
        outline: none;
        cursor: pointer;
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .product-card {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid #e9edf2;
        overflow: hidden;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        text-decoration: none;
        color: inherit;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        border-color: #d1d9e2;
    }

    .product-thumbnail {
        position: relative;
        width: 100%;
        padding-top: 75%; /* 4:3 Aspect Ratio */
        background: #f1f5f9;
        overflow: hidden;
    }

    .product-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .product-card:hover .product-thumbnail img {
        transform: scale(1.05);
    }

    .card-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        z-index: 2;
    }

    .badge-sold {
        background: rgba(220, 38, 38, 0.9);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        backdrop-filter: blur(4px);
    }

    .badge-category {
        background: rgba(31, 41, 51, 0.75);
        color: #ffffff;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 8px;
        border-radius: 4px;
        backdrop-filter: blur(4px);
    }

    .fav-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 16px;
        cursor: pointer;
        z-index: 2;
        transition: all 0.2s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .fav-btn:hover {
        background: #ffffff;
        transform: scale(1.15);
    }

    .fav-btn.active,
    .fav-btn i.fa-solid {
        color: #ef4444;
    }

    .product-details {
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .product-title {
        font-size: 14.5px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.4;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }

    .product-card:hover .product-title {
        color: var(--primary);
    }

    .product-price {
        font-size: 17px;
        font-weight: 800;
        color: #e11d48;
        margin-bottom: 12px;
    }

    .product-meta {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        color: #64748b;
        padding-top: 10px;
        border-top: 1px dashed #f1f5f9;
    }

    .product-location {
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 60%;
    }

    .product-time {
        white-space: nowrap;
    }

    /* Empty state */
    .empty-state {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 60px 20px;
        text-align: center;
        border: 1px dashed #cbd5e1;
        margin-bottom: 32px;
    }

    .empty-state i {
        font-size: 56px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 20px;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--muted);
        font-size: 14px;
        margin-bottom: 20px;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin: 24px 0 40px;
    }

    @media (max-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .hero-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }
        .filter-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        .sort-box {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

    <!-- Hero Banner -->
    @if(!request('search') && !request('category') && !request('province'))
    <section class="hero-banner">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <h1>Khám phá & Rao vặt <span>Đồ Cũ Giá Tốt</span></h1>
                    <p>Hàng ngàn tin đăng bán điện thoại, xe máy, điện tử, đồ gia dụng giá rẻ uy tín mỗi ngày.</p>
                    
                    <div class="hero-highlights">
                        <div class="hero-item">
                            <i class="fa-solid fa-shield-check"></i>
                            <span>Giao dịch an toàn</span>
                        </div>
                        <div class="hero-item">
                            <i class="fa-solid fa-bolt"></i>
                            <span>Đăng tin siêu tốc</span>
                        </div>
                        <div class="hero-item">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                            <span>Tiết kiệm 50% - 70%</span>
                        </div>
                    </div>
                </div>

                <div class="hero-action-card">
                    <div>
                        <h3>Bạn có đồ cũ cần bán?</h3>
                        <p>Đăng tin miễn phí tiếp cận ngay hàng triệu người mua tiềm năng trên toàn quốc!</p>
                    </div>
                    <a href="{{ route('products.create') }}" class="hero-post-btn">
                        <i class="fa-solid fa-circle-plus"></i> ĐĂNG BÁN NGAY
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <div class="container" style="margin-top: 24px;">

        <!-- Filter & Header Bar -->
        <div class="filter-bar">
            <div class="filter-title">
                <i class="fa-solid fa-bag-shopping" style="color: var(--primary);"></i>
                @if($selectedCategory)
                    <span>Danh mục: {{ $selectedCategory->name }}</span>
                @elseif(request('search'))
                    <span>Kết quả cho: "{{ request('search') }}"</span>
                @else
                    <span>Tin Đăng Mới Nhất</span>
                @endif
                <span style="font-size: 13px; font-weight: 500; color: var(--muted);">({{ $products->total() }} tin)</span>
            </div>

            <div class="active-filters">
                @if($selectedCategory)
                    <div class="filter-tag">
                        <span>{{ $selectedCategory->name }}</span>
                        <a href="{{ route('home', array_merge(request()->except('category', 'page')) ) }}" title="Xóa lọc danh mục">&times;</a>
                    </div>
                @endif

                @if(request('province'))
                    <div class="filter-tag">
                        <i class="fa-solid fa-location-dot" style="font-size: 11px;"></i>
                        <span>{{ request('province') }}</span>
                        <a href="{{ route('home', array_merge(request()->except('province', 'page')) ) }}" title="Xóa lọc tỉnh thành">&times;</a>
                    </div>
                @endif

                @if(request('search'))
                    <div class="filter-tag">
                        <span>"{{ request('search') }}"</span>
                        <a href="{{ route('home', array_merge(request()->except('search', 'page')) ) }}" title="Xóa tìm kiếm">&times;</a>
                    </div>
                @endif

                @if($selectedCategory || request('province') || request('search'))
                    <a href="{{ route('home') }}" class="btn btn-outline" style="padding: 4px 10px; font-size: 12px; border-radius: 20px;">
                        <i class="fa-solid fa-rotate-left"></i> Xóa tất cả
                    </a>
                @endif
            </div>

            <!-- Sort Dropdown -->
            <form action="{{ route('home') }}" method="GET" class="sort-box" id="sortForm">
                @foreach(request()->except('sort', 'page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach

                <label for="sortSelect"><i class="fa-solid fa-arrow-down-wide-short"></i> Sắp xếp:</label>
                <select name="sort" id="sortSelect" class="sort-select" onchange="document.getElementById('sortForm').submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                    <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Xem nhiều nhất</option>
                </select>
            </form>
        </div>

        <!-- Product Grid -->
        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', $product->id) }}" class="product-thumbnail">
                            <img src="{{ $product->display_image }}" alt="{{ $product->title }}" loading="lazy">
                            
                            <div class="card-badges">
                                @if($product->status === 'sold')
                                    <span class="badge-sold">ĐÃ BÁN</span>
                                @endif
                                @if($product->category)
                                    <span class="badge-category">{{ $product->category->name }}</span>
                                @endif
                            </div>
                        </a>

                        <!-- Favorite button -->
                        @php
                            $isFav = Auth::check() && $product->isFavoritedBy(Auth::user());
                        @endphp
                        <button type="button" 
                                class="fav-btn {{ $isFav ? 'active' : '' }}" 
                                title="{{ $isFav ? 'Bỏ lưu tin' : 'Lưu tin này' }}"
                                onclick="toggleFavorite({{ $product->id }}, this); return false;">
                            <i class="{{ $isFav ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                        </button>

                        <a href="{{ route('products.show', $product->id) }}" class="product-details">
                            <h3 class="product-title" title="{{ $product->title }}">
                                {{ $product->title }}
                            </h3>

                            <div class="product-price">
                                {{ $product->formatted_price }}
                            </div>

                            <div class="product-meta">
                                <span class="product-location" title="{{ $product->address ?? $product->province }}">
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ $product->province ?? 'Toàn quốc' }}
                                </span>
                                <span class="product-time">
                                    {{ $product->time_ago }}
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Phân trang -->
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty state -->
            <div class="empty-state">
                <i class="fa-solid fa-magnifying-glass"></i>
                <h3>Không tìm thấy tin đăng phù hợp</h3>
                <p>Thử tìm kiếm với từ khóa khác hoặc chọn khu vực tỉnh thành khác xem sao nhé!</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fa-solid fa-house"></i> Quay lại trang chủ
                </a>
            </div>
        @endif

    </div>

@endsection

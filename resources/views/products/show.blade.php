@extends('layouts.app')

@section('title', $product->title . ' - Chợ Tốt')
@section('meta_description', Str::limit(strip_tags($product->description), 160))

@push('styles')
<style>
    .product-detail-page {
        padding: 24px 0 40px;
    }

    /* Breadcrumbs */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .breadcrumb a {
        color: var(--muted);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        color: var(--primary);
    }

    .breadcrumb-current {
        color: var(--dark);
        font-weight: 600;
        max-width: 400px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Layout Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: 1.9fr 1.1fr;
        gap: 28px;
        align-items: start;
    }

    /* Gallery */
    .gallery-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .main-image-wrapper {
        position: relative;
        width: 100%;
        height: 460px;
        background: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .status-ribbon {
        position: absolute;
        top: 16px;
        left: 16px;
        background: #dc2626;
        color: #fff;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.4);
    }

    .thumbnail-row {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: #f8fafc;
        overflow-x: auto;
        border-top: 1px solid var(--border);
    }

    .thumb-item {
        width: 76px;
        height: 76px;
        border-radius: 8px;
        border: 2px solid transparent;
        overflow: hidden;
        cursor: pointer;
        flex-shrink: 0;
        opacity: 0.7;
        transition: all 0.2s ease;
    }

    .thumb-item:hover,
    .thumb-item.active {
        opacity: 1;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(242, 106, 61, 0.2);
    }

    .thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Details Card */
    .content-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .detail-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.35;
        margin-bottom: 12px;
    }

    .price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-price {
        font-size: 26px;
        font-weight: 800;
        color: #e11d48;
    }

    .action-icons {
        display: flex;
        gap: 10px;
    }

    .icon-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: #ffffff;
        color: var(--dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .icon-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .icon-btn.active,
    .icon-btn.favorited i {
        color: #ef4444;
    }

    .detail-meta-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        background: #f8fafc;
        padding: 16px;
        border-radius: var(--radius-md);
        margin-bottom: 24px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        color: #475569;
    }

    .meta-item i {
        color: var(--primary);
        font-size: 16px;
        width: 20px;
    }

    .section-heading {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .description-text {
        font-size: 15px;
        line-height: 1.7;
        color: #334155;
        white-space: pre-line;
    }

    /* Safety Notice */
    .safety-card {
        background: #fffdf5;
        border: 1px solid #fef08a;
        border-radius: var(--radius-md);
        padding: 16px;
        margin-top: 24px;
        display: flex;
        gap: 14px;
    }

    .safety-icon {
        color: #ca8a04;
        font-size: 24px;
        flex-shrink: 0;
    }

    .safety-content h4 {
        font-size: 14px;
        font-weight: 700;
        color: #854d0e;
        margin-bottom: 4px;
    }

    .safety-content ul {
        margin: 0;
        padding-left: 18px;
        font-size: 13px;
        color: #713f12;
    }

    /* Seller Sidebar */
    .seller-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 20px;
    }

    .seller-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    .seller-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }

    .seller-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .seller-info h3 {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .seller-info p {
        font-size: 12.5px;
        color: var(--muted);
    }

    .phone-box {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        padding: 16px;
        border-radius: var(--radius-md);
        margin-bottom: 16px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.25);
    }

    .phone-number {
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
        color: #ffffff;
        text-decoration: none;
    }

    .phone-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .phone-btn {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .phone-btn:hover {
        background: #ffffff;
        color: #059669;
    }

    .btn-chat-seller {
        width: 100%;
        background: linear-gradient(135deg, #f26a3d 0%, #ea580c 100%);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        padding: 14px 18px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 14px;
        box-shadow: 0 6px 16px rgba(242, 106, 61, 0.28);
        transition: all 0.2s ease;
    }

    .btn-chat-seller:hover {
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(242, 106, 61, 0.38);
    }

    .seller-address-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        background: #f8fafc;
        border-radius: var(--radius-md);
        font-size: 13.5px;
        color: #475569;
        margin-bottom: 20px;
    }

    .seller-address-box i {
        color: var(--primary);
        font-size: 16px;
        margin-top: 2px;
    }

    /* Owner Controls */
    .owner-panel {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: var(--radius-md);
        padding: 16px;
        margin-bottom: 20px;
    }

    .owner-title {
        font-size: 14px;
        font-weight: 700;
        color: #9a3412;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .owner-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Related Products */
    .related-card {
        margin-top: 32px;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        .main-image-wrapper {
            height: 350px;
        }
        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="product-detail-page">
    <div class="container">

        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
            @if($product->category)
                <a href="{{ route('home', ['category' => $product->category->slug]) }}">
                    {{ $product->category->name }}
                </a>
                <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
            @endif
            <span class="breadcrumb-current">{{ $product->title }}</span>
        </nav>

        <div class="detail-grid">

            <!-- Left column: Gallery & Description -->
            <div class="detail-left">

                <!-- Image Gallery -->
                <div class="gallery-card">
                    <div class="main-image-wrapper">
                        @if($product->status === 'sold')
                            <div class="status-ribbon">ĐÃ BÁN</div>
                        @endif

                        <img id="mainImage" src="{{ $product->display_image }}" alt="{{ $product->title }}">
                    </div>

                    <!-- Thumbnails -->
                    @if($product->images->count() > 0 || $product->image)
                        <div class="thumbnail-row">
                            @if($product->image)
                                <div class="thumb-item active" onclick="switchImage('{{ $product->display_image }}', this)">
                                    <img src="{{ $product->display_image }}" alt="Thumbnail">
                                </div>
                            @endif

                            @foreach($product->images as $img)
                                <div class="thumb-item" onclick="switchImage('{{ $img->url }}', this)">
                                    <img src="{{ $img->url }}" alt="Thumbnail">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Content -->
                <div class="content-card">
                    <h1 class="detail-title">{{ $product->title }}</h1>

                    <div class="price-row">
                        <div class="detail-price">
                            {{ $product->formatted_price }}
                        </div>

                        <div class="action-icons">
                            <button type="button" 
                                    class="icon-btn {{ $isFavorited ? 'favorited' : '' }}" 
                                    title="Lưu tin yêu thích"
                                    onclick="toggleFavorite({{ $product->id }}, this)">
                                <i class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                            </button>

                            <button type="button" class="icon-btn" title="Chia sẻ tin" onclick="copyShareUrl()">
                                <i class="fa-solid fa-share-nodes"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Meta specs -->
                    <div class="detail-meta-list">
                        <div class="meta-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Khu vực: <strong>{{ $product->province ?? 'Toàn quốc' }}</strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-clock"></i>
                            <span>Đăng lúc: <strong>{{ $product->time_ago }}</strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-tags"></i>
                            <span>Danh mục: <strong>{{ $product->category->name ?? 'Khác' }}</strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-eye"></i>
                            <span>Lượt xem: <strong>{{ $product->views_count }}</strong></span>
                        </div>
                    </div>

                    <!-- Description -->
                    <h3 class="section-heading">
                        <i class="fa-solid fa-align-left" style="color: var(--primary);"></i>
                        Mô tả chi tiết
                    </h3>
                    <div class="description-text">{!! nl2br(e($product->description)) !!}</div>

                    <!-- Safety Notice -->
                    <div class="safety-card">
                        <i class="fa-solid fa-triangle-exclamation safety-icon"></i>
                        <div class="safety-content">
                            <h4>Mẹo giao dịch an toàn trên Chợ Tốt</h4>
                            <ul>
                                <li>KHÔNG chuyển tiền đặt cọc trước khi gặp mặt xem hàng trực tiếp.</li>
                                <li>Kiểm tra cẩn thận tình trạng món đồ, giấy tờ chứng từ (nếu có).</li>
                                <li>Nên hẹn gặp tại nơi công cộng, đông người qua lại vào ban ngày.</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Right column: Seller info & Actions -->
            <div class="detail-right">

                <!-- Nếu là người đăng tin (Chủ sở hữu) -->
                @if(Auth::check() && Auth::id() === $product->user_id)
                    <div class="owner-panel">
                        <div class="owner-title">
                            <i class="fa-solid fa-user-gear"></i>
                            Quản lý tin đăng của bạn
                        </div>
                        <div class="owner-actions">
                            <form action="{{ route('products.toggleStatus', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="width: 100%;">
                                    <i class="fa-solid fa-rotate"></i>
                                    {{ $product->status === 'active' ? 'Đánh dấu ĐÃ BÁN' : 'Mở bán lại (ĐANG BÁN)' }}
                                </button>
                            </form>

                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary" style="width: 100%;">
                                <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa tin
                            </a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin đăng này vĩnh viễn?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="width: 100%; color: #dc2626; border-color: #fca5a5;">
                                    <i class="fa-solid fa-trash-can"></i> Xóa tin đăng này
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Seller Info -->
                <div class="seller-card">
                    <div class="seller-header">
                        <div class="seller-avatar">
                            @if($product->user && $product->user->avatar_url)
                                <img src="{{ $product->user->avatar_url }}" alt="Seller" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                <i class="fa-solid fa-user" style="color: #64748b; display: none;"></i>
                            @else
                                <i class="fa-solid fa-user" style="color: #64748b;"></i>
                            @endif
                        </div>

                        <div class="seller-info">
                            <h3>{{ $product->user->profile->name ?? 'Người bán Chợ Tốt' }}</h3>
                            <p><i class="fa-solid fa-calendar-days"></i> Đã tham gia {{ $product->user->created_at->format('m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Chat với người bán Button -->
                    @if(!Auth::check() || Auth::id() !== $product->user_id)
                        <button type="button" class="btn-chat-seller" onclick="startChatWithSeller()">
                            <i class="fa-solid fa-comments"></i> Chat với người bán
                        </button>
                    @endif

                    <!-- Phone Action Box -->
                    <div class="phone-box">
                        <a href="tel:{{ $product->phone }}" class="phone-number" id="phoneText">
                            <i class="fa-solid fa-phone-volume"></i> {{ $product->phone }}
                        </a>
                        <div class="phone-actions">
                            <a href="tel:{{ $product->phone }}" class="phone-btn">
                                <i class="fa-solid fa-phone"></i> Gọi điện ngay
                            </a>
                            <button type="button" class="phone-btn" onclick="copyPhone('{{ $product->phone }}')">
                                <i class="fa-regular fa-copy"></i> Sao chép số
                            </button>
                        </div>
                    </div>

                    <!-- Address Box -->
                    @if($product->address || $product->province)
                        <div class="seller-address-box">
                            <i class="fa-solid fa-map-location-dot"></i>
                            <div>
                                <strong>Địa chỉ giao dịch:</strong><br>
                                {{ $product->address ? $product->address . ', ' : '' }}{{ $product->province }}
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('home', ['search' => $product->user->profile->name ?? '']) }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fa-solid fa-store"></i> Xem thêm các tin khác
                    </a>
                </div>

            </div>

        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="content-card related-card">
                <h3 class="section-heading">
                    <i class="fa-solid fa-fire" style="color: #e11d48;"></i>
                    Tin đăng tương tự cùng chuyên mục
                </h3>

                <div class="related-grid">
                    @foreach($relatedProducts as $related)
                        <div class="product-card">
                            <a href="{{ route('products.show', $related->id) }}" class="product-thumbnail">
                                <img src="{{ $related->display_image }}" alt="{{ $related->title }}" loading="lazy">
                            </a>

                            <a href="{{ route('products.show', $related->id) }}" class="product-details">
                                <h4 class="product-title" title="{{ $related->title }}">{{ $related->title }}</h4>
                                <div class="product-price">{{ $related->formatted_price }}</div>
                                <div class="product-meta">
                                    <span class="product-location"><i class="fa-solid fa-location-dot"></i> {{ $related->province }}</span>
                                    <span class="product-time">{{ $related->time_ago }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    function switchImage(src, element) {
        const mainImg = document.getElementById('mainImage');
        if (mainImg) {
            mainImg.src = src;
        }
        document.querySelectorAll('.thumb-item').forEach(el => el.classList.remove('active'));
        if (element) {
            element.classList.add('active');
        }
    }

    function copyPhone(phone) {
        navigator.clipboard.writeText(phone).then(() => {
            showToast('Đã sao chép số điện thoại: ' + phone, 'success');
        });
    }

    function copyShareUrl() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('Đã sao chép liên kết tin đăng!', 'success');
        });
    }

    function startChatWithSeller() {
        @if(!Auth::check())
            if (typeof openAuthModal === 'function') {
                openAuthModal('login');
            } else {
                window.location.href = "{{ route('login') }}";
            }
        @else
            window.location.href = "{{ route('chat.index', ['product_id' => $product->id]) }}";
        @endif
    }
</script>
@endsection

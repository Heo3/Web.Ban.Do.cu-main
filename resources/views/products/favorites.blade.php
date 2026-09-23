@extends('layouts.app')

@section('title', 'Tin Đăng Đã Lưu Yêu Thích - Chợ Tốt')

@push('styles')
<style>
    .favorites-page {
        padding: 30px 0 50px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
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
    }

    .product-thumbnail {
        position: relative;
        width: 100%;
        padding-top: 75%;
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
    }

    .unfav-btn {
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
        color: #ef4444;
        font-size: 16px;
        cursor: pointer;
        z-index: 2;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    .unfav-btn:hover {
        background: #ef4444;
        color: #ffffff;
        transform: scale(1.1);
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

    @media (max-width: 1024px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
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
<div class="favorites-page">
    <div class="container">

        <h1 class="page-title">
            <i class="fa-solid fa-heart" style="color: #ef4444;"></i>
            Tin đăng đã lưu yêu thích ({{ $products->total() }})
        </h1>

        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card" id="fav-card-{{ $product->id }}">
                        <a href="{{ route('products.show', $product->id) }}" class="product-thumbnail">
                            <img src="{{ $product->display_image }}" alt="{{ $product->title }}">
                        </a>

                        <form action="{{ route('products.favorite', $product->id) }}" method="POST" onsubmit="removeFavCard(event, {{ $product->id }})">
                            @csrf
                            <button type="submit" class="unfav-btn" title="Bỏ lưu tin">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </form>

                        <a href="{{ route('products.show', $product->id) }}" class="product-details">
                            <h3 class="product-title" title="{{ $product->title }}">
                                {{ $product->title }}
                            </h3>

                            <div class="product-price">
                                {{ $product->formatted_price }}
                            </div>

                            <div class="product-meta">
                                <span><i class="fa-solid fa-location-dot"></i> {{ $product->province ?? 'Toàn quốc' }}</span>
                                <span>{{ $product->time_ago }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 32px;">
                {{ $products->links() }}
            </div>
        @else
            <div style="background: #fff; padding: 60px 20px; text-align: center; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-regular fa-heart" style="font-size: 56px; color: #cbd5e1; margin-bottom: 14px;"></i>
                <h3 style="margin-bottom: 8px;">Chưa có tin yêu thích nào</h3>
                <p style="color: var(--muted); margin-bottom: 20px;">Bấm vào biểu tượng trái tim ở các tin đăng để lưu lại xem sau nhé!</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fa-solid fa-compass"></i> Khám phá tin đăng
                </a>
            </div>
        @endif

    </div>
</div>

<script>
    async function removeFavCard(e, id) {
        e.preventDefault();
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`/san-pham/${id}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                const card = document.getElementById(`fav-card-${id}`);
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    card.style.transition = 'all 0.3s ease';
                    setTimeout(() => card.remove(), 300);
                }
                showToast(data.message, 'success');
            }
        } catch (err) {
            console.error(err);
        }
    }
</script>
@endsection

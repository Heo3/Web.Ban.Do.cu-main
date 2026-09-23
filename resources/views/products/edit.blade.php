@extends('layouts.app')

@section('title', 'Chỉnh Sửa Tin Đăng - Chợ Tốt')

@push('styles')
<style>
    .post-page {
        padding: 30px 0 50px;
    }

    .form-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        max-width: 800px;
        margin: 0 auto;
        padding: 32px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        font-size: 15px;
        color: var(--dark);
        background: #fffdfb;
        border: 1px solid #d1d5db;
        border-radius: var(--radius-md);
        outline: none;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(242, 106, 61, 0.15);
        background: #ffffff;
    }

    .existing-images {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .existing-img-box {
        width: 90px;
        height: 90px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
    }

    .existing-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: var(--radius-md);
        padding: 24px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .upload-zone:hover {
        border-color: var(--primary);
        background: #fff8f5;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .submit-btn {
        width: 100%;
        padding: 14px 20px;
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        background: var(--primary);
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        box-shadow: 0 6px 18px rgba(242, 106, 61, 0.3);
        transition: all 0.2s ease;
    }

    .submit-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="post-page">
    <div class="container">
        <div class="form-card">
            <h1 class="page-title">
                <i class="fa-solid fa-pen-to-square" style="color: var(--primary);"></i>
                Chỉnh sửa tin đăng
            </h1>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Trạng thái tin -->
                <div class="form-group">
                    <label class="form-label" for="statusSelect">Trạng thái tin</label>
                    <select name="status" id="statusSelect" class="form-control">
                        <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>🟢 Đang bán</option>
                        <option value="sold" {{ $product->status === 'sold' ? 'selected' : '' }}>🔴 Đã bán</option>
                        <option value="hidden" {{ $product->status === 'hidden' ? 'selected' : '' }}>⚪ Tạm ẩn</option>
                    </select>
                </div>

                <!-- Danh mục -->
                <div class="form-group">
                    <label class="form-label" for="categorySelect">Danh mục sản phẩm</label>
                    <select name="category_id" id="categorySelect" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tiêu đề -->
                <div class="form-group">
                    <label class="form-label" for="titleInput">Tiêu đề tin đăng</label>
                    <input type="text" 
                           name="title" 
                           id="titleInput" 
                           class="form-control" 
                           value="{{ old('title', $product->title) }}" 
                           required>
                </div>

                <!-- Giá bán -->
                <div class="form-group">
                    <label class="form-label" for="priceInput">Giá bán (VNĐ)</label>
                    <input type="number" 
                           name="price" 
                           id="priceInput" 
                           class="form-control" 
                           value="{{ old('price', $product->price) }}" 
                           min="0" 
                           required>
                </div>

                <!-- Hình ảnh hiện có -->
                <div class="form-group">
                    <label class="form-label">Hình ảnh hiện tại</label>
                    <div class="existing-images">
                        @if($product->image)
                            <div class="existing-img-box">
                                <img src="{{ $product->display_image }}" alt="Existing">
                            </div>
                        @endif
                        @foreach($product->images as $img)
                            <div class="existing-img-box">
                                <img src="{{ $img->url }}" alt="Existing">
                            </div>
                        @endforeach
                    </div>

                    <!-- Tải thêm ảnh -->
                    <label class="form-label" style="margin-top: 12px;">Tải thêm hình ảnh mới</label>
                    <div class="upload-zone" onclick="document.getElementById('editImageInput').click()">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 32px; color: var(--primary); margin-bottom: 8px;"></i>
                        <div>Bấm vào đây để tải thêm ảnh mới (nếu cần)</div>
                        <input type="file" 
                               name="images[]" 
                               id="editImageInput" 
                               style="display: none;" 
                               multiple 
                               accept="image/*">
                    </div>
                </div>

                <!-- Mô tả -->
                <div class="form-group">
                    <label class="form-label" for="descriptionInput">Mô tả chi tiết</label>
                    <textarea name="description" 
                              id="descriptionInput" 
                              class="form-control" 
                              rows="6" 
                              required>{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Khu vực & Địa chỉ -->
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="editProvinceSelect">Tỉnh / Thành phố</label>
                        <select name="province" id="editProvinceSelect" class="form-control" required>
                            <option value="{{ $product->province }}" selected>{{ $product->province }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="addressInput">Địa chỉ chi tiết</label>
                        <input type="text" 
                               name="address" 
                               id="addressInput" 
                               class="form-control" 
                               value="{{ old('address', $product->address) }}">
                    </div>
                </div>

                <!-- Số điện thoại -->
                <div class="form-group">
                    <label class="form-label" for="phoneInput">Số điện thoại liên hệ</label>
                    <input type="text" 
                           name="phone" 
                           id="phoneInput" 
                           class="form-control" 
                           value="{{ old('phone', $product->phone) }}" 
                           required>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-save"></i> LƯU THAY ĐỔI
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    async function loadEditProvinces() {
        const select = document.getElementById('editProvinceSelect');
        const current = @json($product->province);

        try {
            const res = await fetch('https://provinces.open-api.vn/api/p/');
            const list = await res.json();
            list.sort((a, b) => a.name.localeCompare(b.name, 'vi'));

            select.innerHTML = '';
            list.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                if (current && (current === p.name || current.includes(p.name))) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
        } catch (e) {
            console.warn(e);
        }
    }

    document.addEventListener('DOMContentLoaded', loadEditProvinces);
</script>
@endsection

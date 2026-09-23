@extends('layouts.app')

@section('title', 'Đăng Tin Bán Đồ Cũ - Chợ Tốt')

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

    .page-subtitle {
        color: var(--muted);
        font-size: 14px;
        margin-bottom: 28px;
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

    .form-label span.req {
        color: #dc2626;
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

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    /* Price Presets */
    .price-presets {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .preset-chip {
        padding: 6px 12px;
        border-radius: 20px;
        background: #f1f5f9;
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }

    .preset-chip:hover {
        background: var(--primary-light);
        color: var(--primary);
        border-color: #fbcfe8;
    }

    /* Image Upload Drag & Drop */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: var(--radius-md);
        padding: 30px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .upload-zone:hover {
        border-color: var(--primary);
        background: #fff8f5;
    }

    .upload-icon {
        font-size: 40px;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .upload-text {
        font-size: 14px;
        color: #475569;
        font-weight: 600;
    }

    .upload-hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: 4px;
    }

    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
    }

    .preview-box {
        position: relative;
        width: 90px;
        height: 90px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
    }

    .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .submit-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .form-card {
            padding: 20px;
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="post-page">
    <div class="container">
        <div class="form-card">
            <h1 class="page-title">
                <i class="fa-solid fa-camera" style="color: var(--primary);"></i>
                Đăng tin bán đồ cũ
            </h1>
            <p class="page-subtitle">Điền đầy đủ thông tin để món đồ của bạn tiếp cận người mua nhanh nhất nhé!</p>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Danh mục -->
                <div class="form-group">
                    <label class="form-label" for="categorySelect">
                        Danh mục sản phẩm <span class="req">*</span>
                    </label>
                    <select name="category_id" id="categorySelect" class="form-control" required>
                        <option value="">-- Chọn danh mục phù hợp --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tiêu đề -->
                <div class="form-group">
                    <label class="form-label" for="titleInput">
                        Tiêu đề tin đăng <span class="req">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="titleInput" 
                           class="form-control" 
                           placeholder="VD: iPhone 13 Pro Max 128GB pin 88% máy đẹp 99%"
                           value="{{ old('title') }}" 
                           required 
                           maxlength="255">
                </div>

                <!-- Giá bán -->
                <div class="form-group">
                    <label class="form-label" for="priceInput">
                        Giá bán (VNĐ) <span class="req">*</span>
                    </label>
                    <input type="number" 
                           name="price" 
                           id="priceInput" 
                           class="form-control" 
                           placeholder="VD: 5000000 (Nhập 0 nếu cho tặng miễn phí)"
                           value="{{ old('price', '') }}" 
                           min="0" 
                           step="1000" 
                           required>

                    <div class="price-presets">
                        <span class="preset-chip" onclick="setPrice(0)">Cho tặng (0đ)</span>
                        <span class="preset-chip" onclick="setPrice(100000)">100.000 đ</span>
                        <span class="preset-chip" onclick="setPrice(500000)">500.000 đ</span>
                        <span class="preset-chip" onclick="setPrice(1000000)">1.000.000 đ</span>
                        <span class="preset-chip" onclick="setPrice(5000000)">5.000.000 đ</span>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="form-group">
                    <label class="form-label">
                        Hình ảnh sản phẩm (Tải lên 1 hoặc nhiều ảnh)
                    </label>
                    <div class="upload-zone" onclick="document.getElementById('imageInput').click()">
                        <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                        <div class="upload-text">Bấm vào đây để chọn ảnh chụp sản phẩm</div>
                        <div class="upload-hint">Định dạng JPG, PNG, WEBP. Ảnh rõ nét sẽ bán nhanh hơn gấp 3 lần!</div>
                        <input type="file" 
                               name="images[]" 
                               id="imageInput" 
                               style="display: none;" 
                               multiple 
                               accept="image/*"
                               onchange="previewImages(event)">
                    </div>

                    <div id="previewContainer" class="preview-container"></div>
                </div>

                <!-- Mô tả -->
                <div class="form-group">
                    <label class="form-label" for="descriptionInput">
                        Mô tả chi tiết <span class="req">*</span>
                    </label>
                    <textarea name="description" 
                              id="descriptionInput" 
                              class="form-control" 
                              rows="5" 
                              placeholder="Mô tả chi tiết về tình trạng món đồ, thời gian đã dùng, xuất xứ, phụ kiện kèm theo, lý do bán..." 
                              required>{{ old('description') }}</textarea>
                </div>

                <!-- Khu vực & Địa chỉ -->
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="postProvinceSelect">
                            Tỉnh / Thành phố <span class="req">*</span>
                        </label>
                        <select name="province" id="postProvinceSelect" class="form-control" required>
                            <option value="">-- Đang tải danh sách tỉnh... --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="addressInput">
                            Địa chỉ chi tiết (Quận/huyện, số nhà)
                        </label>
                        <input type="text" 
                               name="address" 
                               id="addressInput" 
                               class="form-control" 
                               placeholder="VD: Quận Cầu Giấy, Hà Nội"
                               value="{{ old('address', $user->profile->dia_chi ?? '') }}">
                    </div>
                </div>

                <!-- Số điện thoại -->
                <div class="form-group">
                    <label class="form-label" for="phoneInput">
                        Số điện thoại liên hệ <span class="req">*</span>
                    </label>
                    <input type="text" 
                           name="phone" 
                           id="phoneInput" 
                           class="form-control" 
                           placeholder="VD: 0912345678"
                           value="{{ old('phone', $user->sdt ?? '') }}" 
                           required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-circle-check"></i> ĐĂNG TIN BÁN NGAY
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function setPrice(val) {
        document.getElementById('priceInput').value = val;
    }

    function previewImages(event) {
        const container = document.getElementById('previewContainer');
        container.innerHTML = '';
        const files = event.target.files;

        if (files) {
            Array.from(files).forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const box = document.createElement('div');
                    box.className = 'preview-box';
                    box.innerHTML = `<img src="${e.target.result}" alt="Preview ${idx}">`;
                    container.appendChild(box);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // Tải danh sách tỉnh thành
    async function loadPostProvinces() {
        const select = document.getElementById('postProvinceSelect');
        if (!select) return;

        try {
            const res = await fetch('https://provinces.open-api.vn/api/p/');
            if (!res.ok) throw new Error();
            const list = await res.json();
            list.sort((a, b) => a.name.localeCompare(b.name, 'vi'));

            select.innerHTML = '<option value="">-- Chọn Tỉnh / Thành phố --</option>';
            list.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                select.appendChild(opt);
            });
        } catch (e) {
            select.innerHTML = `
                <option value="Hà Nội">Hà Nội</option>
                <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                <option value="Đà Nẵng">Đà Nẵng</option>
                <option value="Cần Thơ">Cần Thơ</option>
                <option value="Hải Phòng">Hải Phòng</option>
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', loadPostProvinces);
</script>
@endsection

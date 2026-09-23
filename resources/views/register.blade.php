@extends('layouts.app')

@section('title', 'Tạo Tài Khoản Mới - Chợ Tốt')

@push('styles')
<style>
    .auth-page {
        padding: 40px 16px 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-card {
        width: min(460px, 100%);
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 36px 32px;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.06);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .auth-header h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .auth-header p {
        font-size: 14px;
        color: var(--muted);
    }

    .auth-form .form-group {
        margin-bottom: 18px;
    }

    .auth-form label {
        display: block;
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .auth-form label span.req {
        color: #dc2626;
    }

    .auth-form .form-control {
        width: 100%;
        height: 48px;
        padding: 0 14px;
        font-size: 15px;
        border: 1px solid #d1d5db;
        border-radius: var(--radius-sm);
        outline: none;
        transition: all 0.2s;
    }

    .auth-form .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(242, 106, 61, 0.15);
    }

    .btn-auth {
        width: 100%;
        height: 48px;
        background: var(--primary);
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 10px;
        box-shadow: 0 6px 16px rgba(242, 106, 61, 0.25);
    }

    .btn-auth:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
    }

    .auth-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 14px;
        color: var(--muted);
    }

    .auth-footer a {
        color: var(--primary);
        font-weight: 700;
        text-decoration: none;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Tạo tài khoản</h1>
            <p>Trở thành thành viên Chợ Tốt để đăng tin mua bán nhanh chóng</p>
        </div>

        @if($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 14px;">
                @foreach($errors->all() as $error)
                    <div><i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="/register" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="nameInput">Họ và tên <span class="req">*</span></label>
                <input type="text" 
                       name="name" 
                       id="nameInput" 
                       class="form-control" 
                       placeholder="VD: Nguyễn Văn A" 
                       value="{{ old('name') }}" 
                       required>
            </div>

            <div class="form-group">
                <label for="sdtInput">Số điện thoại</label>
                <input type="text" 
                       name="sdt" 
                       id="sdtInput" 
                       class="form-control" 
                       placeholder="VD: 0912345678" 
                       value="{{ old('sdt') }}">
            </div>

            <div class="form-group">
                <label for="emailInput">Gmail / Email</label>
                <input type="email" 
                       name="email" 
                       id="emailInput" 
                       class="form-control" 
                       placeholder="VD: vidu@gmail.com" 
                       value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="passwordInput">Mật khẩu <span class="req">*</span></label>
                <input type="password" 
                       name="password" 
                       id="passwordInput" 
                       class="form-control" 
                       placeholder="Tối thiểu 6 ký tự..." 
                       required>
            </div>

            <div class="form-group">
                <label for="passwordConfirmInput">Nhập lại mật khẩu <span class="req">*</span></label>
                <input type="password" 
                       name="password_confirmation" 
                       id="passwordConfirmInput" 
                       class="form-control" 
                       placeholder="Nhập lại chính xác mật khẩu..." 
                       required>
            </div>

            <button type="submit" class="btn-auth">
                <i class="fa-solid fa-user-plus" style="margin-right: 6px;"></i> Tạo tài khoản ngay
            </button>
        </form>

        <div style="display: flex; align-items: center; margin: 20px 0; color: #94a3b8; font-size: 13px;">
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            <span style="padding: 0 10px;">HOẶC</span>
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
        </div>

        <a href="{{ route('google.login') }}" style="
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            height: 48px;
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 14.5px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        ">
            <i class="fa-brands fa-google" style="color: #4285F4; font-size: 18px;"></i>
            Tiếp tục với Google
        </a>

        <div class="auth-footer">
            Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
        </div>
    </div>
</div>
@endsection

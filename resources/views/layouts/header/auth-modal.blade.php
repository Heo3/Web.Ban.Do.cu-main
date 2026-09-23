    <div id="authModal" class="auth-modal">

    <div class="auth-box">

        <button class="auth-close" onclick="closeAuthModal()">
            &times;
        </button>

        <!-- Đăng nhập -->
        <div id="loginForm">

            <h2>Đăng nhập</h2>

            <p class="auth-description">
                Đăng nhập bằng Gmail hoặc số điện thoại
            </p>

            <form action="/login" method="POST">

                @csrf

                <input
                    type="text"
                    name="login"
                    placeholder="Gmail hoặc số điện thoại"
                    required
                >

                <input
                    type="password"
                    name="password"
                    placeholder="Mật khẩu"
                    required
                >

                <button type="submit" class="auth-submit">
                    Đăng nhập
                </button>

            </form>

            <div style="display: flex; align-items: center; margin: 15px 0 10px; color: #94a3b8; font-size: 12px;">
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                <span style="padding: 0 8px;">HOẶC</span>
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            </div>

            <a href="{{ route('google.login') }}" style="
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                height: 40px;
                background: #ffffff;
                color: #334155;
                border: 1px solid #cbd5e1;
                border-radius: 6px;
                font-weight: 600;
                font-size: 13.5px;
                text-decoration: none;
                margin-bottom: 12px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            ">
                <i class="fa-brands fa-google" style="color: #4285F4; font-size: 16px;"></i>
                Đăng nhập bằng Google
            </a>

            <p class="auth-switch">
                Chưa có tài khoản?
                <a href="#" onclick="openAuthModal('register'); return false;">
                    Tạo tài khoản
                </a>
            </p>

        </div>


        <!-- Đăng ký -->
        <div id="registerForm" style="display: none;">

            <h2>Tạo tài khoản</h2>

            <p class="auth-description">
                Tạo tài khoản mới
            </p>

           <form action="/register" method="POST">

                @csrf

                <input
                    type="text"
                    name="name"
                    placeholder="Họ và tên"
                    required
                >

                <input
                    type="text"
                    name="sdt"
                    placeholder="Số điện thoại"
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Gmail"
                >

                <input
                    type="password"
                    name="password"
                    placeholder="Mật khẩu"
                    required
                >

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Nhập lại mật khẩu"
                    required
                >

                <button type="submit" class="auth-submit">
                    Tạo tài khoản
                </button>

            </form>

            <p class="auth-switch">
                Đã có tài khoản?
                <a href="#" onclick="openAuthModal('login'); return false;">
                    Đăng nhập
                </a>
            </p>

        </div>

        {{-- Cài đặt thông tin --}}
        @auth
        <div id="profileForm" style="display: none;">

            <h2>Cài đặt thông tin</h2>

            <p class="auth-description">
                Cập nhật thông tin tài khoản
            </p>

            <form action="/profile/update"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="profile-avatar-preview">

                    @if(Auth::user()->avatar_url)

                        <img
                            src="{{ Auth::user()->avatar_url }}"
                            alt="Avatar"
                        >

                    @else

                        👤

                    @endif

                </div>

                <input
                    type="file"
                    name="avatar"
                    accept="image/*"
                >

                <input
                    type="text"
                    name="name"
                    placeholder="Họ và tên"
                    value="{{ Auth::user()->profile->name ?? '' }}"
                >

                <input
                    type="text"
                    name="sdt"
                    placeholder="Số điện thoại"
                    value="{{ Auth::user()->sdt ?? '' }}"
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Gmail"
                    value="{{ Auth::user()->email ?? '' }}"
                >

                <textarea
                    name="dia_chi"
                    placeholder="Địa chỉ"
                >{{ Auth::user()->profile->dia_chi ?? '' }}</textarea>

                <button type="submit" class="auth-submit">
                    Lưu thay đổi
                </button>

            </form>

        </div>
        @endauth

    </div>

</div>

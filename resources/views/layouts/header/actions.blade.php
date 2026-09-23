{{-- Các chức năng --}}
<div class="header-actions">

    {{-- Đăng tin --}}
    <a href="{{ route('products.create') }}" class="post-btn">
        <i class="fa-solid fa-camera" style="margin-right: 6px;"></i>
        ĐĂNG TIN
    </a>

    @auth

        {{-- ==================== ĐÃ ĐĂNG NHẬP ==================== --}}

        {{-- Admin --}}
        @if(Auth::user()->isAdmin())

            <a href="{{ route('admin.dashboard') }}"
               class="admin-badge-btn"
               title="Trang quản trị hệ thống">

                <i class="fa-solid fa-shield-halved"></i>
                Quản trị

            </a>

        @endif


        {{-- Tin yêu thích --}}
        <a href="{{ route('products.favorites') }}"
           class="header-fav-btn"
           title="Tin đã lưu">

            <i class="fa-solid fa-heart"></i>

            @php
                $favCount = Auth::user()->favorites()->count();
            @endphp

            @if($favCount > 0)
                <span class="fav-badge">
                    {{ $favCount }}
                </span>
            @endif

        </a>


        {{-- Hộp thư tin nhắn --}}
        <a href="{{ route('chat.index') }}"
           class="header-chat-btn"
           title="Hộp thư tin nhắn">

            <i class="fa-solid fa-comments"></i>

            @php
                $unreadMsgCount = Auth::user()->totalUnreadMessagesCount();
            @endphp

            <span class="chat-badge"
                  id="headerChatBadge"
                  style="{{ $unreadMsgCount > 0 ? '' : 'display: none;' }}">
                {{ $unreadMsgCount }}
            </span>

        </a>


        {{-- ==================== TÀI KHOẢN ==================== --}}
        <div class="account">

            {{-- Avatar --}}
            <div class="account-avatar">

                @if(Auth::user()->avatar_url)

                    <img
                        src="{{ Auth::user()->avatar_url }}"
                        alt="Avatar"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <i class="fa-solid fa-user" style="display: none;"></i>

                @else

                    <i class="fa-solid fa-user"></i>

                @endif

            </div>


            {{-- Menu tài khoản --}}
            <div class="account-menu">

                {{-- Tên tài khoản --}}
                <div class="account-name">

                    <i class="fa-solid fa-circle-user"
                       style="color: var(--orange); margin-right: 4px;">
                    </i>

                    {{ Auth::user()->profile->name ?? (Auth::user()->sdt ?? Auth::user()->email) }}

                    @if(Auth::user()->isAdmin())

                        <div style="
                            font-size: 11px;
                            color: #b45309;
                            font-weight: 800;
                            margin-top: 3px;
                        ">
                            👑 QUẢN TRỊ VIÊN
                        </div>

                    @endif

                </div>


                {{-- ==================== MENU ADMIN ==================== --}}
                @if(Auth::user()->isAdmin())

                    <div style="
                        background: #fffbeb;
                        padding: 5px 12px;
                        font-size: 11px;
                        font-weight: 800;
                        color: #b45309;
                        text-transform: uppercase;
                        border-bottom: 1px solid #fef3c7;
                    ">
                        Menu Quản Trị
                    </div>


                    {{-- Dashboard Admin --}}
                    <a href="{{ route('admin.dashboard') }}"
                       style="color: #b45309; font-weight: 600;">

                        <i class="fa-solid fa-chart-pie"
                           style="
                               width: 18px;
                               margin-right: 6px;
                               color: #f59e0b;
                           ">
                        </i>

                        Bảng điều khiển Admin

                    </a>


                    {{-- Quản lý sản phẩm --}}
                    <a href="{{ route('admin.products') }}"
                       style="color: #b45309; font-weight: 600;">

                        <i class="fa-solid fa-newspaper"
                           style="
                               width: 18px;
                               margin-right: 6px;
                               color: #f59e0b;
                           ">
                        </i>

                        Quản lý tất cả bài viết

                    </a>


                    {{-- Quản lý user --}}
                    <a href="{{ route('admin.users') }}"
                       style="color: #b45309; font-weight: 600;">

                        <i class="fa-solid fa-users-gear"
                           style="
                               width: 18px;
                               margin-right: 6px;
                               color: #f59e0b;
                           ">
                        </i>

                        Quản lý tài khoản

                    </a>


                    <div style="
                        border-bottom: 1px solid #f1e9e0;
                        margin: 4px 0;
                    "></div>

                @endif


                {{-- ==================== MENU USER ==================== --}}

                {{-- Tin của tôi --}}
                <a href="{{ route('products.my') }}">

                    <i class="fa-solid fa-boxes-stacked"
                       style="
                           width: 18px;
                           margin-right: 6px;
                           color: #4b5563;
                       ">
                    </i>

                    Quản lý tin của tôi

                </a>


                {{-- Tin đã lưu --}}
                <a href="{{ route('products.favorites') }}">

                    <i class="fa-solid fa-heart"
                       style="
                           width: 18px;
                           margin-right: 6px;
                           color: #ef4444;
                       ">
                    </i>

                    Tin đã lưu

                </a>


                {{-- Hộp thư --}}
                <a href="{{ route('chat.index') }}">

                    <i class="fa-solid fa-comments"
                       style="
                           width: 18px;
                           margin-right: 6px;
                           color: var(--orange);
                       ">
                    </i>

                    Hộp thư tin nhắn

                    @if(isset($unreadMsgCount) && $unreadMsgCount > 0)

                        <span class="badge badge-danger"
                              style="
                                  margin-left: auto;
                                  font-size: 11px;
                              ">
                            {{ $unreadMsgCount }}
                        </span>

                    @endif

                </a>


                {{-- Cài đặt thông tin --}}
                <a href="#"
                   onclick="openAuthModal('profile'); return false;">

                    <i class="fa-solid fa-gear"
                       style="
                           width: 18px;
                           margin-right: 6px;
                           color: #4b5563;
                       ">
                    </i>

                    Cài đặt thông tin

                </a>


                {{-- Đăng xuất --}}
                <form action="{{ route('logout') }}" method="POST">

                    @csrf

                    <button type="submit"
                            style="color: #ef4444;">

                        <i class="fa-solid fa-arrow-right-from-bracket"
                           style="
                               width: 18px;
                               margin-right: 6px;
                           ">
                        </i>

                        Đăng xuất

                    </button>

                </form>

            </div>

        </div>


    @else

        {{-- ==================== CHƯA ĐĂNG NHẬP ==================== --}}


        {{-- Tin yêu thích --}}
        <a href="{{ route('login') }}"
           class="header-fav-btn"
           title="Tin đã lưu"
           onclick="openAuthModal('login'); return false;">

            <i class="fa-regular fa-heart"></i>

        </a>


        {{-- Tin nhắn --}}
        <a href="{{ route('login') }}"
           class="header-chat-btn"
           title="Hộp thư tin nhắn"
           onclick="openAuthModal('login'); return false;">

            <i class="fa-regular fa-comments"></i>

        </a>


        {{-- ==================== TÀI KHOẢN CHƯA ĐĂNG NHẬP ==================== --}}
        <div class="account">

            {{-- Icon user --}}
            <div class="account-icon">

                <i class="fa-regular fa-user"></i>

            </div>


            {{-- Menu --}}
            <div class="account-menu">

                {{-- Đăng ký --}}
                <a href="#"
                   onclick="openAuthModal('register'); return false;">

                    <i class="fa-solid fa-user-plus"
                       style="
                           width: 18px;
                           margin-right: 6px;
                           color: var(--orange);
                       ">
                    </i>

                    Tạo tài khoản

                </a>


                {{-- Đăng nhập --}}
                <a href="#"
                   onclick="openAuthModal('login'); return false;">

                    <i class="fa-solid fa-right-to-bracket"
                       style="
                           width: 18px;
                           margin-right: 6px;
                           color: var(--orange);
                       ">
                    </i>

                    Đăng nhập

                </a>


                {{-- Đường phân cách --}}
                <div style="
                    border-top: 1px solid #f1e9e0;
                    margin: 5px 0;
                "></div>


                {{-- Đăng nhập Google --}}
                <a href="{{ route('google.login') }}">

                    <i class="fa-brands fa-google"
                       style="
                           width: 18px;
                           margin-right: 6px;
                           color: #4285F4;
                       ">
                    </i>

                    Đăng nhập bằng Google

                </a>

            </div>

        </div>

    @endauth

</div>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chợ Tốt - Mua Bán Đồ Cũ, Rao Vặt Nhanh Chóng')</title>
    <meta name="description" content="@yield('meta_description', 'Website mua bán đồ cũ, rao vặt trực tuyến hàng đầu. Đăng tin mua bán điện thoại, xe máy, đồ điện tử, đồ gia dụng nhanh chóng, tiện lợi.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #f26a3d;
            --primary-hover: #c94c24;
            --primary-light: #fff4ed;
            --dark: #1f2933;
            --muted: #667085;
            --light-bg: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --border-warm: #eadfd2;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 20px 30px -10px rgba(91, 62, 45, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f6f7f9;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
        }

        main {
            flex: 1;
            width: 100%;
        }

        .container {
            width: min(1240px, calc(100% - 32px));
            margin: 0 auto;
        }

        /* Flash Alerts */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .alert-toast {
            pointer-events: auto;
            min-width: 320px;
            max-width: 450px;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideInRight 0.3s ease forwards;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-toast.success {
            background-color: #10b981;
            color: #fff;
        }

        .alert-toast.error {
            background-color: #ef4444;
            color: #fff;
        }

        .alert-toast .close-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            cursor: pointer;
            padding: 0 4px;
        }
        .alert-toast .close-btn:hover {
            color: #fff;
        }

        /* Nút chung */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(242, 106, 61, 0.28);
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--dark);
        }
        .btn-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
        }
        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }
        /* Custom Compact Pagination */
        .custom-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 28px 0;
            width: 100%;
        }

        .pagination-list {
            display: inline-flex;
            align-items: center;
            list-style: none;
            gap: 6px;
            padding: 5px 8px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .pagination-list .page-item {
            display: inline-block;
        }

        .pagination-list .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            user-select: none;
        }

        .pagination-list a.page-link:hover {
            background: #fff4ed;
            color: var(--primary);
            border-color: #fbd0b9;
            transform: translateY(-1px);
        }

        .pagination-list .page-item.active .page-link {
            background: var(--primary);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(242, 106, 61, 0.3);
        }

        .pagination-list .page-item.disabled .page-link {
            color: #cbd5e1;
            cursor: not-allowed;
            pointer-events: none;
            background: transparent;
        }

        .pagination-list .page-link.dots {
            min-width: 24px;
            padding: 0;
            color: #94a3b8;
        }

        /* Reset for any standard SVG pagination icons */
        nav[role="navigation"] svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            display: inline-block !important;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Flash message alerts -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="alert-toast success">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-toast error">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert-toast error">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
    </div>

    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @include('components.chatbot')

    <script>
        // Tự động tắt toast sau 4 giây
        setTimeout(() => {
            document.querySelectorAll('.alert-toast').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(100%)';
                el.style.transition = 'all 0.4s ease';
                setTimeout(() => el.remove(), 400);
            });
        }, 4000);

        // Cập nhật số tin nhắn chưa đọc trên Header định kỳ
        @auth
        async function updateHeaderUnreadCount() {
            try {
                const res = await fetch('/api/chat/unread-count', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                const badge = document.getElementById('headerChatBadge');
                if (badge) {
                    if (data.unread_count > 0) {
                        badge.innerText = data.unread_count;
                        badge.style.display = 'inline-flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            } catch (e) {}
        }
        setInterval(updateHeaderUnreadCount, 8000);
        @endauth

        // Global favorite toggle handler
        async function toggleFavorite(productId, buttonElement) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`/san-pham/${productId}/favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 401) {
                    if (typeof openAuthModal === 'function') {
                        openAuthModal('login');
                    } else {
                        window.location.href = '/login';
                    }
                    return;
                }

                const data = await response.json();
                if (data.success) {
                    if (buttonElement) {
                        const heartIcon = buttonElement.querySelector('i');
                        if (heartIcon) {
                            if (data.favorited) {
                                heartIcon.classList.remove('fa-regular');
                                heartIcon.classList.add('fa-solid');
                                heartIcon.style.color = '#ef4444';
                            } else {
                                heartIcon.classList.remove('fa-solid');
                                heartIcon.classList.add('fa-regular');
                                heartIcon.style.color = '';
                            }
                        }
                    }
                    // Show small toast
                    showToast(data.message, 'success');
                }
            } catch (err) {
                console.error(err);
            }
        }

        function showToast(msg, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = `alert-toast ${type}`;
            toast.innerHTML = `
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-${type === 'success' ? 'circle-check' : 'triangle-exclamation'}"></i>
                    <span>${msg}</span>
                </div>
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.4s ease';
                setTimeout(() => toast.remove(), 400);
            }, 3500);
        }
    </script>

    @stack('scripts')
</body>
</html>
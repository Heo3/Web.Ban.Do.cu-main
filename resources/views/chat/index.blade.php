@extends('layouts.app')

@section('title', 'Hộp thư tin nhắn - Chợ Tốt')

@push('styles')
<style>
    .chat-page {
        padding: 30px 0 60px;
        background-color: #f4f6f8;
        min-height: calc(100vh - 250px);
    }

    .chat-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        display: grid;
        grid-template-columns: 360px 1fr;
        height: 720px;
        max-height: 85vh;
        overflow: hidden;
    }

    /* Left panel: Conversation list */
    .chat-sidebar {
        border-right: 1px solid #edf2f7;
        display: flex;
        flex-direction: column;
        background: #fafbfc;
        height: 100%;
        overflow: hidden;
    }

    .sidebar-header {
        padding: 20px 20px 14px;
        border-bottom: 1px solid #edf2f7;
        background: #ffffff;
    }

    .sidebar-title {
        font-size: 19px;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .sidebar-title span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sidebar-title i {
        color: var(--primary);
    }

    .search-conversation {
        position: relative;
    }

    .search-conversation input {
        width: 100%;
        height: 40px;
        padding: 0 14px 0 38px;
        background: #f1f5f9;
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 13.5px;
        outline: none;
        transition: all 0.2s ease;
    }

    .search-conversation input:focus {
        background: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(242, 106, 61, 0.12);
    }

    .search-conversation i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .conversations-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .conversation-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        background: #ffffff;
        border: 1px solid transparent;
        position: relative;
        cursor: pointer;
    }

    .conversation-item:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
        transform: translateY(-1px);
    }

    .conversation-item.active {
        background: #fff4ed;
        border-color: #fdba74;
        box-shadow: 0 4px 12px rgba(242, 106, 61, 0.1);
    }

    .conv-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .conv-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #64748b;
        overflow: hidden;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .conv-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .online-indicator {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 12px;
        height: 12px;
        background: #10b981;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    .conv-body {
        flex: 1;
        min-width: 0;
    }

    .conv-top {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 4px;
    }

    .conv-name {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-time {
        font-size: 11.5px;
        color: #94a3b8;
        flex-shrink: 0;
    }

    .conv-product-title {
        font-size: 11.5px;
        color: var(--primary);
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .conv-last-msg {
        font-size: 13px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conversation-item.unread .conv-name {
        color: #0f172a;
        font-weight: 800;
    }

    .conversation-item.unread .conv-last-msg {
        color: #0f172a;
        font-weight: 700;
    }

    .conv-badge {
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .empty-sidebar {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-sidebar i {
        font-size: 40px;
        margin-bottom: 12px;
        color: #cbd5e1;
    }

    /* Right panel: Active chat window */
    .chat-main {
        display: flex;
        flex-direction: column;
        background: #ffffff;
        height: 100%;
        overflow: hidden;
    }

    .chat-header {
        padding: 16px 24px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        z-index: 2;
    }

    .chat-partner {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .partner-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #64748b;
    }

    .partner-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .partner-info h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .partner-status {
        font-size: 12px;
        color: #10b981;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
    }

    /* Pinned Product Card */
    .pinned-product {
        background: #fff8f5;
        border-bottom: 1px solid #fed7aa;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .pinned-product-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .pinned-img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #fdba74;
        flex-shrink: 0;
    }

    .pinned-details {
        min-width: 0;
    }

    .pinned-tag {
        font-size: 11px;
        font-weight: 700;
        color: #c2410c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pinned-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pinned-price {
        font-size: 13.5px;
        font-weight: 800;
        color: #e11d48;
    }

    .pinned-btn {
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--primary);
        background: #ffffff;
        border: 1px solid #fdba74;
        border-radius: 8px;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .pinned-btn:hover {
        background: var(--primary);
        color: #ffffff;
    }

    /* Message stream */
    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background: #f8fafc;
    }

    .msg-group {
        display: flex;
        flex-direction: column;
        max-width: 70%;
    }

    .msg-group.me {
        align-self: flex-end;
        align-items: flex-end;
    }

    .msg-group.other {
        align-self: flex-start;
        align-items: flex-start;
    }

    .msg-bubble {
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 14.5px;
        line-height: 1.5;
        word-break: break-word;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
        position: relative;
    }

    .msg-group.me .msg-bubble {
        background: linear-gradient(135deg, #f26a3d 0%, #e0552a 100%);
        color: #ffffff;
        border-bottom-right-radius: 4px;
    }

    .msg-group.other .msg-bubble {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
    }

    .msg-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
        padding: 0 4px;
    }

    .msg-group.me .msg-time {
        color: #94a3b8;
    }

    /* Quick replies */
    .quick-replies {
        padding: 8px 24px 0;
        display: flex;
        gap: 8px;
        overflow-x: auto;
        background: #ffffff;
        scrollbar-width: none;
    }
    .quick-replies::-webkit-scrollbar {
        display: none;
    }

    .quick-chip {
        padding: 5px 12px;
        font-size: 12.5px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .quick-chip:hover {
        background: #fff4ed;
        color: var(--primary);
        border-color: #fdba74;
    }

    /* Chat input form */
    .chat-input-bar {
        padding: 14px 24px;
        border-top: 1px solid #edf2f7;
        background: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-input-bar input {
        flex: 1;
        height: 46px;
        padding: 0 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s;
    }

    .chat-input-bar input:focus {
        background: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(242, 106, 61, 0.12);
    }

    .btn-send-msg {
        height: 46px;
        padding: 0 20px;
        background: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(242, 106, 61, 0.25);
    }

    .btn-send-msg:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
    }

    /* Empty State */
    .chat-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        padding: 40px;
        text-align: center;
        background: #f8fafc;
    }

    .empty-icon-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #fff4ed;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin-bottom: 20px;
    }

    .chat-empty-state h3 {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .chat-empty-state p {
        font-size: 14px;
        color: #64748b;
        max-width: 360px;
    }

    @media (max-width: 900px) {
        .chat-card {
            grid-template-columns: 1fr;
            height: auto;
            min-height: 600px;
        }

        .chat-sidebar {
            display: {{ $activeConversation ? 'none' : 'flex' }};
            height: 600px;
        }

        .chat-main {
            display: {{ $activeConversation ? 'flex' : 'none' }};
            height: 600px;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-page">
    <div class="container">

        <div class="chat-card">

            <!-- Sidebar: Danh sách cuộc trò chuyện -->
            <div class="chat-sidebar">
                <div class="sidebar-header">
                    <div class="sidebar-title">
                        <span><i class="fa-solid fa-comments"></i> Hộp thư tin nhắn</span>
                        <span style="font-size: 13px; font-weight: 600; color: #64748b;" id="totalConversationsCount">
                            {{ $conversations->count() }} cuộc hội thoại
                        </span>
                    </div>

                    <div class="search-conversation">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="convSearchInput" placeholder="Tìm người bán, sản phẩm..." oninput="filterConversations(this.value)">
                    </div>
                </div>

                <div class="conversations-list" id="conversationsList">
                    @forelse($conversations as $c)
                        @php
                            $other = $c->getOtherUser(Auth::id());
                            $unread = $c->getUnreadCount(Auth::id());
                            $isActive = $activeConversation && $activeConversation->id === $c->id;
                        @endphp
                        <a href="{{ route('chat.index', ['conversation_id' => $c->id]) }}" 
                           class="conversation-item {{ $isActive ? 'active' : '' }} {{ $unread > 0 ? 'unread' : '' }}" 
                           data-id="{{ $c->id }}"
                           data-name="{{ $other->profile->name ?? ($other->sdt ?? $other->email) }}"
                           data-product="{{ $c->product->title ?? '' }}">
                            
                            <div class="conv-avatar-wrap">
                                <div class="conv-avatar">
                                    @if($other && $other->avatar_url)
                                        <img src="{{ $other->avatar_url }}" alt="Avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                        <i class="fa-solid fa-user" style="display: none;"></i>
                                    @else
                                        <i class="fa-solid fa-user"></i>
                                    @endif
                                </div>
                                <div class="online-indicator"></div>
                            </div>

                            <div class="conv-body">
                                <div class="conv-top">
                                    <div class="conv-name">
                                        {{ $other->profile->name ?? ($other->sdt ?? ($other->email ?? 'Người dùng')) }}
                                    </div>
                                    <div class="conv-time">
                                        {{ $c->last_message_at ? $c->last_message_at->diffForHumans() : '' }}
                                    </div>
                                </div>

                                @if($c->product)
                                    <div class="conv-product-title" title="{{ $c->product->title }}">
                                        <i class="fa-solid fa-tag"></i> {{ Str::limit($c->product->title, 26) }}
                                    </div>
                                @endif

                                <div class="conv-last-msg">
                                    {{ $c->last_message ?: 'Bắt đầu cuộc trò chuyện...' }}
                                </div>
                            </div>

                            @if($unread > 0)
                                <div class="conv-badge">{{ $unread }}</div>
                            @endif
                        </a>
                    @empty
                        <div class="empty-sidebar">
                            <i class="fa-regular fa-comment-dots"></i>
                            <p>Bạn chưa có cuộc trò chuyện nào.</p>
                            <p style="font-size: 12.5px; margin-top: 6px;">Hãy nhấp <strong>"Chat với người bán"</strong> trên bất kỳ tin đăng nào để bắt đầu!</p>
                        </div>
                    @endforelse
                </div>
            </div>


            <!-- Main: Khung chat trò chuyện -->
            <div class="chat-main">
                @if($activeConversation)
                    @php
                        $activeOther = $activeConversation->getOtherUser(Auth::id());
                    @endphp

                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="chat-partner">
                            <div class="partner-avatar">
                                @if($activeOther && $activeOther->avatar_url)
                                    <img src="{{ $activeOther->avatar_url }}" alt="Partner" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                    <i class="fa-solid fa-user" style="display: none;"></i>
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <div class="partner-info">
                                <h3>{{ $activeOther->profile->name ?? ($activeOther->sdt ?? ($activeOther->email ?? 'Người dùng')) }}</h3>
                                <div class="partner-status">
                                    <span class="status-dot"></span> Đang trực tuyến
                                </div>
                            </div>
                        </div>

                        <div>
                            @if($activeOther)
                                <a href="{{ route('home', ['search' => $activeOther->profile->name ?? '']) }}" 
                                   class="btn btn-outline" 
                                   style="padding: 6px 14px; font-size: 13px;" 
                                   title="Xem các tin người này đang bán">
                                    <i class="fa-solid fa-store"></i> Xem tin người bán
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Pinned Product Information -->
                    @if($activeConversation->product)
                        <div class="pinned-product">
                            <div class="pinned-product-left">
                                <img src="{{ $activeConversation->product->display_image }}" alt="Product" class="pinned-img">
                                <div class="pinned-details">
                                    <div class="pinned-tag">Đang trao đổi về sản phẩm</div>
                                    <div class="pinned-title">{{ $activeConversation->product->title }}</div>
                                    <div class="pinned-price">{{ $activeConversation->product->formatted_price }}</div>
                                </div>
                            </div>
                            <a href="{{ route('products.show', $activeConversation->product->id) }}" target="_blank" class="pinned-btn">
                                Xem tin đăng <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    @endif

                    <!-- Message History Stream -->
                    <div class="messages-area" id="messagesArea">
                        @forelse($messages as $m)
                            <div class="msg-group {{ $m->sender_id === Auth::id() ? 'me' : 'other' }}" data-id="{{ $m->id }}">
                                <div class="msg-bubble">
                                    {{ $m->message }}
                                </div>
                                <div class="msg-time">
                                    {{ $m->formatted_time }}
                                </div>
                            </div>
                        @empty
                            <div style="margin: auto; text-align: center; color: #94a3b8;">
                                <i class="fa-solid fa-hand-wave" style="font-size: 32px; color: var(--primary); margin-bottom: 8px;"></i>
                                <p>Hãy gửi lời chào mở đầu cuộc trò chuyện nhé!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Quick Suggestions -->
                    <div class="quick-replies">
                        <button type="button" class="quick-chip" onclick="sendQuickReply('Sản phẩm này còn không bạn?')">
                            Sản phẩm này còn không?
                        </button>
                        <button type="button" class="quick-chip" onclick="sendQuickReply('Giá này có bớt thêm được không ạ?')">
                            Có bớt giá không?
                        </button>
                        <button type="button" class="quick-chip" onclick="sendQuickReply('Cho mình xin địa chỉ xem hàng trực tiếp nhé!')">
                            Địa chỉ xem hàng ở đâu?
                        </button>
                        <button type="button" class="quick-chip" onclick="sendQuickReply('Món đồ này còn hoạt động tốt không bạn?')">
                            Tình trạng thế nào?
                        </button>
                    </div>

                    <!-- Input Bar -->
                    <form class="chat-input-bar" id="chatForm" onsubmit="handleSendMessage(event)">
                        <input type="text" 
                               id="chatInput" 
                               placeholder="Nhập nội dung tin nhắn... (Nhấn Enter để gửi)" 
                               autocomplete="off">
                        
                        <button type="submit" class="btn-send-msg" id="sendBtn">
                            <span>Gửi</span>
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>

                @else
                    <!-- Empty State khi chưa chọn hội thoại -->
                    <div class="chat-empty-state">
                        <div class="empty-icon-wrap">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <h3>Chào mừng đến Hộp thư Chợ Tốt</h3>
                        <p>Chọn một cuộc trò chuyện từ danh sách bên trái hoặc bấm "Chat với người bán" ở trang chi tiết món đồ để bắt đầu thương lượng!</p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const activeConversationId = {{ $activeConversation ? $activeConversation->id : 'null' }};
    const currentUserId = {{ Auth::id() }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Tự động cuộn xuống cuối khung tin nhắn
    function scrollToBottom() {
        const area = document.getElementById('messagesArea');
        if (area) {
            area.scrollTop = area.scrollHeight;
        }
    }
    scrollToBottom();

    // Tìm kiếm / lọc cuộc hội thoại bên sidebar
    function filterConversations(keyword) {
        const q = keyword.toLowerCase().trim();
        document.querySelectorAll('.conversation-item').forEach(item => {
            const name = (item.getAttribute('data-name') || '').toLowerCase();
            const product = (item.getAttribute('data-product') || '').toLowerCase();
            if (name.includes(q) || product.includes(q)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Gửi phản hồi nhanh (Quick Reply)
    function sendQuickReply(text) {
        const input = document.getElementById('chatInput');
        if (input) {
            input.value = text;
            input.focus();
            handleSendMessage(new Event('submit'));
        }
    }

    // Xử lý gửi tin nhắn mới
    async function handleSendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        if (!input || !activeConversationId) return;

        const messageText = input.value.trim();
        if (!messageText) return;

        input.value = '';
        input.focus();

        // Thêm tin nhắn tạm thời vào giao diện (Optimistic UI)
        const messagesArea = document.getElementById('messagesArea');
        const tempId = 'temp_' + Date.now();
        const nowTime = new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Ho_Chi_Minh' });

        const tempBubble = document.createElement('div');
        tempBubble.className = 'msg-group me';
        tempBubble.id = tempId;
        tempBubble.innerHTML = `
            <div class="msg-bubble">${escapeHtml(messageText)}</div>
            <div class="msg-time">${nowTime}</div>
        `;
        messagesArea.appendChild(tempBubble);
        scrollToBottom();

        try {
            const res = await fetch(`/api/chat/conversations/${activeConversationId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: messageText })
            });

            const data = await res.json();
            if (!data.success) {
                tempBubble.style.opacity = '0.5';
                showToast(data.message || 'Không thể gửi tin nhắn.', 'error');
            } else {
                // Cập nhật last message bên sidebar
                const activeItem = document.querySelector(`.conversation-item[data-id="${activeConversationId}"]`);
                if (activeItem) {
                    const lastMsgEl = activeItem.querySelector('.conv-last-msg');
                    if (lastMsgEl) lastMsgEl.innerText = messageText;
                }
            }
        } catch (err) {
            console.error(err);
            tempBubble.style.opacity = '0.5';
            showToast('Lỗi kết nối khi gửi tin nhắn.', 'error');
        }
    }

    // Polling định kỳ tải tin nhắn mới của cuộc trò chuyện hiện tại (mỗi 3 giây)
    // Dùng lastMessageId để track tin nhắn mới, tránh duplicate / lộn xộn với optimistic UI
    let pollInterval = null;
    let lastMessageId = 0;

    // Khởi tạo lastMessageId từ tin nhắn đã render sẵn (server-side)
    document.querySelectorAll('#messagesArea .msg-group[data-id]').forEach(el => {
        const id = parseInt(el.getAttribute('data-id') || '0', 10);
        if (id > lastMessageId) lastMessageId = id;
    });

    if (activeConversationId) {
        pollInterval = setInterval(pollMessages, 3000);
    }

    async function pollMessages() {
        if (!activeConversationId) return;

        try {
            const res = await fetch(`/api/chat/conversations/${activeConversationId}/messages`, {
                headers: { 'Accept': 'application/json' }
            });

            if (!res.ok) return;
            const data = await res.json();

            if (data.success && data.messages) {
                const area = document.getElementById('messagesArea');
                if (!area) return;

                // Chỉ lấy những tin nhắn MỚI hơn lastMessageId
                const newMessages = data.messages.filter(m => m.id > lastMessageId);

                if (newMessages.length > 0) {
                    // Xóa tất cả các tin nhắn tạm (temp_*) nếu có
                    area.querySelectorAll('.msg-group[id^="temp_"]').forEach(el => el.remove());

                    // Thêm tin nhắn mới vào cuối (không re-render toàn bộ)
                    newMessages.forEach(m => {
                        const div = document.createElement('div');
                        div.className = `msg-group ${m.is_me ? 'me' : 'other'}`;
                        div.setAttribute('data-id', m.id);
                        div.innerHTML = `
                            <div class="msg-bubble">${escapeHtml(m.message)}</div>
                            <div class="msg-time">${m.formatted_time}</div>
                        `;
                        area.appendChild(div);
                        if (m.id > lastMessageId) lastMessageId = m.id;
                    });

                    scrollToBottom();
                }
            }
        } catch (e) {
            // Im lặng bỏ qua lỗi mạng định kỳ
        }
    }

    function escapeHtml(string) {
        const div = document.createElement('div');
        div.innerText = string;
        return div.innerHTML;
    }
</script>
@endpush

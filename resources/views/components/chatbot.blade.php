<!-- Floating AI Assistant Chatbot Component -->
<div id="chatbotContainer">
    <!-- Floating Launcher Trigger Button -->
    <button type="button" id="chatbotTriggerBtn" class="chatbot-trigger" onclick="toggleChatbot()" title="Trò chuyện với Trợ lý ảo Chợ Tốt">
        <div class="chatbot-trigger-icon">
            <i class="fa-solid fa-robot"></i>
        </div>
        <div class="chatbot-trigger-pulse"></div>
        <div class="chatbot-trigger-tooltip">
            <span>Trợ lý 24/7 💬</span>
        </div>
    </button>

    <!-- Chatbot Window -->
    <div id="chatbotWindow" class="chatbot-window">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-header-left">
                <div class="chatbot-bot-avatar">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="chatbot-header-info">
                    <div class="chatbot-bot-title">Trợ Lý Ảo Chợ Tốt</div>
                    <div class="chatbot-bot-status">
                        <span class="status-indicator"></span> Sẵn sàng giải đáp 24/7
                    </div>
                </div>
            </div>

            <div class="chatbot-header-actions">
                <button type="button" class="chatbot-action-btn" onclick="resetChatbotHistory()" title="Bắt đầu lại cuộc hội thoại">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
                <button type="button" class="chatbot-action-btn" onclick="toggleChatbot()" title="Đóng cửa sổ">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Messages Body -->
        <div class="chatbot-body" id="chatbotMessages">
            <!-- Messages inserted dynamically via JS -->
        </div>

        <!-- Quick Suggestion Chips -->
        <div class="chatbot-chips" id="chatbotChips">
            <button type="button" class="bot-chip" onclick="handleChipClick('🔍 Tìm iPhone giá rẻ')">🔍 Tìm iPhone</button>
            <button type="button" class="bot-chip" onclick="handleChipClick('🛵 Tìm Xe máy')">🛵 Tìm Xe máy</button>
            <button type="button" class="bot-chip" onclick="handleChipClick('📝 Hướng dẫn đăng tin')">📝 Cách đăng tin</button>
            <button type="button" class="bot-chip" onclick="handleChipClick('🛡️ Mẹo tránh lừa đảo')">🛡️ Mẹo an toàn</button>
            <button type="button" class="bot-chip" onclick="handleChipClick('📦 Tin mới nhất')">📦 Tin mới nhất</button>
        </div>

        <!-- Footer Input Bar -->
        <form class="chatbot-footer" id="chatbotForm" onsubmit="handleSendBotMessage(event)">
            <input type="text" 
                   id="chatbotInput" 
                   placeholder="Hỏi về sản phẩm, cách mua bán..." 
                   autocomplete="off"
                   maxlength="250">

            <button type="submit" class="chatbot-send-btn" id="chatbotSendBtn" title="Gửi tin nhắn">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<style>
    /* Chatbot Trigger Button */
    .chatbot-trigger {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f26a3d 0%, #e0552a 100%);
        color: #ffffff;
        border: none;
        box-shadow: 0 8px 24px rgba(242, 106, 61, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9998;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s;
    }

    .chatbot-trigger:hover {
        transform: scale(1.08);
        box-shadow: 0 12px 30px rgba(242, 106, 61, 0.5);
    }

    .chatbot-trigger-icon {
        font-size: 26px;
        z-index: 2;
    }

    .chatbot-trigger-pulse {
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid rgba(242, 106, 61, 0.6);
        animation: botPulse 2s infinite;
        pointer-events: none;
    }

    @keyframes botPulse {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.2); opacity: 0; }
        100% { transform: scale(0.95); opacity: 0; }
    }

    .chatbot-trigger-tooltip {
        position: absolute;
        right: calc(100% + 12px);
        background: #1e293b;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        pointer-events: none;
        opacity: 0.95;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: opacity 0.2s;
    }

    /* Chatbot Main Window */
    .chatbot-window {
        position: fixed;
        bottom: 96px;
        right: 24px;
        width: 380px;
        max-width: calc(100vw - 32px);
        height: 570px;
        max-height: calc(100vh - 120px);
        background: #ffffff;
        border-radius: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        display: none;
        flex-direction: column;
        overflow: hidden;
        z-index: 9999;
        animation: chatWindowPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .chatbot-window.open {
        display: flex;
    }

    @keyframes chatWindowPop {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.96);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, #f26a3d 0%, #ea580c 100%);
        color: #ffffff;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .chatbot-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chatbot-bot-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
    }

    .chatbot-bot-title {
        font-size: 15px;
        font-weight: 800;
        letter-spacing: -0.2px;
    }

    .chatbot-bot-status {
        font-size: 11.5px;
        color: rgba(255, 255, 255, 0.85);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .chatbot-bot-status .status-indicator {
        width: 7px;
        height: 7px;
        background: #34d399;
        border-radius: 50%;
        display: inline-block;
    }

    .chatbot-header-actions {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .chatbot-action-btn {
        background: transparent;
        border: none;
        color: #ffffff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0.85;
        transition: all 0.15s;
        font-size: 15px;
    }

    .chatbot-action-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        opacity: 1;
    }

    /* Messages Stream */
    .chatbot-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .bot-msg-row {
        display: flex;
        gap: 8px;
        align-items: flex-end;
        max-width: 90%;
    }

    .bot-msg-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #fff4ed;
        color: var(--primary);
        border: 1px solid #fed7aa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }

    .bot-msg-bubble {
        background: #ffffff;
        color: #1e293b;
        padding: 10px 14px;
        border-radius: 16px;
        border-bottom-left-radius: 4px;
        font-size: 13.5px;
        line-height: 1.55;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        white-space: pre-line;
    }

    .user-msg-row {
        align-self: flex-end;
        max-width: 85%;
    }

    .user-msg-bubble {
        background: linear-gradient(135deg, #f26a3d 0%, #ea580c 100%);
        color: #ffffff;
        padding: 10px 14px;
        border-radius: 16px;
        border-bottom-right-radius: 4px;
        font-size: 13.5px;
        line-height: 1.5;
        box-shadow: 0 2px 6px rgba(242, 106, 61, 0.25);
        word-break: break-word;
    }

    /* Product Cards inside Chatbot */
    .bot-products-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
        width: 100%;
    }

    .bot-product-card {
        display: flex;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        padding: 8px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .bot-product-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(242, 106, 61, 0.15);
    }

    .bot-product-img {
        width: 54px;
        height: 54px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .bot-product-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .bot-product-title {
        font-size: 12.5px;
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .bot-product-price {
        font-size: 12.5px;
        font-weight: 800;
        color: #e11d48;
    }

    .bot-product-location {
        font-size: 11px;
        color: #64748b;
    }

    /* Suggestion Chips */
    .chatbot-chips {
        padding: 6px 14px 10px;
        background: #ffffff;
        display: flex;
        gap: 6px;
        overflow-x: auto;
        border-top: 1px solid #f1f5f9;
        scrollbar-width: none;
    }
    .chatbot-chips::-webkit-scrollbar {
        display: none;
    }

    .bot-chip {
        padding: 5px 11px;
        font-size: 11.5px;
        font-weight: 600;
        background: #fff4ed;
        color: #c2410c;
        border: 1px solid #ffedd5;
        border-radius: 14px;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s;
    }

    .bot-chip:hover {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
    }

    /* Footer Input */
    .chatbot-footer {
        padding: 10px 14px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chatbot-footer input {
        flex: 1;
        height: 38px;
        padding: 0 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        transition: all 0.2s;
    }

    .chatbot-footer input:focus {
        background: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(242, 106, 61, 0.15);
    }

    .chatbot-send-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--primary);
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.15s;
        box-shadow: 0 3px 8px rgba(242, 106, 61, 0.3);
    }

    .chatbot-send-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
    }

    /* Typing Dots */
    .bot-typing {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 6px;
    }

    .bot-typing-dot {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typingDot 1.4s infinite ease-in-out both;
    }

    .bot-typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .bot-typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    @media (max-width: 480px) {
        .chatbot-window {
            bottom: 85px;
            right: 12px;
            width: calc(100vw - 24px);
            height: 520px;
        }
        .chatbot-trigger {
            bottom: 16px;
            right: 16px;
            width: 52px;
            height: 52px;
        }
        .chatbot-trigger-icon {
            font-size: 22px;
        }
    }
</style>

<script>
    const CHATBOT_STORAGE_KEY = 'chotot_chatbot_history_v1';
    let isChatbotOpen = false;

    // Tin nhắn chào mừng ban đầu
    const DEFAULT_BOT_GREETING = {
        role: 'bot',
        text: "Xin chào bạn! 👋 Tôi là **Trợ lý ảo Chợ Tốt**.\nTôi có thể giúp bạn tìm sản phẩm đồ cũ giá rẻ, hướng dẫn đăng tin hoặc tư vấn mẹo mua bán an toàn. Bạn cần hỗ trợ gì hôm nay?",
        products: []
    };

    // Khởi tạo Chatbot khi tải trang
    document.addEventListener('DOMContentLoaded', () => {
        loadChatbotHistory();
    });

    // Bật / tắt cửa sổ chatbot
    function toggleChatbot() {
        const windowEl = document.getElementById('chatbotWindow');
        isChatbotOpen = !isChatbotOpen;

        if (isChatbotOpen) {
            windowEl.classList.add('open');
            scrollBotToBottom();
            setTimeout(() => {
                const input = document.getElementById('chatbotInput');
                if (input) input.focus();
            }, 200);
        } else {
            windowEl.classList.remove('open');
        }
    }

    // Tải lịch sử chat từ sessionStorage
    function loadChatbotHistory() {
        const messagesEl = document.getElementById('chatbotMessages');
        if (!messagesEl) return;

        let history = [];
        try {
            const raw = sessionStorage.getItem(CHATBOT_STORAGE_KEY);
            if (raw) history = JSON.parse(raw);
        } catch (e) {}

        if (!history || history.length === 0) {
            history = [DEFAULT_BOT_GREETING];
            saveChatbotHistory(history);
        }

        renderAllBotMessages(history);
    }

    // Lưu lịch sử chat vào sessionStorage
    function saveChatbotHistory(history) {
        try {
            sessionStorage.setItem(CHATBOT_STORAGE_KEY, JSON.stringify(history.slice(-20))); // Giữ 20 tin nhắn gần nhất
        } catch (e) {}
    }

    // Vẽ danh sách tin nhắn
    function renderAllBotMessages(history) {
        const messagesEl = document.getElementById('chatbotMessages');
        if (!messagesEl) return;

        messagesEl.innerHTML = '';
        history.forEach(item => {
            if (item.role === 'user') {
                appendUserMessageDom(item.text);
            } else {
                appendBotMessageDom(item.text, item.products || []);
            }
        });
        scrollBotToBottom();
    }

    // Reset lịch sử hội thoại
    function resetChatbotHistory() {
        const history = [DEFAULT_BOT_GREETING];
        saveChatbotHistory(history);
        renderAllBotMessages(history);
        showToast('Đã làm mới cuộc hội thoại với Trợ lý!', 'success');
    }

    // Click vào gợi ý nhanh
    function handleChipClick(text) {
        const input = document.getElementById('chatbotInput');
        if (input) {
            input.value = text;
            handleSendBotMessage(new Event('submit'));
        }
    }

    // Gửi tin nhắn từ người dùng tới Bot
    async function handleSendBotMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatbotInput');
        if (!input) return;

        const text = input.value.trim();
        if (!text) return;

        input.value = '';

        // Hiển thị tin nhắn người dùng
        appendUserMessageDom(text);
        scrollBotToBottom();

        // Lưu vào history
        let history = getHistory();
        history.push({ role: 'user', text: text });
        saveChatbotHistory(history);

        // Hiển thị bong bóng bot đang trả lời (Typing)
        showBotTyping();

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('/api/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            removeBotTyping();

            if (!res.ok) throw new Error('Lỗi máy chủ');
            const data = await res.json();

            // Hiển thị phản hồi từ bot
            appendBotMessageDom(data.reply || 'Tôi đã nhận được câu hỏi.', data.products || []);
            scrollBotToBottom();

            // Cập nhật gợi ý nếu có
            if (data.suggestions && data.suggestions.length > 0) {
                updateBotChips(data.suggestions);
            }

            // Lưu vào history
            history.push({ role: 'bot', text: data.reply, products: data.products || [] });
            saveChatbotHistory(history);

        } catch (err) {
            console.error(err);
            removeBotTyping();
            appendBotMessageDom("Xin lỗi, hệ thống đang bận một chút. Bạn vui lòng thử lại sau nhé! 🙏", []);
            scrollBotToBottom();
        }
    }

    function appendUserMessageDom(text) {
        const area = document.getElementById('chatbotMessages');
        const div = document.createElement('div');
        div.className = 'user-msg-row';
        div.innerHTML = `<div class="user-msg-bubble">${escapeBotHtml(text)}</div>`;
        area.appendChild(div);
    }

    function appendBotMessageDom(text, products = []) {
        const area = document.getElementById('chatbotMessages');
        const div = document.createElement('div');
        div.className = 'bot-msg-row';

        let formattedText = escapeBotHtml(text)
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>');

        let productsHtml = '';
        if (products && products.length > 0) {
            productsHtml = `
                <div class="bot-products-list">
                    ${products.map(p => `
                        <a href="${p.url}" target="_blank" class="bot-product-card" title="Xem chi tiết ${escapeBotHtml(p.title)}">
                            <img src="${p.image}" alt="Product" class="bot-product-img" onerror="this.src='https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=200'">
                            <div class="bot-product-info">
                                <div class="bot-product-title">${escapeBotHtml(p.title)}</div>
                                <div class="bot-product-price">${p.price}</div>
                                <div class="bot-product-location"><i class="fa-solid fa-location-dot"></i> ${escapeBotHtml(p.province)}</div>
                            </div>
                        </a>
                    `).join('')}
                </div>
            `;
        }

        div.innerHTML = `
            <div class="bot-msg-avatar"><i class="fa-solid fa-robot"></i></div>
            <div class="bot-msg-bubble">
                ${formattedText}
                ${productsHtml}
            </div>
        `;
        area.appendChild(div);
    }

    function showBotTyping() {
        removeBotTyping();
        const area = document.getElementById('chatbotMessages');
        const div = document.createElement('div');
        div.className = 'bot-msg-row';
        div.id = 'botTypingElement';
        div.innerHTML = `
            <div class="bot-msg-avatar"><i class="fa-solid fa-robot"></i></div>
            <div class="bot-msg-bubble">
                <div class="bot-typing">
                    <span class="bot-typing-dot"></span>
                    <span class="bot-typing-dot"></span>
                    <span class="bot-typing-dot"></span>
                </div>
            </div>
        `;
        area.appendChild(div);
        scrollBotToBottom();
    }

    function removeBotTyping() {
        const el = document.getElementById('botTypingElement');
        if (el) el.remove();
    }

    function updateBotChips(chips) {
        const container = document.getElementById('chatbotChips');
        if (!container) return;
        container.innerHTML = chips.map(c => `
            <button type="button" class="bot-chip" onclick="handleChipClick('${escapeBotHtml(c)}')">${escapeBotHtml(c)}</button>
        `).join('');
    }

    function scrollBotToBottom() {
        const area = document.getElementById('chatbotMessages');
        if (area) {
            area.scrollTop = area.scrollHeight;
        }
    }

    function getHistory() {
        try {
            const raw = sessionStorage.getItem(CHATBOT_STORAGE_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function escapeBotHtml(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.innerText = str;
        return d.innerHTML;
    }
</script>

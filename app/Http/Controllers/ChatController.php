<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Trang hộp thư tin nhắn chính
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Lấy danh sách các cuộc trò chuyện của người dùng hiện tại
        $conversations = Conversation::where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)
                  ->orWhere('seller_id', $userId);
            })
            ->with(['buyer.profile', 'seller.profile', 'product', 'latestMessage'])
            ->orderByRaw('COALESCE(last_message_at, updated_at) DESC')
            ->get();

        $activeConversation = null;

        // Nếu có truyền conversation_id cụ thể
        if ($request->filled('conversation_id')) {
            $activeConversation = $conversations->firstWhere('id', (int) $request->conversation_id);
        }

        // Nếu có truyền product_id (nhấp từ trang chi tiết sản phẩm)
        if (!$activeConversation && $request->filled('product_id')) {
            $product = Product::with('user.profile')->find($request->product_id);

            if ($product && $product->user_id !== $userId) {
                // Tìm cuộc hội thoại đã có với người bán về sản phẩm này
                $activeConversation = Conversation::where('product_id', $product->id)
                    ->where(function ($q) use ($userId, $product) {
                        $q->where(function ($sub) use ($userId, $product) {
                            $sub->where('buyer_id', $userId)->where('seller_id', $product->user_id);
                        })->orWhere(function ($sub) use ($userId, $product) {
                            $sub->where('buyer_id', $product->user_id)->where('seller_id', $userId);
                        });
                    })
                    ->first();

                // Nếu chưa có thì khởi tạo hội thoại mới
                if (!$activeConversation) {
                    $activeConversation = Conversation::create([
                        'buyer_id' => $userId,
                        'seller_id' => $product->user_id,
                        'product_id' => $product->id,
                        'last_message' => null,
                        'last_message_at' => now(),
                        'buyer_unread_count' => 0,
                        'seller_unread_count' => 0,
                    ]);

                    // Nạp lại danh sách conversations
                    $conversations->prepend($activeConversation->load(['buyer.profile', 'seller.profile', 'product', 'latestMessage']));
                }
            }
        }

        // Nếu chưa chọn conversation nào và danh sách có dữ liệu, chọn hội thoại đầu tiên
        if (!$activeConversation && $conversations->isNotEmpty()) {
            $activeConversation = $conversations->first();
        }

        $messages = collect();
        if ($activeConversation) {
            // Đánh dấu đã đọc
            $activeConversation->markAsReadFor($userId);

            // Nạp tin nhắn
            $messages = $activeConversation->messages()
                ->with('sender.profile')
                ->get();
        }

        return view('chat.index', compact('conversations', 'activeConversation', 'messages'));
    }

    /**
     * Bắt đầu cuộc trò chuyện với người bán từ nút "Chat với người bán"
     */
    public function start(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'initial_message' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        if ($product->user_id === $userId) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không thể chat với chính mình trên tin đăng của bạn.',
                ], 422);
            }
            return back()->with('error', 'Bạn không thể chat với chính mình trên tin đăng của bạn.');
        }

        // Tìm cuộc trò chuyện sẵn có hoặc tạo mới
        $conversation = Conversation::firstOrCreate(
            [
                'buyer_id' => $userId,
                'seller_id' => $product->user_id,
                'product_id' => $product->id,
            ],
            [
                'last_message' => null,
                'last_message_at' => now(),
                'buyer_unread_count' => 0,
                'seller_unread_count' => 0,
            ]
        );

        // Gửi tin nhắn mở đầu nếu có
        if ($request->filled('initial_message')) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $userId,
                'message' => $request->initial_message,
                'is_read' => false,
            ]);

            $conversation->recordNewMessage($userId, $request->initial_message);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'redirect_url' => route('chat.index', ['conversation_id' => $conversation->id]),
            ]);
        }

        return redirect()->route('chat.index', ['conversation_id' => $conversation->id]);
    }

    /**
     * API: Lấy danh sách các cuộc hội thoại (dùng cho AJAX / Polling)
     */
    public function getConversations()
    {
        $userId = Auth::id();

        $conversations = Conversation::where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)
                  ->orWhere('seller_id', $userId);
            })
            ->with(['buyer.profile', 'seller.profile', 'product'])
            ->orderByRaw('COALESCE(last_message_at, updated_at) DESC')
            ->get();

        $data = $conversations->map(function ($c) use ($userId) {
            $otherUser = $c->getOtherUser($userId);
            $unread = $c->getUnreadCount($userId);

            return [
                'id' => $c->id,
                'other_user' => [
                    'id' => $otherUser->id ?? null,
                    'name' => $otherUser->profile->name ?? ($otherUser->sdt ?? ($otherUser->email ?? 'Người dùng')),
                    'avatar' => $otherUser ? $otherUser->avatar_url : null,
                ],
                'last_message' => $c->last_message ?: 'Chưa có tin nhắn nào',
                'last_message_time' => $c->last_message_at ? $c->last_message_at->diffForHumans() : '',
                'unread_count' => $unread,
                'product' => $c->product ? [
                    'id' => $c->product->id,
                    'title' => $c->product->title,
                    'price' => $c->product->formatted_price,
                    'image' => $c->product->display_image,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'conversations' => $data,
        ]);
    }

    /**
     * API: Lấy danh sách tin nhắn của 1 cuộc hội thoại
     */
    public function getMessages($id)
    {
        $userId = Auth::id();
        $conversation = Conversation::with(['product', 'buyer.profile', 'seller.profile'])
            ->findOrFail($id);

        // Bảo mật: Người dùng phải là một trong 2 người tham gia
        if ($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId) {
            return response()->json(['success' => false, 'message' => 'Không có quyền truy cập.'], 403);
        }

        // Đánh dấu đã đọc
        $conversation->markAsReadFor($userId);

        $messages = $conversation->messages()
            ->with('sender.profile')
            ->get()
            ->map(function ($m) use ($userId) {
                return [
                    'id' => $m->id,
                    'sender_id' => $m->sender_id,
                    'is_me' => $m->sender_id === $userId,
                    'message' => $m->message,
                    'formatted_time' => $m->formatted_time,
                    'created_at' => $m->created_at
                        ->timezone('Asia/Ho_Chi_Minh')
                        ->format('Y-m-d H:i:s'),
                ];
            });

        $otherUser = $conversation->getOtherUser($userId);

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'other_user' => [
                    'id' => $otherUser->id ?? null,
                    'name' => $otherUser->profile->name ?? ($otherUser->sdt ?? ($otherUser->email ?? 'Người dùng')),
                    'avatar' => $otherUser ? $otherUser->avatar_url : null,
                ],
                'product' => $conversation->product ? [
                    'id' => $conversation->product->id,
                    'title' => $conversation->product->title,
                    'price' => $conversation->product->formatted_price,
                    'image' => $conversation->product->display_image,
                    'url' => route('products.show', $conversation->product->id),
                    'province' => $conversation->product->province,
                ] : null,
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * API: Gửi tin nhắn mới trong cuộc hội thoại
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $userId = Auth::id();
        $conversation = Conversation::findOrFail($id);

        // Kiểm tra quyền
        if ($conversation->buyer_id !== $userId && $conversation->seller_id !== $userId) {
            return response()->json(['success' => false, 'message' => 'Không có quyền.'], 403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Cập nhật thông tin tin nhắn cuối cùng và tăng số tin chưa đọc cho bên kia
        $conversation->recordNewMessage($userId, $request->message);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'is_me' => true,
                'message' => $message->message,
                'formatted_time' => $message->formatted_time,
                'created_at' => $message->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * API: Lấy tổng số tin nhắn chưa đọc của người dùng hiện tại (cập nhật Header)
     */
    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['unread_count' => 0]);
        }

        return response()->json([
            'unread_count' => Auth::user()->totalUnreadMessagesCount(),
        ]);
    }
}

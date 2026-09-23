<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test Chatbot API responses
     */
    public function test_chatbot_greeting_response(): void
    {
        $response = $this->postJson('/api/chatbot/message', [
            'message' => 'Xin chào',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'reply',
                'suggestions',
                'products',
            ]);

        $this->assertStringContainsString('Trợ lý ảo Chợ Tốt', $response->json('reply'));
    }

    public function test_chatbot_safety_tips(): void
    {
        $response = $this->postJson('/api/chatbot/message', [
            'message' => 'Làm sao để mua hàng an toàn không bị lừa đảo?',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('AN TOÀN', $response->json('reply'));
    }

    public function test_chatbot_how_to_post_ad(): void
    {
        $response = $this->postJson('/api/chatbot/message', [
            'message' => 'Hướng dẫn tôi cách đăng tin',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('ĐĂNG TIN', $response->json('reply'));
    }

    /**
     * Test User-to-User Chat APIs
     */
    public function test_user_chat_workflow(): void
    {
        // Tạo 2 user đúng schema của dự án
        $buyer = User::create([
            'email' => 'buyer@example.com',
            'sdt' => '0901234567',
            'password' => bcrypt('password123'),
        ]);

        $seller = User::create([
            'email' => 'seller@example.com',
            'sdt' => '0907654321',
            'password' => bcrypt('password123'),
        ]);

        // Tạo cuộc trò chuyện
        $conversation = Conversation::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'buyer_unread_count' => 0,
            'seller_unread_count' => 0,
        ]);

        // Gửi tin nhắn từ Buyer
        $sendResponse = $this->actingAs($buyer)->postJson("/api/chat/conversations/{$conversation->id}/messages", [
            'message' => 'Xin chào người bán, sản phẩm còn không ạ?',
        ]);

        $sendResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $buyer->id,
            'message' => 'Xin chào người bán, sản phẩm còn không ạ?',
        ]);

        // Seller lấy danh sách tin nhắn (và tự động đánh dấu đã đọc)
        $msgResponse = $this->actingAs($seller)->getJson("/api/chat/conversations/{$conversation->id}/messages");
        $msgResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        // Kiểm tra unread count API
        $unreadResponse = $this->actingAs($seller)->getJson('/api/chat/unread-count');
        $unreadResponse->assertStatus(200)
            ->assertJsonStructure(['unread_count']);
    }

    public function test_chatbot_product_search(): void
    {
        $seller = User::create([
            'email' => 'seller_prod@example.com',
            'sdt' => '0912345678',
            'password' => bcrypt('password123'),
        ]);

        $category = \App\Models\Category::create([
            'name' => 'Điện thoại',
            'slug' => 'dien-thoai',
        ]);

        $product = Product::create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'title' => 'iPhone 13 Pro Max 128GB xanh lá',
            'price' => 14500000,
            'description' => 'Máy đẹp nguyên zin 99%, pin 88%',
            'province' => 'TP. Hồ Chí Minh',
            'phone' => '0912345678',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/chatbot/message', [
            'message' => 'Tìm giúp tôi iphone 13',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['reply', 'products', 'suggestions']);

        $products = $response->json('products');
        $this->assertNotEmpty($products);
        $this->assertEquals($product->id, $products[0]['id']);
    }

    public function test_unauthorized_user_cannot_view_others_conversation(): void
    {
        $user1 = User::create(['email' => 'u1@example.com', 'sdt' => '0910000001', 'password' => bcrypt('123456')]);
        $user2 = User::create(['email' => 'u2@example.com', 'sdt' => '0910000002', 'password' => bcrypt('123456')]);
        $stranger = User::create(['email' => 'u3@example.com', 'sdt' => '0910000003', 'password' => bcrypt('123456')]);

        $conv = Conversation::create([
            'buyer_id' => $user1->id,
            'seller_id' => $user2->id,
        ]);

        // Stranger tries to get messages
        $res = $this->actingAs($stranger)->getJson("/api/chat/conversations/{$conv->id}/messages");
        $res->assertStatus(403);
    }
}

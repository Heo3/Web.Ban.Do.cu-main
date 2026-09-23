<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\GoogleController;

// Trang chủ & Danh sách sản phẩm
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/danh-muc/{slug}', function ($slug) {
    return redirect()->route('home', ['category' => $slug]);
})->name('category.show');

// Chi tiết sản phẩm
Route::get('/san-pham/{id}', [ProductController::class, 'show'])->name('products.show');

// Yêu thích tin đăng (hỗ trợ ajax / form post)
Route::post('/san-pham/{id}/favorite', [ProductController::class, 'toggleFavorite'])->name('products.favorite');

// Đăng nhập / Đăng ký trang độc lập
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// Các chức năng yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    // Cập nhật thông tin cá nhân
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Đăng tin
    Route::get('/dang-tin', [ProductController::class, 'create'])->name('products.create');
    Route::post('/dang-tin', [ProductController::class, 'store'])->name('products.store');

    // Sửa tin
    Route::get('/san-pham/{id}/chinh-sua', [ProductController::class, 'edit'])->name('products.edit');
    Route::match(['put', 'post'], '/san-pham/{id}/chinh-sua', [ProductController::class, 'update'])->name('products.update');

    // Xóa tin
    Route::delete('/san-pham/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/san-pham/{id}/xoa', [ProductController::class, 'destroy']);

    // Đổi trạng thái (đã bán / đang bán)
    Route::post('/san-pham/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

    // Quản lý tin cá nhân & Tin yêu thích
    Route::get('/tin-da-dang', [ProductController::class, 'myProducts'])->name('products.my');
    Route::get('/yeu-thich', [ProductController::class, 'favorites'])->name('products.favorites');

    // Tin nhắn & Trò chuyện giữa người dùng (Chat User-to-User)
    Route::get('/tin-nhan', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/tin-nhan/start', [ChatController::class, 'start'])->name('chat.start');
    Route::get('/api/chat/conversations', [ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('/api/chat/conversations/{id}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/api/chat/conversations/{id}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/api/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unreadCount');
});

// Chatbot Trợ lý ảo AI (cho phép cả khách vãng lai và thành viên)
Route::post('/api/chatbot/message', [ChatbotController::class, 'reply'])->name('chatbot.reply');

// Khu vực Quản trị hệ thống (Chỉ dành cho Admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard.index');

    // Quản lý bài viết / tin đăng
    Route::get('/products', [\App\Http\Controllers\AdminController::class, 'products'])->name('products');
    Route::post('/products/{id}/status', [\App\Http\Controllers\AdminController::class, 'toggleProductStatus'])->name('products.status');
    Route::delete('/products/{id}', [\App\Http\Controllers\AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::post('/products/{id}/delete', [\App\Http\Controllers\AdminController::class, 'deleteProduct']);

    // Quản lý tài khoản
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('users.toggleStatus');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{id}/delete', [\App\Http\Controllers\AdminController::class, 'deleteUser']);
});

// Route Xác minh Email (chỉ yêu cầu đăng nhập 'auth')
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [\App\Http\Controllers\Auth\VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});
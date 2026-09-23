<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Bảng điều khiển quản trị tổng quan
     */
    public function dashboard()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $soldProducts = Product::where('status', 'sold')->count();
        $hiddenProducts = Product::where('status', 'hidden')->count();
        $totalUsers = User::count();
        $totalCategories = Category::count();

        // Top 5 tin xem nhiều nhất
        $topViewedProducts = Product::with(['category', 'user.profile'])
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // 5 tin đăng mới nhất
        $recentProducts = Product::with(['category', 'user.profile'])
            ->latest()
            ->take(5)
            ->get();

        // 5 thành viên mới đăng ký
        $recentUsers = User::with('profile')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'soldProducts',
            'hiddenProducts',
            'totalUsers',
            'totalCategories',
            'topViewedProducts',
            'recentProducts',
            'recentUsers'
        ));
    }

    /**
     * Quản lý tất cả bài viết / tin đăng
     */
    public function products(Request $request)
    {
        $query = Product::with(['category', 'user.profile']);

        // Tìm kiếm
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%")
                         ->orWhere('sdt', 'like', "%{$search}%");
                  });
            });
        }

        // Lọc trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Lọc danh mục
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products', compact('products', 'categories'));
    }

    /**
     * Đổi trạng thái bài viết (Đang bán / Đã bán / Tạm ẩn)
     */
    public function toggleProductStatus($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $newStatus = $request->input('status');

        if (!in_array($newStatus, ['active', 'sold', 'hidden'])) {
            $newStatus = $product->status === 'active' ? 'hidden' : 'active';
        }

        $product->update(['status' => $newStatus]);

        $statusNames = [
            'active' => 'Đang hiển thị',
            'sold' => 'Đã bán',
            'hidden' => 'Tạm ẩn'
        ];

        return back()->with('success', "Đã đổi trạng thái tin [#{$product->id}] thành: " . ($statusNames[$newStatus] ?? $newStatus));
    }

    /**
     * Xóa bài viết vi phạm
     */
    public function deleteProduct($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Xóa ảnh
        if ($product->image && !Str::startsWith($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $img) {
            if (!Str::startsWith($img->image_path, 'http')) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $product->delete();

        return back()->with('success', "Đã xóa bài viết [#{$id}] vĩnh viễn.");
    }

    /**
     * Quản lý tất cả tài khoản người dùng
     */
    public function users(Request $request)
    {
        $query = User::with('profile')->withCount('products');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('sdt', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Khóa hoặc mở khóa tài khoản
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản quản trị của chính mình!');
        }

        $newStatus = ($user->status ?? 'active') === 'active' ? 'banned' : 'active';
        $user->update(['status' => $newStatus]);

        $msg = $newStatus === 'banned' 
            ? "Đã tạm KHÓA tài khoản {$user->email} ({$user->sdt})." 
            : "Đã MỞ KHÓA tài khoản {$user->email} ({$user->sdt}).";

        return back()->with('success', $msg);
    }

    /**
     * Xóa tài khoản người dùng
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản quản trị của chính mình!');
        }

        $email = $user->email ?? $user->sdt;
        $user->delete();

        return back()->with('success', "Đã xóa vĩnh viễn tài khoản [{$email}].");
    }
}

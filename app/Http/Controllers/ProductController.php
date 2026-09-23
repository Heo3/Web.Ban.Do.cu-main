<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Trang chủ / Danh sách sản phẩm kèm tìm kiếm & lọc
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with(['category', 'user.profile', 'favorites'])->active();

        // Tìm kiếm theo từ khóa
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục (slug hoặc id)
        $categorySlug = $request->input('category');
        $selectedCategory = null;
        if (!empty($categorySlug)) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            } elseif (is_numeric($categorySlug)) {
                $query->where('category_id', $categorySlug);
                $selectedCategory = Category::find($categorySlug);
            }
        }

        // Lọc theo tỉnh / thành phố (case‑insensitive)
        $province = $request->input('province');
        if (!empty($province)) {
            $province = trim($province);
            $query->whereRaw('LOWER(`province`) LIKE ?', ['%' . strtolower($province) . '%']);
        }

        // Sắp xếp
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'views':
                $query->orderBy('views_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('index', compact(
            'products',
            'categories',
            'selectedCategory',
            'search',
            'province',
            'sort'
        ));
    }

    /**
     * Chi tiết tin đăng
     */
    public function show($id)
    {
        $product = Product::with(['user.profile', 'category', 'images', 'favorites'])
            ->findOrFail($id);

        // Tăng lượt xem
        $product->increment('views_count');

        // Tin liên quan cùng danh mục
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        $isFavorited = Auth::check() ? $product->isFavoritedBy(Auth::user()) : false;

        return view('products.show', compact('product', 'relatedProducts', 'isFavorited'));
    }

    /**
     * Giao diện đăng tin mới
     */
    public function create()
    {
        $categories = Category::all();
        $user = Auth::user();

        return view('products.create', compact('categories', 'user'));
    }

    /**
     * Xử lý lưu tin đăng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'province' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề tin đăng',
            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm',
            'price.required' => 'Vui lòng nhập giá bán',
            'description.required' => 'Vui lòng nhập mô tả chi tiết',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ',
        ]);

        $mainImagePath = null;
        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                if ($index === 0) {
                    $mainImagePath = $path;
                }
                $uploadedImages[] = $path;
            }
        }

        $product = Product::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'price' => $request->price,
            'description' => $request->description,
            'image' => $mainImagePath,
            'province' => $request->province,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        // Lưu danh sách hình ảnh vào product_images
        foreach ($uploadedImages as $img) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $img,
            ]);
        }

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Đăng tin thành công! Tin của bạn đã được hiển thị.');
    }

    /**
     * Giao diện chỉnh sửa tin đăng
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa tin đăng này.');
        }

        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật tin đăng
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa tin đăng này.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'province' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'status' => 'nullable|in:active,sold,hidden',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'province' => $request->province,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => $request->input('status', $product->status),
        ];

        // Tải ảnh mới lên nếu có
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);

                // Nếu sản phẩm chưa có ảnh chính thì gán ảnh đầu tiên
                if (!$product->image && $index === 0) {
                    $data['image'] = $path;
                }
            }
        }

        $product->update($data);

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Cập nhật tin đăng thành công!');
    }

    /**
     * Xóa tin đăng
     */
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa tin đăng này.');
        }

        // Xóa các file ảnh nếu nằm trong public storage
        if ($product->image && !Str::startsWith($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $img) {
            if (!Str::startsWith($img->image_path, 'http')) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $product->delete();

        return redirect()->route('products.my')
            ->with('success', 'Đã xóa tin đăng thành công!');
    }

    /**
     * Đổi trạng thái tin (Đã bán / Đang bán)
     */
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);

        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $newStatus = $product->status === 'active' ? 'sold' : 'active';
        $product->update(['status' => $newStatus]);

        $msg = $newStatus === 'sold' ? 'Đã đánh dấu là ĐÃ BÁN.' : 'Đã mở lại tin đăng ĐANG BÁN.';

        return back()->with('success', $msg);
    }

    /**
     * Quản lý tin đăng cá nhân
     */
    public function myProducts(Request $request)
    {
        $status = $request->input('status', 'all');
        $query = Product::where('user_id', Auth::id())->with('category');

        if ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'sold') {
            $query->where('status', 'sold');
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('products.my_products', compact('products', 'status'));
    }

    /**
     * Yêu thích / Bỏ yêu thích tin đăng
     */
    public function toggleFavorite($id, Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'require_login' => true,
                    'message' => 'Vui lòng đăng nhập để lưu tin yêu thích.'
                ], 401);
            }
            return back()->with('error', 'Vui lòng đăng nhập để lưu tin yêu thích.');
        }

        $product = Product::findOrFail($id);
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $favorited = false;
            $message = 'Đã bỏ lưu tin đăng.';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $favorited = true;
            $message = 'Đã lưu tin vào danh sách yêu thích!';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Danh sách tin đã lưu yêu thích
     */
    public function favorites()
    {
        $user = Auth::user();
        $products = $user->favoriteProducts()
            ->with(['category', 'user.profile'])
            ->latest()
            ->paginate(12);

        return view('products.favorites', compact('products'));
    }
}

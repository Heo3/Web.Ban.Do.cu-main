<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductImage;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $sampleProducts = [
            [
                'cat' => 'dien-thoai',
                'title' => 'iPhone 13 Pro Max 128GB Quốc Tế pin 88% máy đẹp 99%',
                'price' => 13500000,
                'description' => 'Cần lên đời nên bán lại em iPhone 13 Pro Max màu Sierra Blue 128GB. Máy dùng kỹ từ đầu, dán PPF và ốp lưng suốt nên ngoại hình còn như mới. Pin zin 88% dùng trọn 1 ngày thoải mái. Mọi chức năng Face ID, camera, loa nghe gọi chuẩn chỉ không lỗi lầm. Phụ kiện có sạc cáp 20W zin. Mua bán tại nhà bao test 7 ngày.',
                'image' => 'https://images.unsplash.com/photo-1632661674596-df8be070a5c5?w=800&auto=format&fit=crop&q=80',
                'province' => 'TP. Hồ Chí Minh',
                'address' => 'Quận 10, TP. Hồ Chí Minh',
                'phone' => '0908123456',
                'views' => 142,
            ],
            [
                'cat' => 'dien-thoai',
                'title' => 'Samsung Galaxy S23 Ultra 256GB chính hãng SSVN còn bảo hành',
                'price' => 15200000,
                'description' => 'Bán Galaxy S23 Ultra bản 8/256GB màu Xanh Botanic chính hãng SSVN. Máy còn bảo hành Care+ đến cuối năm. Màn hình 120Hz siêu nét, camera zoom 100x cực nét, bút S-Pen mượt mà. Hộp và cáp theo máy đầy đủ.',
                'image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&auto=format&fit=crop&q=80',
                'province' => 'Hà Nội',
                'address' => 'Quận Cầu Giấy, Hà Nội',
                'phone' => '0912345678',
                'views' => 98,
            ],
            [
                'cat' => 'phuong-tien',
                'title' => 'Xe Honda Vision 2022 màu trắng xám chính chủ chạy 12.000km',
                'price' => 28500000,
                'description' => 'Chính chủ cần bán xe Honda Vision đời 2022 bản Đặc Biệt khóa Smartkey. Xe nữ đi làm gần nhà giữ gìn, thay dầu nhớt bảo dưỡng định kỳ tại Head Honda. Máy móc nguyên bản 100%, ốc tán sáng bóng, giấy tờ đầy đủ sang tên trong ngày.',
                'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
                'province' => 'TP. Hồ Chí Minh',
                'address' => 'Quận Bình Thạnh, TP. Hồ Chí Minh',
                'phone' => '0938765432',
                'views' => 310,
            ],
            [
                'cat' => 'phuong-tien',
                'title' => 'Xe máy Yamaha Grande 125cc Hybrid 2023 tiết kiệm xăng',
                'price' => 34000000,
                'description' => 'Xe Grande Hybrid màu đỏ mận, biển số thành phố đẹp. Động cơ BlueCore Hybrid êm ái, cốp siêu rộng 27 lít đựng đồ thoải mái. Xe còn đủ 2 chìa khóa thông minh, sổ bảo hành.',
                'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
                'province' => 'Đà Nẵng',
                'address' => 'Quận Hải Châu, Đà Nẵng',
                'phone' => '0977123987',
                'views' => 64,
            ],
            [
                'cat' => 'tu-lanh',
                'title' => 'Tủ lạnh Panasonic Inverter 255 lít làm đá nhanh tiết kiệm điện',
                'price' => 3800000,
                'description' => 'Gia đình đổi tủ lớn hơn nên thanh lý tủ lạnh Panasonic Inverter 255L. Tủ đang chạy rất êm, lạnh sâu, công nghệ Econavi tiết kiệm điện vượt trội. Khay kính chịu lực, ngăn rau củ giữ ẩm tốt. Bao thợ test thoải mái, hỗ trợ vận chuyển nội thành.',
                'image' => 'https://images.unsplash.com/photo-1584992236310-6edddc08acff?w=800&auto=format&fit=crop&q=80',
                'province' => 'Hà Nội',
                'address' => 'Quận Đống Đa, Hà Nội',
                'phone' => '0988456123',
                'views' => 85,
            ],
            [
                'cat' => 'dien-tu',
                'title' => 'Smart Tivi LG 4K 55 inch NanoCell giọng nói tiếng Việt mượt mà',
                'price' => 6200000,
                'description' => 'Cần bán Smart Tivi LG 55 inch dòng NanoCell màu sắc rực rỡ, độ phân giải 4K sắc nét. Có chuột bay Magic Remote điều khiển giọng nói tiếng Việt rất nhạy. Xem YouTube, Netflix cực mượt. Chân đế và remote đầy đủ.',
                'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&auto=format&fit=crop&q=80',
                'province' => 'TP. Hồ Chí Minh',
                'address' => 'Quận Gò Vấp, TP. Hồ Chí Minh',
                'phone' => '0903332211',
                'views' => 210,
            ],
            [
                'cat' => 'dien-tu',
                'title' => 'MacBook Air M1 2020 8GB/256GB Gray đẹp keng sạc ít lần',
                'price' => 13800000,
                'description' => 'MacBook Air M1 bản 8GB RAM, SSD 256GB màu Xám Không Gian. Máy chủ yếu lướt web làm văn phòng nhẹ nhàng, pin 94% sạc 60 lần. Màn hình Retina hiển thị siêu đẹp, loa to ấm. Đi kèm củ cáp sạc zin Type-C 30W.',
                'image' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=800&auto=format&fit=crop&q=80',
                'province' => 'Hà Nội',
                'address' => 'Quận Thanh Xuân, Hà Nội',
                'phone' => '0945678901',
                'views' => 450,
            ],
            [
                'cat' => 'noi-that',
                'title' => 'Bàn làm việc chữ L chân sắt sơn tĩnh điện kèm kệ sách',
                'price' => 750000,
                'description' => 'Dọn văn phòng dư chiếc bàn chữ L mặt gỗ MDF phủ Melamine chống trầy chống nước. Khung sắt hộp chắc chắn chịu lực cao. Kích thước 140x120cm rộng rãi để 2 màn hình máy tính thoải mái.',
                'image' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=800&auto=format&fit=crop&q=80',
                'province' => 'TP. Hồ Chí Minh',
                'address' => 'Quận Tân Bình, TP. Hồ Chí Minh',
                'phone' => '0931234567',
                'views' => 115,
            ],
            [
                'cat' => 'noi-that',
                'title' => 'Sofa băng nỉ nhung cao cấp màu xám thanh lịch phòng khách',
                'price' => 2200000,
                'description' => 'Bộ ghế sofa dài 1m8 kèm 2 đôn nhỏ và gối ôm. Nệm mút D40 êm ái chống xẹp lún, vải nỉ nhung thoáng mát. Mới mua được 6 tháng còn rất mới, nay chuyển nhà nên nhượng lại giá rẻ.',
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&auto=format&fit=crop&q=80',
                'province' => 'Đà Nẵng',
                'address' => 'Quận Sơn Trà, Đà Nẵng',
                'phone' => '0905123789',
                'views' => 78,
            ],
            [
                'cat' => 'thu-cung',
                'title' => 'Mèo Golden Ny25 đực 3 tháng tuổi tiêm phòng sổ giun đầy đủ',
                'price' => 3500000,
                'description' => 'Bé mèo Golden mã màu Ny25 lông dày mắt xanh, tròn trĩnh mập mạp siêu quấn người. Đã tiêm ngừa 2 mũi và tẩy giun định kỳ, ăn hạt và vệ sinh đúng chỗ chậu cát. Tìm chủ yêu thương bé.',
                'image' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=800&auto=format&fit=crop&q=80',
                'province' => 'Hà Nội',
                'address' => 'Quận Ba Đình, Hà Nội',
                'phone' => '0966889900',
                'views' => 520,
            ],
            [
                'cat' => 'thoi-trang',
                'title' => 'Giày thể thao Nike Air Force 1 07 White size 42 chính hãng',
                'price' => 1450000,
                'description' => 'Đôi AF1 màu trắng huyền thoại order Nhật Bản full box. Đi được 2 lần còn trắng tinh tươm, đế gần như chưa mòn. Size 42 (26.5cm). Bao check fake trọn đời đền x10.',
                'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&auto=format&fit=crop&q=80',
                'province' => 'TP. Hồ Chí Minh',
                'address' => 'Quận 1, TP. Hồ Chí Minh',
                'phone' => '0909090909',
                'views' => 190,
            ],
            [
                'cat' => 'cho-tang',
                'title' => 'Tặng bộ sách giáo khoa và truyện tranh thiếu nhi cho bạn nào cần',
                'price' => 0,
                'description' => 'Con mình đã học xong nên tặng lại trọn bộ SGK lớp 6 và khoảng 30 cuốn truyện tranh Doraemon, Thần Đồng Đất Việt cho em nhỏ nào cần. Ai có hoàn cảnh khó khăn cứ nhắn mình gửi tặng nhé.',
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&auto=format&fit=crop&q=80',
                'province' => 'TP. Hồ Chí Minh',
                'address' => 'Quận Bình Tân, TP. Hồ Chí Minh',
                'phone' => '0918273645',
                'views' => 670,
            ],
            [
                'cat' => 'bat-dong-san',
                'title' => 'Căn hộ 2PN 2WC chung cư Sky Garden Phú Mỹ Hưng view công viên',
                'price' => 3100000000,
                'description' => 'Bán gấp căn hộ 71m2 gồm 2 phòng ngủ, 2 WC tại chung cư Sky Garden 3, Phú Mỹ Hưng, Quận 7. Nhà full nội thất cao cấp chỉ việc xách vali vào ở. Sổ hồng riêng sẵn sàng công chứng ngay.',
                'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&auto=format&fit=crop&q=80',
                'province' => 'TP. Hồ Chí Minh',
                'address' => 'Quận 7, TP. Hồ Chí Minh',
                'phone' => '0901239876',
                'views' => 380,
            ],
            [
                'cat' => 'do-dung-van-phong',
                'title' => 'Ghế xoay công thái học Ergonomic Sihoo M57 chống đau lưng',
                'price' => 1900000,
                'description' => 'Thanh lý ghế công thái học Sihoo M57 lưới toàn thân thoáng mát. Có đỡ thắt lưng điều chỉnh đa hướng, tựa đầu 2D, tay nâng hạ 3D. Ghế dùng êm, hỗ trợ cột sống ngồi làm việc cả ngày không mỏi.',
                'image' => 'https://images.unsplash.com/photo-1580481077190-7361356a15fa?w=800&auto=format&fit=crop&q=80',
                'province' => 'Hà Nội',
                'address' => 'Quận Hoàng Mai, Hà Nội',
                'phone' => '0978654321',
                'views' => 140,
            ],
            [
                'cat' => 'giai-tri',
                'title' => 'Đàn Guitar Acoustic Rosen G11 gỗ thịt tiếng vang tặng bao da',
                'price' => 1100000,
                'description' => 'Cây guitar Acoustic Rosen G11 mặt gỗ thông thịt, action êm tay không đau ngón. Đã thay bộ dây Elixir xịn tiếng vang ngân rất hay. Tặng kèm bao da 3 lớp, capo và phím gảy.',
                'image' => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800&auto=format&fit=crop&q=80',
                'province' => 'Cần Thơ',
                'address' => 'Quận Ninh Kiều, Cần Thơ',
                'phone' => '0939123456',
                'views' => 76,
            ],
        ];

        foreach ($sampleProducts as $index => $item) {
            $cat = $categories->get($item['cat']) ?? $categories->first();
            $user = $users[$index % $users->count()];

            $product = Product::create([
                'user_id' => $user->id,
                'category_id' => $cat->id,
                'title' => $item['title'],
                'price' => $item['price'],
                'description' => $item['description'],
                'image' => $item['image'],
                'province' => $item['province'],
                'address' => $item['address'],
                'phone' => $item['phone'],
                'status' => 'active',
                'views_count' => $item['views'],
                'created_at' => Carbon::now()->subHours(rand(1, 48)),
                'updated_at' => Carbon::now(),
            ]);

            // Add 2 extra gallery images for each product
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $item['image'],
            ]);
        }
    }
}

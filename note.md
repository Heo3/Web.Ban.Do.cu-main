thêm đã bán, thêm nhiều ảnh vào 1 sản phẩm, đăng nhập bằng google





| Bảng                 | Chức năng              |
| -------------------- | ---------------------- |
1| `users`              | Tài khoản người dùng   |
id, sdt,email, mk

1| `user_profiles`      | Thông tin cá nhân      |
id(key:users), name, địa chỉ

| `categories`         | Danh mục sản phẩm      |
id, tên danh mục sp

| `products`           | Tin đăng               |
id, id(key:products), gia, mô tả, link ảnh,

| `product_images`     | Hình ảnh sản phẩm      |

| `product_attributes` | Thuộc tính sản phẩm    |
| `locations`          | Tỉnh/thành, quận/huyện |
| `favorites`          | Tin đã yêu thích       |
1| `conversations`      | Cuộc trò chuyện (người mua & người bán) |
1| `messages`           | Tin nhắn trao đổi |
| `reports`            | Báo cáo tin đăng       |
| `orders`             | Giao dịch/đơn hàng     |



resources/
└── views/
    ├── index.blade.php       ← giao diện trang chủ
    ├── layouts/
    │   ├── header.blade.php
    │   ├── footer.blade.php
    │   └── app.blade.php->khung giao diện chung
    ├── login.blade.php       ← giao diện đăng nhập
    ├── register.blade.php    ← giao diện đăng ký
    ├── chat/
    │   └── index.blade.php   ← hộp thư tin nhắn trò chuyện giữa người mua & người bán
    ├── components/
    │   └── chatbot.blade.php ← widget trợ lý ảo AI thông minh 24/7
    └── products/
        ├── index.blade.php   ← danh sách sản phẩm
        ├── create.blade.php  ← thêm sản phẩm
        └── edit.blade.php    ← sửa sản phẩm
__Yêu thích
Danh mục:
Điện thoại, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/5010.png
Tủ lạnh, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/9030.png
NOXH, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/1001.png
bất động sản, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/1000.png
Đồ dùng văn phòng, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/8000.png
 Phương tiện,  https://static.chotot.com/storage/chotot-img/personalized-categories/v1/2000.png
Mẹ và bé, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/11000.png
Thực phẩm, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/7000.png
 thú cưng ,https://static.chotot.com/storage/chotot-img/personalized-categories/v1/12000.png
  điện tử, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/5000.png
   thời trang, https://static.chotot.com/storage/chapy-pro/newcats/v12/3000.png
    nội thất, https://static.chotot.com/storage/chotot-img/personalized-categories/v1/14000.png
     giải trí, src="https://static.chotot.com/storage/chapy-pro/newcats/v12/3000.png"
       cho tặng https://static.chotot.com/storage/chotot-img/personalized-categories/v1/giveaway.png

sản phẩm (id, id(danh mục), tên sp,hinh ảnh, mô tả, ngày đăng, địa chỉ(lấy bên users), id(người dùng))



---

# DANH SÁCH CÁC CHỨC NĂNG CẦN BỔ SUNG

*(Dựa trên `note.md`, mã nguồn `web.php` và nghiệp vụ thực tế của trang rao vặt / đồ cũ C2C)*

---

## 1. Hệ thống Báo cáo Vi phạm (Report System)

* **Hiện trạng trong dự án:**
* File `note.md` đã khai báo bảng `reports` trong cơ sở dữ liệu.
* Trong `web.php` chưa thiết lập các Route cho luồng báo cáo.


* **Chức năng cần bổ sung:**
* **Phía Người dùng (Client):**
* Thêm nút **"Báo cáo tin đăng"** tại trang chi tiết sản phẩm.
* Form báo cáo cho phép người dùng chọn các lý do phổ biến (Hàng giả / lừa đảo, Tin trùng lặp, Thông tin sai sự thật, Nội dung không lành mạnh...) kèm mô tả chi tiết.


* **Phía Quản trị (Admin):**
* Trang quản lý danh sách báo cáo vi phạm.
* Cho phép Admin duyệt xử lý: Khóa tin đăng, gửi cảnh báo hoặc khóa tài khoản vi phạm.





---

## 2. Hệ thống Đánh giá & Uy tín Người bán (Rating / Review)

* **Hiện trạng trong dự án:**
* Mô hình C2C (người dùng mua bán trực tiếp với người dùng) đòi hỏi độ tin cậy cao, tuy nhiên trang web hiện chưa có cơ chế kiểm tra và đánh giá người bán.


* **Chức năng cần bổ sung:**
* **Chức năng Đánh giá:**
* Cho phép người mua đánh giá người bán (Thang điểm 1 - 5 sao kèm lời bình luận).
* Điều kiện đánh giá: Sau khi giao dịch thành công hoặc sau khi kết thúc cuộc trò chuyện/thỏa thuận qua nhắn tin.


* **Hiển thị Độ uy tín:**
* Hiển thị điểm đánh giá trung bình và tổng số lượt đánh giá công khai trên Trang cá nhân/Hồ sơ người bán và Trang chi tiết sản phẩm.





---

## 3. Khôi phục Tài khoản & Quên Mật khẩu (Forgot / Reset Password)

* **Hiện trạng trong dự án:**
* Hệ thống hiện tại mới chỉ xử lý các luồng Đăng nhập (Login) và Đăng ký (Register) cơ bản.


* **Chức năng cần bổ sung:**
* **Quên mật khẩu:**
* Luồng "Quên mật khẩu" cho phép gửi email kèm liên kết / mã OTP để khôi phục mật khẩu.


* **Đổi mật khẩu:**
* Thêm chức năng **"Đổi mật khẩu"** trong Trang quản lý tài khoản cá nhân (yêu cầu nhập mật khẩu cũ và mật khẩu mới).





---

## 4. Bộ lọc Nâng cao & Sắp xếp Sản phẩm (Advanced Filter & Sorting)

* **Hiện trạng trong dự án:**
* Bộ lọc hiện tại chỉ hỗ trợ tìm kiếm cơ bản theo Từ khóa (Keyword) và Danh mục (Category).


* **Chức năng cần bổ sung:**
* **Bộ lọc Nâng cao (Advanced Filter):**
* **Theo giá:** Cho phép lọc theo khoảng giá (Giá từ `[ ... ]` đến `[ ... ]`).
* **Theo tình trạng:** Lọc theo trạng thái sản phẩm (Mới 100%, Đã sử dụng / Đồ cũ).


* **Bộ sắp xếp (Sorting):**
* Mới nhất / Cũ nhất.
* Giá từ thấp đến cao (Giá tăng dần).
* Giá từ cao đến thấp (Giá giảm dần).
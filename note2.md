# Tổng quan dự án & Danh sách các file đã lập trình (Backend)

## 1. Thư mục cấp cao nhất

- **app/** – Mã nguồn chính của ứng dụng (controllers, models, middleware, notifications).
- **bootstrap/** – Khởi tạo framework, autoloading và tải môi trường.
- **config/** – Các file cấu hình (database, mail, services Google OAuth, …).
- **database/** – Migration, seeder và factory cho schema cơ sở dữ liệu.
- **public/** – Tài nguyên công cộng (hình ảnh, CSS/JS đã biên dịch, thư mục liên kết `storage`, điểm vào `index.php`).
- **resources/** – Các file view (Blade template), ngôn ngữ và tài nguyên giao diện.
- **routes/** – Định nghĩa route (`web.php`).
- **storage/** – Log hệ thống, cache, file upload (hình ảnh sản phẩm, avatar người dùng).
- **tests/** – Các kiểm thử tự động (Feature tests & Unit tests).
- **vendor/** – Các thư viện của Composer (Laravel, Socialite, ...).

---

## 2. Chi tiết các File đã Lập trình (Coded Files)

### ⚙️ Controllers (`app/Http/Controllers/`)
- `ProductController.php` – Xử lý trang chủ, tìm kiếm, lọc danh mục, chi tiết tin đăng, đăng tin mới kèm nhiều ảnh, sửa, xóa, đổi trạng thái tin (*Đang bán/Đã bán*), quản lý tin cá nhân và tin yêu thích.
- `AuthController.php` – Xử lý đăng ký (qua SĐT hoặc Gmail), đăng nhập, đăng xuất và cập nhật hồ sơ cá nhân kèm tải ảnh đại diện.
- `GoogleController.php` – Xử lý đăng nhập bằng Google OAuth (`redirect`), nhận dữ liệu callback (`callback`), tự động tạo tài khoản mới hoặc liên kết với tài khoản sẵn có, đồng bộ ảnh đại diện Google, xác minh email tự động và kiểm tra tài khoản bị khóa.
- `Auth/VerificationController.php` – Xử lý trang thông báo xác minh email (`notice`), kích hoạt tài khoản qua link có chữ ký bảo mật (`verify`), và gửi lại email xác minh (`resend`).
- `ChatController.php` – Quản lý cuộc trò chuyện giữa người mua & người bán, gửi/nhận tin nhắn Ajax/Polling thời gian thực, lấy danh sách cuộc trò chuyện và đếm tin nhắn chưa đọc.
- `ChatbotController.php` – Xử lý phản hồi tự động của Trợ lý ảo AI 24/7 phục vụ giải đáp thắc mắc người dùng, tư vấn an toàn mua bán và tìm kiếm tin đăng.
- `AdminController.php` – Quản trị hệ thống (trang Dashboard thống kê số liệu, duyệt/ẩn/xóa tin đăng vi phạm, quản lý danh sách tài khoản người dùng và khóa/mở khóa tài khoản).

### 📧 Notifications (`app/Notifications/`)
- `VerifyEmailVietnamese.php` – Class thông báo gửi mail xác minh tài khoản dạng tiếng Việt với đường dẫn bảo mật (Signed URL 60 phút).

### 🛡️ Middleware (`app/Http/Middleware/`)
- `AdminMiddleware.php` – Kiểm tra quyền người dùng (`isAdmin`), bảo vệ toàn bộ các tuyến đường (routes) dành riêng cho Quản trị viên.

### 📦 Models (`app/Models/`)
- `User.php` – Model tài khoản người dùng: quản lý phân quyền role (`admin`/`user`), trạng thái (`active`/`banned`), mã liên kết `google_id`, accessor `avatar_url`, các mối quan hệ với Profile, Products, Favorites, Conversations.
- `UserProfile.php` – Model hồ sơ cá nhân: lưu họ tên, số điện thoại, địa chỉ, avatar; accessor `avatar_url` thông minh tự động nhận diện và hiển thị ảnh đại diện Google (`http/https`) hoặc ảnh tải lên cục bộ (`public/storage`).
- `Product.php` – Model tin đăng sản phẩm: giá tiền, mô tả, địa chỉ, tỉnh thành, số điện thoại, trạng thái (`active`, `sold`, `hidden`), lượt xem, accessor `display_image` chuẩn hóa hiển thị ảnh và mối quan hệ với User, Category, ProductImages, Favorites, Conversations.
- `ProductImage.php` – Model lưu danh sách các hình ảnh của tin đăng, accessor `url` chuẩn hóa đường dẫn ảnh.
- `Category.php` – Model danh mục sản phẩm (điện thoại, điện tử, xe cộ, gia dụng, ...).
- `Favorite.php` – Model lưu danh sách tin đăng yêu thích của từng người dùng.
- `Conversation.php` – Model quản lý cuộc trò chuyện giữa người mua và người bán.
- `Message.php` – Model lưu nội dung tin nhắn trao đổi (`message`), trạng thái đã đọc (`is_read`) và định dạng thời gian thân thiện.

### 🗄️ Database Migrations (`database/migrations/`)
- `0001_01_01_000000_create_users_table.php` – Khởi tạo bảng tài khoản `users` (hỗ trợ `sdt` có thể null cho người dùng đăng nhập qua Google hoặc Gmail).
- `0001_01_01_000001_create_cache_table.php` – Khởi tạo bảng cache hệ thống.
- `0001_01_01_000002_create_jobs_table.php` – Khởi tạo bảng hàng đợi jobs.
- `2026_09_07_103941_create_user_profiles_table.php` – Khởi tạo bảng thông tin chi tiết `user_profiles`.
- `2026_09_09_080515_create_categories_table.php` – Khởi tạo bảng danh mục sản phẩm `categories`.
- `2026_09_14_095521_create_products_table.php` – Khởi tạo bảng tin đăng `products`.
- `2026_09_15_000001_create_product_images_table.php` – Khởi tạo bảng hình ảnh sản phẩm `product_images`.
- `2026_09_15_000002_create_favorites_table.php` – Khởi tạo bảng tin yêu thích `favorites`.
- `2026_09_15_000003_add_role_and_status_to_users_table.php` – Thêm cột `role` (user/admin) và `status` (active/banned) vào bảng `users`.
- `2026_09_15_000004_create_conversations_table.php` – Khởi tạo bảng cuộc trò chuyện `conversations`.
- `2026_09_15_000005_create_messages_table.php` – Khởi tạo bảng tin nhắn `messages` (cột nội dung `message`).
- `2026_09_21_032715_create_password_reset_tokens_table.php` – Khởi tạo bảng token đặt lại mật khẩu `password_reset_tokens`.
- `2026_09_23_161620_add_google_id_to_users_table.php` – Bổ sung cột `google_id` vào bảng `users`.
- `2026_09_23_164800_make_sdt_nullable_and_fix_messages_table.php` – Cho phép cột `sdt` nhận giá trị null và đổi tên cột `content` thành `message` trong bảng `messages` cho CSDL hiện tại.

### 🛣️ Routes (`routes/`)
- `web.php` – Định nghĩa toàn bộ hệ thống URL/Route của ứng dụng:
  - Trang chủ & Lọc tin: `home`, `products.index`, `category.show`.
  - Chi tiết & Yêu thích: `products.show`, `products.favorite`.
  - Xác thực: `login`, `register`, `logout`, `google.login`, `google.callback`.
  - Cá nhân & Đăng tin: `products.create`, `products.store`, `products.edit`, `products.update`, `products.destroy`, `products.toggleStatus`, `products.my`, `products.favorites`, `profile.update`.
  - Chat C2C & Chatbot AI: `chat.index`, `chat.start`, `chat.conversations`, `chat.messages`, `chat.send`, `chat.unreadCount`, `chatbot.reply`.
  - Khu vực Quản trị Admin: `admin.dashboard`, `admin.products`, `admin.products.status`, `admin.products.delete`, `admin.users`, `admin.users.toggleStatus`, `admin.users.delete`.
  - Xác minh email: `verification.notice`, `verification.verify`, `verification.send`.

### 🎨 Views & Giao diện UI (`resources/views/`)
- `index.blade.php` – Trang chủ: slider/banner giới thiệu, lọc theo danh mục & tỉnh thành, tìm kiếm từ khóa, danh sách tin đăng kèm giá, địa điểm, badge Đã bán và nút tim yêu thích.
- `login.blade.php` – Trang đăng nhập độc lập: hỗ trợ đăng nhập bằng Gmail / SĐT, hiển thị thông báo lỗi và tích hợp nút **"Đăng nhập bằng Google"**.
- `register.blade.php` – Trang đăng ký tài khoản mới: kiểm tra hợp lệ thông tin, hiển thị lỗi và tích hợp nút **"Tiếp tục với Google"**.

- **`auth/`**:
  - `verify-email.blade.php` – Giao diện trang thông báo yêu cầu người dùng xác minh địa chỉ Gmail.

- **`layouts/`**:
  - `app.blade.php` – Layout khung tổng thể (tích hợp Font Awesome, Google Fonts Inter, Modal Auth, Chatbot Widget AI và script dùng chung).
  - `header.blade.php` & `footer.blade.php` – Thanh điều hướng đầu trang và chân trang.
  - `header/` – Gồm các thành phần con (`actions`, `auth-modal`, `auth-script`, `categories`, `logo`, `search`, `styles`) hiển thị thông tin tài khoản, avatar người dùng, nút đăng tin, số lượng tin yêu thích và huy hiệu tin nhắn chưa đọc.

- **`products/`**:
  - `show.blade.php` – Trang chi tiết tin đăng: bộ sưu tập ảnh chính kèm ảnh nhỏ (thumbnails), giá bán, mô tả, thông tin người bán kèm avatar, nút Chat với người bán, gọi điện và lưu tin.
  - `create.blade.php` – Giao diện tạo/đăng tin mới: chọn danh mục, tải lên nhiều hình ảnh, điền thông tin và địa chỉ.
  - `edit.blade.php` – Giao diện chỉnh sửa tin đăng: hiển thị ảnh hiện tại và cho phép tải bổ sung ảnh mới.
  - `my_products.blade.php` – Trang quản lý tin cá nhân người dùng: lọc theo trạng thái (đang hiển thị, đã bán, tạm ẩn), chỉnh sửa, đổi trạng thái và xóa tin.
  - `favorites.blade.php` – Trang danh sách các tin đăng người dùng đã lưu yêu thích.

- **`chat/`**:
  - `index.blade.php` – Giao diện hộp thư trao đổi tin nhắn trực tiếp giữa người mua và người bán: sidebar danh sách hội thoại kèm avatar đối tác, xem sản phẩm đang trao đổi, bong bóng tin nhắn và gửi tin nhắn AJAX tức thì.

- **`components/`**:
  - `chatbot.blade.php` – Widget Trợ lý ảo AI nổi ở góc phải màn hình phục vụ tư vấn 24/7.

- **`admin/`**:
  - `dashboard.blade.php` – Dashboard thống kê tổng số lượng bài viết, người dùng, tin đã bán và doanh số ước tính.
  - `products.blade.php` – Quản lý toàn bộ tin đăng trên hệ thống: xem, duyệt/chuyển trạng thái và xóa tin vi phạm.
  - `users.blade.php` – Quản lý toàn bộ danh sách tài khoản người dùng: hiển thị avatar, họ tên, email, SĐT, phân quyền và nút khóa/mở khóa tài khoản.

---

## 3. Các file cấu hình, lưu trữ & kiểm thử khác

- **artisan** – Lệnh CLI thực thi Laravel.
- **composer.json / composer.lock** – Cấu hình phụ thuộc và thư viện PHP (`laravel/framework`, `laravel/socialite`, ...).
- **.env** – File biến môi trường (Database MySQL, App Key, Google Client ID / Secret / Redirect URI).
- **config/services.php** – Cấu hình dịch vụ bên thứ ba bao gồm Google OAuth Socialite.
- **public/storage** – Thư mục liên kết Junction/Symlink tới `storage/app/public` (tạo bởi `php artisan storage:link`) phục vụ hiển thị ảnh sản phẩm và avatar người dùng.
- **tests/Feature/GoogleAuthTest.php** – Kiểm thử tự động tính năng xác thực Google OAuth.
- **tests/Feature/ChatTest.php** – Kiểm thử tự động tính năng Chat C2C và Chatbot AI.
- **tests/Feature/ExampleTest.php** – Kiểm thử tự động khả năng tải thành công của Trang chủ.
- **README.md** – Tài liệu dự án.
- **vite.config.js** – Cấu hình biên dịch Front-end.
- **note.md** – Ghi chú ý tưởng nghiệp vụ và mô hình cơ sở dữ liệu ban đầu.
- **note2.md** – Tài liệu kỹ thuật chi tiết cấu trúc thư mục và toàn bộ file backend đã lập trình.

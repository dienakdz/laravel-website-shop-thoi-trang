# 🛍️ DDien Fashion - Website Bán Hàng Thời Trang (Laravel)

<p align="center">
  <img src="public/assets/clients/img/logo.png" alt="DDien Logo" width="180">
</p>

<p align="center">
  <strong>Website Bán Hàng Thời Trang Đơn Giản</strong><br>
  <em>Đồ án môn học thời sinh viên, được hoàn thiện các chức năng CRUD cơ bản và giao diện với sự hỗ trợ của AI Agent.</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-8.75-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-4.6-7952B3?style=flat-square&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/AI%20Assisted-Antigravity-4285F4?style=flat-square&logo=google&logoColor=white" alt="AI Assisted">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

## 📖 Về Dự Án Này

> *"Dự án này vốn là bài tập lớn / đồ án hồi sinh viên khi tôi mới bắt đầu tập tành học lập trình web với PHP & Laravel. Lúc đó tôi mới làm được giao diện Client cơ bản, còn phần Admin và nhiều chỗ xử lý dữ liệu vẫn chưa xong, bị bỏ dở một thời gian.*
> 
> *Gần đây có các công cụ AI agent hỗ trợ lập trình, nên tôi tranh thủ dùng AI để dọn dẹp lại code, hoàn thiện nốt các chức năng CRUD cơ bản bên trang Admin (quản lý sản phẩm, đơn hàng, danh mục, banner), đổi lại tên shop và logo thành **DDien** cho gọn gàng, hoàn chỉnh để lưu lại làm kỷ niệm học tập và tham khảo."*

---

## 💻 Các Chức Năng Hiện Có

### 🛒 1. Phía Khách Hàng (Client)
- **Trang chủ & Banner**: Hiển thị banner chính, các sản phẩm mới và sản phẩm nổi bật.
- **Xem & Lọc Sản phẩm**:
  - Xem danh sách sản phẩm, lọc theo Danh mục và Thương hiệu.
  - Tìm kiếm theo tên sản phẩm.
  - Hỗ trợ tìm kiếm bằng giọng nói cơ bản (sử dụng Web Speech API của trình duyệt).
- **Chi tiết Sản phẩm**: Xem ảnh, giá, số lượng tồn kho và mô tả sản phẩm.
- **Giỏ hàng & Đặt hàng**:
  - Thêm, sửa số lượng, xóa sản phẩm trong giỏ.
  - Form đặt hàng (Checkout): Chọn Tỉnh/Thành phố, Quận/Huyện, Phường/Xã từ bảng địa chỉ Việt Nam có sẵn trong database.
- **Tài khoản**: Đăng ký, đăng nhập người dùng và xem danh sách đơn hàng đã đặt (`/ordered`).
- **Liên hệ**: Form gửi tin nhắn liên hệ về cho shop.

---

### ⚙️ 2. Phía Quản Trị Viên (Admin - CRUD cơ bản)
- **Bảng điều khiển (Dashboard)**: Xem nhanh tổng doanh thu, số đơn chờ xử lý, số sản phẩm sắp hết hàng, đơn hàng mới nhất.
- **Quản lý Đơn hàng**:
  - Danh sách đơn hàng, lọc theo trạng thái (chờ xử lý, đang giao, hoàn thành, hủy).
  - Popup xem nhanh thông tin đơn hàng và danh sách món hàng mà không cần chuyển trang.
  - Cập nhật trạng thái đơn hàng.
  - Trang xem và in hóa đơn đơn giản.
- **Quản lý Sản phẩm**: Xem danh sách, thêm, sửa, xóa sản phẩm, tải ảnh sản phẩm lên thư mục public.
- **Quản lý Danh mục & Thương hiệu**: Thêm, sửa, xóa danh mục và hãng (sử dụng modal đơn giản).
- **Quản lý Banner**: Thêm/sửa ảnh banner trang chủ và chọn 1 banner đang kích hoạt hiển thị.
- **Quản lý Khách hàng**: Xem danh sách người dùng, bật/tắt trạng thái tài khoản.
- **Hòm thư Liên hệ**: Xem tin nhắn khách gửi và mở link gửi mail phản hồi.

---

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: PHP 8.x, Laravel Framework 8.75 (mô hình MVC, Blade template, Query Builder).
- **Frontend**: HTML5, CSS3, JavaScript, jQuery, Bootstrap 4, Font Awesome, Themify Icons.
- **Database**: MySQL (chứa dữ liệu sản phẩm mẫu và bảng địa giới hành chính Việt Nam `devvn_*`).
- **AI Hỗ trợ**: Sử dụng AI Agent để fix lỗi, viết nốt các controller/view Admin còn thiếu, dọn CSS và tạo logo/banner thay thế template cũ.

---

## 📁 Cấu Trúc Thư Mục

```text
laravel-website-shop-thoi-trang/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── admin/             # Các Controller CRUD bên Admin
│   │   │   └── clients/           # Các Controller bên Client
│   │   └── Middleware/            # Kiểm tra đăng nhập Admin và Client
│   └── Helpers/Functions.php      # Helper functions (lấy danh mục, số lượng giỏ...)
├── public/
│   ├── assets/
│   │   ├── admin/                 # CSS/JS và ảnh upload cho Admin
│   │   └── clients/               # CSS/JS, logo và favicon cho Client
│   └── favicon.ico
├── resources/
│   └── views/
│       ├── admin/                 # Blade views trang Admin
│       └── clients/               # Blade views trang Client
├── routes/
│   └── web.php                    # Định tuyến toàn bộ ứng dụng
├── laravel-shop-thoi-trang.sql    # File dump CSDL mẫu
└── composer.json
```

---

## 🚀 Hướng Dẫn Cài Đặt & Chạy Local

### 1. Yêu Cầu
- PHP >= 8.0
- Composer
- MySQL (XAMPP, Laragon, hoặc MySQL Server)

### 2. Cài Đặt

**1. Clone dự án về máy:**
```bash
git clone https://github.com/your-username/laravel-website-shop-thoi-trang.git
cd laravel-website-shop-thoi-trang
```

**2. Cài dependencies:**
```bash
composer install
```

**3. Tạo file cấu hình môi trường:**
```bash
cp .env.example .env
```
Mở file `.env` chỉnh lại kết nối database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop_thoi_trang
DB_USERNAME=root
DB_PASSWORD=
```

**4. Tạo application key:**
```bash
php artisan key:generate
```

**5. Import Database:**
- Tạo database tên `shop_thoi_trang` trong MySQL (qua phpMyAdmin hoặc HeidiSQL).
- Import file `laravel-shop-thoi-trang.sql` có sẵn trong thư mục gốc vào database vừa tạo.

> *Lưu ý: Bảng `banner` có cột `is_active` (tinyint default 0) để đánh dấu banner nào đang hiển thị ở trang chủ.*

**6. Chạy máy chủ:**
```bash
php artisan serve
```
Mở trình duyệt vào: `http://127.0.0.1:8000`

---

## 🔑 Tài Khoản Đăng Nhập Mẫu

- **Tài khoản Admin**:
  - Link: `http://127.0.0.1:8000/admin/login`
  - Username: `dienak` (hoặc `admin`)
  - Password: `dienak` (hoặc `admin`)

- **Tài khoản Khách hàng**:
  - Link: `http://127.0.0.1:8000/tai-khoan`
  - Username: `dienak`
  - Password: `123456`

---

## 📝 Tác Giả

- **Nguyễn Minh Diện (DDien)**
- Đồ án cá nhân phục vụ mục đích học tập và làm quen với Laravel.

```text
WebClothesShoppingTheFox/
│
├── assets/
│   │
│   ├── css/
│   │   ├── main.css
│   │   ├── header.css
│   │   ├── footer.css
│   │   ├── product.css
│   │   ├── cart.css
│   │   ├── admin.css
│   │   └── auth.css
│   │
│   ├── js/
│   │   ├── main.js
│   │   ├── slider.js
│   │   ├── cart.js
│   │   ├── checkout.js
│   │   ├── auth.js
│   │   └── product.js
│   │
│   └── images/
│       ├── banners/
│       ├── products/
│       ├── avatars/
│       ├── icons/
│       └── logo/
│
├── config/
│   ├── database.php
│   └── config.php
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── sidebar.php
│
├── models/
│   ├── User.php
│   ├── Product.php
│   ├── Category.php
│   ├── Cart.php
│   ├── Order.php
│   ├── Wishlist.php
│   └── Outfit.php
│
├── controllers/
│   ├── AuthController.php
│   ├── ProductController.php
│   ├── CategoryController.php
│   ├── CartController.php
│   ├── CheckoutController.php
│   ├── OrderController.php
│   ├── WishlistController.php
│   └── OutfitController.php
│
├── pages/
│   │
│   ├── home.php
│   │
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   └── profile.php
│   │
│   ├── product/
│   │   ├── index.php
│   │   └── detail.php
│   │
│   ├── category/
│   │   └── index.php
│   │
│   ├── cart/
│   │   └── cart.php
│   │
│   ├── checkout/
│   │   └── checkout.php
│   │
│   ├── order/
│   │   ├── history.php
│   │   └── detail.php
│   │
│   ├── wishlist/
│   │   └── wishlist.php
│   │
│   └── outfit/
│       └── builder.php
│
├── admin/
│   │
│   ├── dashboard.php
│   │
│   ├── users/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   ├── products/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   ├── orders/
│   │   ├── index.php
│   │   └── detail.php
│   │
│   └── statistics/
│       └── revenue.php
│
├── uploads/
│   ├── products/
│   ├── avatars/
│   └── outfits/
│
├── database/
│   ├── schema.sql
│   └── seed.sql
│
├── index.php
└── README.md
```
# Cấu Trúc Dự Án & Tài Liệu Kỹ Thuật - Web Clothes Shopping "The Fox"

Tài liệu này mô tả chi tiết sơ đồ tổ chức thư mục, kiến trúc kỹ thuật và các thành phần chính của hệ thống website thương mại điện tử thời trang **The Fox**. Dự án được xây dựng trên nền tảng **PHP Full-stack (MVC hướng đối tượng rút gọn)** kết hợp với giao tiếp dữ liệu qua **APIs** (AJAX).

---

## 1. Sơ đồ cây thư mục (Directory Tree)

```text
WebClothesShoppingTheFox/
├── assets/                  # Tài nguyên tĩnh của hệ thống (Static Assets)
│   ├── css/                 # Các tệp định dạng giao diện stylesheet
│   │   ├── main.css         # Cấu hình biến màu sắc, font chữ global và reset styles
│   │   ├── header.css       # Định dạng giao diện thanh đầu trang
│   │   ├── footer.css       # Định dạng giao diện chân trang
│   │   ├── cart.css         # Giao diện trang giỏ hàng chính
│   │   ├── checkout.css     # Giao diện trang điền thông tin thanh toán
│   │   ├── sidebar.css      # Định dạng thanh biên phụ
│   │   ├── sidebarcart.css  # Giao diện giỏ hàng nhanh (Sidebar Cart)
│   │   ├── home.css         # Định dạng giao diện trang chủ
│   │   ├── category.css     # Định dạng giao diện trang danh mục sản phẩm
│   │   ├── product.css      # Định dạng giao diện trang chi tiết sản phẩm
│   │   └── admin.css        # Định dạng giao diện trang quản trị viên
│   │
│   ├── js/                  # Kịch bản tương tác động Javascript
│   │   ├── main.js          # Khởi tạo và liên kết các hoạt động giao diện chính
│   │   ├── cart.js          # Quản lý giỏ hàng Front-end (sử dụng LocalStorage)
│   │   ├── checkout.js      # Kiểm tra thông tin biểu mẫu và gửi dữ liệu đặt hàng lên API
│   │   ├── order.js         # Lịch sử đơn hàng, xem chi tiết & cập nhật trạng thái đơn hàng
│   │   ├── sidebar-cart.js  # Điều khiển đóng/mở và tăng giảm số lượng nhanh trên Sidebar
│   │   ├── animation.js     # Các hiệu ứng chuyển động, chuyển cảnh mượt mà
│   │   ├── scroll.js        # Logic cuộn trang (Back to top)
│   │   ├── format.js        # Các hàm tiện ích định dạng (định dạng tiền tệ, ngày tháng...)
│   │   ├── api.js           # Cấu hình kết nối và các hàm gọi API dùng chung
│   │   ├── product.js       # Xử lý các sự kiện chọn size, màu, phóng to ảnh ở trang chi tiết
│   │   └── slider.js        # Logic điều khiển banner trượt (Slide) ở trang chủ
│   │
│   └── images/              # Thư mục chứa hình ảnh giao diện tĩnh
│       ├── banners/         # Ảnh quảng cáo, ảnh nền slide trang chủ
│       ├── products/        # Ảnh mặc định của các mặt hàng quần áo
│       ├── icons/           # Logo App Store, Google Play, Favicon
│       └── logo/            # Logo chính thức của website The Fox
│
├── config/                  # Tệp cấu hình toàn hệ thống
│   ├── config.php           # Khai báo thông số môi trường, hằng số đường dẫn gốc
│   ├── database.php         # Mảng cấu hình kết nối MySQL Database
│   ├── db.php               # Lớp quản lý kết nối CSDL PDO (áp dụng Singleton Pattern)
│   └── session.php          # Quản lý phiên đăng nhập, phân quyền truy cập (Role-based access)
│
├── includes/                # Các thành phần giao diện dùng chung (Templates)
│   ├── header.php           # Phần mở đầu HTML, nhúng CSS và thẻ Meta SEO
│   ├── footer.php           # Phần chân trang và nhúng các tệp tin Javascript
│   ├── navbar.php           # Thanh điều hướng chính (Menu đa cấp, Megamenu)
│   └── sidebar.php          # Thanh biên cho các trang con hoặc trang quản trị
│
├── models/                  # Lớp xử lý thực thể dữ liệu (Data Models)
│   ├── Cart.php             # Xử lý truy vấn CSDL cho giỏ hàng của người dùng đăng nhập
│   ├── Order.php            # Xử lý các nghiệp vụ thêm, sửa, xem thông tin Đơn hàng
│   ├── OrderDetail.php      # Xử lý chi tiết sản phẩm nằm trong từng đơn hàng
│   ├── Product.php          # Quản lý dữ liệu sản phẩm, biến thể (màu sắc, kích thước)
│   ├── User.php             # Quản lý thông tin tài khoản người dùng, đăng ký, đăng nhập
│   └── Category.php         # Quản lý danh mục sản phẩm (Nam, Nữ, Trẻ em, Phụ kiện...)
│
├── controllers/             # Bộ điều khiển trung gian (Controllers)
│   ├── CartController.php   # Tiếp nhận và xử lý thêm, sửa số lượng, xóa mặt hàng khỏi giỏ hàng
│   ├── OrderController.php  # Xử lý tạo đơn hàng (Checkout), lấy lịch sử đơn hàng, cập nhật trạng thái đơn
│   ├── ProductController.php# Xử lý hiển thị danh sách sản phẩm, bộ lọc, chi tiết sản phẩm
│   └── UserController.php   # Xử lý logic đăng nhập, đăng ký tài khoản, sửa hồ sơ cá nhân
│
├── routes/                  # Điểm phân phối các yêu cầu API (Routing)
│   ├── cart.php             # Phân phối các yêu cầu API về giỏ hàng (AJAX)
│   └── order.php            # Phân phối các yêu cầu API về đặt hàng và quản lý đơn hàng
│
├── pages/                   # Giao diện hiển thị trang động (Views)
│   ├── home.php             # Giao diện trang chủ website
│   ├── category.php         # Giao diện trang danh mục sản phẩm kèm bộ lọc
│   ├── product-detail.php   # Giao diện trang chi tiết sản phẩm
│   ├── cart.php             # Giao diện trang giỏ hàng chính thức
│   ├── checkout.php         # Giao diện trang nhập thông tin giao hàng và chọn thanh toán
│   ├── invoice.php          # Giao diện hiển thị hóa đơn thành công và mã QR thanh toán (VietQR)
│   ├── order.php            # Giao diện trang lịch sử và quản lý đơn hàng cá nhân
│   ├── sidebarcart.php      # Giao diện khối giỏ hàng nhanh dạng trượt bên hông
│   ├── login.php            # Giao diện trang đăng nhập tài khoản
│   ├── register.php         # Giao diện trang đăng ký tài khoản mới
│   └── profile.php          # Giao diện trang cập nhật hồ sơ khách hàng
│
├── admin/                   # Bảng điều khiển quản trị hệ thống (Admin Panel)
│   ├── dashboard.php        # Trang thống kê tổng quan doanh thu, đơn hàng, người dùng
│   ├── products/            # Quản lý danh sách sản phẩm (Xem, thêm, sửa, xóa)
│   ├── categories/          # Quản lý danh sách danh mục (Xem, thêm, sửa, xóa)
│   ├── orders/              # Quản lý, duyệt đơn hàng và cập nhật tiến độ giao hàng
│   └── users/               # Quản lý danh sách tài khoản thành viên trong hệ thống
│
├── uploads/                 # Lưu trữ tài nguyên do người dùng tải lên hệ thống
│   ├── products/            # Lưu trữ hình ảnh sản phẩm do Admin đăng tải lên
│   └── avatars/             # Lưu trữ hình ảnh đại diện do người dùng cập nhật
│
├── database/                # Tài liệu và dữ liệu mẫu cơ sở dữ liệu
│   ├── connection.php       # Khởi tạo kết nối PDO trung gian cho API
│   ├── schema.sql           # Cấu trúc bảng MySQL của toàn bộ hệ thống
│   ├── seed_data.sql        # Dữ liệu thử nghiệm mẫu (sản phẩm, tài khoản mặc định)
│   └── fashionshop.sql      # Tệp cơ sở dữ liệu xuất bản chính thức của dự án
│
├── index.php                # Điểm đón đầu vào website (Tự động định hướng về trang chủ)
└── .htaccess                # Cấu hình rewrite URL thân thiện trên máy chủ Apache
```

---

## 2. Các Phân Hệ & Chức Năng Chính Của Hệ Thống

Hệ thống được chia thành các phân hệ chính hoạt động độc lập và đồng bộ thông qua cơ sở dữ liệu:

1. **Phân hệ Xác thực (Authentication):**
   - Hỗ trợ đăng ký tài khoản khách hàng mới, đăng nhập và bảo mật thông tin.
   - Quản lý phiên làm việc thông qua session (config/session.php), phân quyền truy cập giữa người dùng thông thường và quản trị viên (Admin).

2. **Phân hệ Sản phẩm & Danh mục (Product & Category):**
   - Quản lý danh mục sản phẩm đa cấp (Nam, Nữ, Trẻ em, Phụ kiện...).
   - Hiển thị danh sách sản phẩm trực quan, tích hợp bộ lọc tìm kiếm sản phẩm theo giá cả, màu sắc và kích cỡ.
   - Trang chi tiết hiển thị đầy đủ mô tả, hình ảnh sản phẩm phóng to và các biến thể sản phẩm.

3. **Phân hệ Giỏ hàng (Cart System):**
   - Giỏ hàng Front-end sử dụng bộ nhớ cục bộ LocalStorage để tối ưu tốc độ phản hồi giao diện.
   - Thanh giỏ hàng nhanh (Sidebar Cart) hỗ trợ người dùng xem nhanh, thay đổi số lượng hoặc xóa sản phẩm trực tiếp từ bất kỳ trang nào.
   - Trang giỏ hàng chính thức hiển thị đầy đủ danh sách mặt hàng đã chọn, giá tạm tính, chiết khấu và tổng số tiền thanh toán thực tế.

4. **Phân hệ Thanh toán & Đơn hàng (Checkout & Order):**
   - Cho phép điền thông tin người nhận, lựa chọn các hình thức vận chuyển và thanh toán phù hợp.
   - Tích hợp công nghệ thanh toán VietQR, tự động tạo mã QR động ngân hàng tương ứng với thông tin đơn hàng, số tiền cần chuyển khoản và mã giao dịch để hạn chế nhầm lẫn cho khách hàng.
   - Lưu trữ dữ liệu đơn hàng và chi tiết các mặt hàng vào cơ sở dữ liệu MySQL thông qua Transaction.

5. **Phân hệ Theo dõi đơn hàng (Order Tracking):**
   - Khách hàng có thể truy cập lịch sử mua hàng cá nhân để xem tiến độ xử lý đơn hàng.
   - Hiển thị đầy đủ thông tin trạng thái đơn hàng: Chờ xác nhận, Đang xử lý, Đang giao hàng, Đã hoàn thành hoặc Đã hủy.

6. **Phân hệ Quản trị (Admin Panel):**
   - Bảng điều khiển trực quan thống kê doanh số, số lượng đơn hàng và số lượng tài khoản mới.
   - Quản lý kho hàng (sản phẩm, phân loại danh mục).
   - Kiểm duyệt đơn hàng và cập nhật tình trạng giao nhận đơn.

---

## 3. Cấu Hình Hệ Thống

Khi triển khai hệ thống lên máy chủ thử nghiệm (localhost) hoặc máy chủ trực tuyến (production hosting):

1. **Cấu hình Cơ sở dữ liệu:**
   - Nhập tệp cơ sở dữ liệu schema.sql (hoặc fashionshop.sql) trong thư mục database/ vào hệ quản trị MySQL.
   - Chỉnh sửa thông số kết nối cơ sở dữ liệu trong tệp config/database.php (gồm địa chỉ máy chủ host, tên CSDL dbname, tên người dùng username và mật khẩu truy cập password).

2. **Cấu hình Đường dẫn:**
   - Mở tệp config/config.php để thiết lập biến hằng số đường dẫn gốc BASE_URL trỏ về tên miền hoặc thư mục chạy dự án (ví dụ: https://thefoxshop.com/ hoặc http://localhost/WebClothesShoppingTheFox/WebAI/).

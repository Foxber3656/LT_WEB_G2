-- Khởi tạo Cơ sở dữ liệu cho dự án Web Clothes Shopping - The Fox
CREATE DATABASE IF NOT EXISTS web_clothes_shopping CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE web_clothes_shopping;

-- 1. Bảng Users (Người dùng)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('guest', 'user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng Categories (Danh mục sản phẩm)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng Products (Sản phẩm)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL, -- Đường dẫn ảnh chính của sản phẩm
    category_id INT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bảng Carts (Giỏ hàng chính của User)
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE, -- Mỗi user chỉ có tối đa 1 giỏ hàng hoạt động
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Bảng Cart Items (Chi tiết sản phẩm trong giỏ hàng)
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    color VARCHAR(30) NOT NULL,
    size VARCHAR(10) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (cart_id, product_id, color, size) -- Không trùng sản phẩm cùng loại
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Bảng Orders (Đơn hàng)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(20) NOT NULL UNIQUE, -- Mã đơn hàng hiển thị (ví dụ: FOX123456)
    user_id INT NULL, -- Cho phép NULL nếu khách mua hàng không đăng nhập (guest)
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    shipping_method VARCHAR(50) NOT NULL, -- Tiêu chuẩn, Hỏa tốc
    shipping_fee DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    subtotal DECIMAL(10, 2) NOT NULL, -- Tổng tiền hàng
    final_total DECIMAL(10, 2) NOT NULL, -- Thành tiền cuối (Tạm tính + Ship - Giảm)
    payment_method VARCHAR(50) NOT NULL, -- COD, Chuyển khoản, MoMo
    payment_status VARCHAR(50) NOT NULL DEFAULT 'Chưa thanh toán',
    status ENUM('Chờ xác nhận', 'Đang xử lý', 'Đang giao hàng', 'Đã hoàn thành', 'Đã hủy') DEFAULT 'Chờ xác nhận',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Bảng Order Items (Chi tiết sản phẩm của đơn hàng đã đặt)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NULL, -- Để NULL nếu sản phẩm bị xóa sau này nhưng đơn hàng vẫn lưu
    product_name VARCHAR(255) NOT NULL, -- Lưu tên tại thời điểm mua
    color VARCHAR(30) NOT NULL,
    size VARCHAR(10) NOT NULL,
    price DECIMAL(10, 2) NOT NULL, -- Lưu giá bán tại thời điểm mua
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DỮ LIỆU ĐỂ TEST (Mock Data)
-- Chèn dữ liệu mẫu vào categories
INSERT INTO categories (name) VALUES ('Thời trang Nam'), ('Thời trang Nữ'), ('Trẻ Em'), ('Phụ Kiện')
ON DUPLICATE KEY UPDATE name=name;

-- Chèn dữ liệu mẫu vào products
INSERT INTO products (name, price, image, category_id, description) VALUES
('Áo kiểu Fox Summer', 790000.00, '../../assets/images/sp1.jpg', 2, 'Áo kiểu mùa hè năng động cho phái đẹp.'),
('Váy Fox Summer', 690000.00, '../../assets/images/sp2.jpg', 2, 'Đầm váy mùa hè quyến rũ và thanh lịch.'),
('Áo sơ mi Oxford Nam', 550000.00, '../../assets/images/sp3.jpg', 1, 'Sơ mi oxford lịch lãm phong cách công sở.'),
('Quần Short Kaki Casual', 420000.00, '../../assets/images/sp4.jpg', 1, 'Quần short kaki năng động thoải mái dạo phố.')
ON DUPLICATE KEY UPDATE name=name;

-- Chèn user mẫu cho việc test local (Mật khẩu test: 123456)
INSERT INTO users (id, fullname, email, phone, password, role) VALUES
(1, 'Khách hàng Demo', 'demo@thefox.com', '0912345678', '$2y$10$WdZ5M3yq4C.24jE.o4BfWuj1pX8V.Ff632lT9N9bB1y8U1e02UuUe', 'user'),
(2, 'Admin Manager', 'admin@thefox.com', '0987654321', '$2y$10$WdZ5M3yq4C.24jE.o4BfWuj1pX8V.Ff632lT9N9bB1y8U1e02UuUe', 'admin')
ON DUPLICATE KEY UPDATE email=email;

-- Tạo sẵn giỏ hàng cho user 1
INSERT INTO carts (user_id) VALUES (1) ON DUPLICATE KEY UPDATE user_id=user_id;

-- Tạo sẵn một số sản phẩm trong giỏ hàng của user 1 để test
INSERT INTO cart_items (cart_id, product_id, color, size, quantity) VALUES
(1, 1, 'Hồng Pastel', 'M', 1),
(1, 2, 'Đỏ', 'S', 2)
ON DUPLICATE KEY UPDATE quantity=quantity;

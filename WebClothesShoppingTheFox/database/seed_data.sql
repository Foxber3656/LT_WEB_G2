USE web_clothes_shopping;

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

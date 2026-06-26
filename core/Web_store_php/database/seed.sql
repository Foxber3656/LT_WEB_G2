-- Admin mặc định
Insert Into `Users` (`Ho`, `Ten`, `Email`, `Dien thoai`, `Mat khau`, `Vai tro`) Values
('The Fox', 'Admin', 'admin@gmail.com', '1234567890',
 '123456', 'Admin');

-- Khách hàng mẫu
Insert Into `Users` (`Ho`, `Ten`, `Email`, `Dien thoai`, `Ngay sinh`, `Dia chi`, `Mat khau`, `Vai tro`) Values
('Nguyễn', 'Minh An', 'an@email.com', '0123456789', '2005-05-15',
 '123 Nguyễn Văn Quá , Q12, TP.HCM',
 '123456789', 'Khach hang'),
('Trần', 'Thị Tài', 'tai@email.com', '0123456788', '2006-06-06',
 '456 Tô Ký, Q12, TP.HCM',
 '123456789', 'Khach hang');

-- Danh mục sản phẩm
Insert Into `Categories` (`Ten`, `Slug`, `Cha id`, `Thu tu`) Values
('Nam',        'nam',        Null, 1),
('Nữ',         'nu',         Null, 2),
('Trẻ Em',     'tre em',     Null, 3),
('Phụ Kiện',   'phu kien',   Null, 4);

Insert Into `Categories` (`Ten`, `Slug`, `Cha id`, `Thu tu`) Values
('Áo Nam',     'ao nam',     1, 1),
('Quần Nam',   'quan nam',   1, 2),
('Áo Nữ',      'ao nu',      2, 1),
('Đầm Nữ',     'dam nu',     2, 2),
('Áo Trẻ Em',  'ao tre em',  3, 1),
('Túi Xách',   'tui xach',   4, 1),
('Thắt Lưng',  'that lung',  4, 2);

-- Sản phẩm mẫu
Insert Into `Products` (`Danh muc id`, `Ten`, `Slug`, `Mo ta`, `Gia goc`, `Gia ban`, `Anh chinh`, `Noi bat`) Values
(7, 'Áo Thun Nữ Hồng Pastel Basic',
    'ao-thun-nu-hong-pastel-basic',
    'Áo thun cotton 100%, form rộng thoải mái, màu hồng pastel dịu dàng.',
    350000, 299000, 'uploads/products/sp1.jpg', 1),

(7, 'Áo Sơ Mi Nữ Trắng Thanh Lịch',
    'ao so mi nu trang thanh lich',
    'Áo sơ mi vải lụa mềm mại, phù hợp đi làm và dạo phố.',
    450000, 399000, 'uploads/products/sp2.jpg', 1),

(8, 'Đầm Maxi Hoa Nhí Mùa Hè',
    'dam maxi hoa nhi mua he',
    'Đầm maxi vải chiffon, họa tiết hoa nhỏ xinh, thoáng mát.',
    550000, 480000, 'uploads/products/sp3.jpg', 1),

(5, 'Áo Polo Nam Xanh Navy',
    'ao polo nam-xanh navy',
    'Áo polo cotton pique, cổ bẻ, màu xanh navy lịch sự.',
    380000, 320000, 'uploads/products/sp4.jpg', 0);

-- Biến thể sản phẩm
Insert Into `Product variants` (`San pham id`, `Mau sac`, `Ma mau`, `Size`, `So luong`, `Gia them`) Values
(1, 'Hồng Pastel', '#FFB6C1', 'S',  15, 0),
(1, 'Hồng Pastel', '#FFB6C1', 'M',  20, 0),
(1, 'Hồng Pastel', '#FFB6C1', 'L',  10, 0),
(1, 'Xám',         '#B0B0B0', 'S',  12, 0),
(1, 'Xám',         '#B0B0B0', 'M',  18, 0),
(2, 'Trắng', '#FFFFFF', 'S',  10, 0),
(2, 'Trắng', '#FFFFFF', 'M',  15, 0),
(2, 'Trắng', '#FFFFFF', 'L',   8, 0),
(2, 'Xanh Nhạt', '#ADD8E6', 'M', 10, 0),
(3, 'Hoa Nhí Trắng', '#FAFAFA', 'S', 8,  0),
(3, 'Hoa Nhí Trắng', '#FAFAFA', 'M', 12, 0),
(3, 'Hoa Nhí Trắng', '#FAFAFA', 'L', 6,  0),
(4, 'Xanh Navy', '#001F5B', 'M',  20, 0),
(4, 'Xanh Navy', '#001F5B', 'L',  15, 0),
(4, 'Xanh Navy', '#001F5B', 'XL', 10, 0),
(4, 'Trắng',     '#FFFFFF', 'M',  12, 0),
(4, 'Trắng',     '#FFFFFF', 'L',   8, 0);

-- Mã giảm giá mẫu
Insert Into `Discount codes` (`Ma`, `Loai`, `Gia tri`, `Don hang toi thieu`, `So luot dung`, `Ngay het han`) Values
('WELCOME10', 'Phan tram', 10, 200000, 100, '2026-12-31'),
('SALE50K',   'So tien',   50000, 300000,  50, '2026-09-30'),
('FOXVIP',    'Phan tram', 20, 500000,  20, '2026-12-31');
Create Database If Not Exists `Fashionshop`
Character Set Utf8mb4
Use `Fashionshop`;

-- ----------------------------------------------------------------
-- Bảng 1: Users - Tài khoản người dùng & admin
-- ----------------------------------------------------------------
Create Table `Users` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `Ho`            Varchar(50)  Not Null Comment 'Họ',
    `Ten`           Varchar(50)  Not Null Comment 'Tên',
    `Email`         Varchar(100) Not Null Unique,
    `Dien thoai`    Varchar(15)  Default Null,
    `Ngay sinh`     Date         Default Null,
    `Dia chi`       Varchar(200) Default Null,
    `Mat khau`      Varchar(20) Not Null Comment 'mật khẩu bình thường'  ,
    `Avatar`        Varchar(200) Default Null Comment 'Đường dẫn ảnh đại diện',
    `Vai tro`       Enum('Khach hang','Admin') Not Null Default 'Khach hang',
    `Trang thai`    Tinyint(1)   Not Null Default 1 Comment '1=Hoạt động, 0=Khóa',
    `Tao luc`       Datetime     Not Null Default Current_timestamp,
    `Cap nhat`      Datetime     Not Null Default Current_timestamp On Update Current_timestamp
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Tài khoản người dùng và quản trị viên';

-- ----------------------------------------------------------------
-- Bảng 2: Categories - Danh mục sản phẩm 
-- ----------------------------------------------------------------
Create Table `Categories` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `Ten`           Varchar(100) Not Null Comment 'Tên danh mục (Nam, Nữ, Trẻ em...)',
    `Slug`          Varchar(120) Not Null Unique Comment 'URL thân thiện',
    `Cha id`        Int Unsigned Default Null Comment 'Danh mục cha (Null = cấp 1)',
    `Thu tu`        Int          Not Null Default 0 Comment 'Thứ tự hiển thị',
    `Tao luc`       Datetime     Not Null Default Current_timestamp,
    Foreign Key (`Cha id`) References `Categories`(`Id`) On Delete Set Null
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Danh mục sản phẩm đa cấp';

-- ----------------------------------------------------------------
-- Bảng 3: Products - Sản phẩm
-- ----------------------------------------------------------------
Create Table `Products` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `Danh muc id`   Int Unsigned Not Null,
    `Ten`           Varchar(200) Not Null,
    `Slug`          Varchar(220) Not Null Unique,
    `Mo ta`         Text         Default Null,
    `Gia goc`       Decimal(12,0) Not Null Default 0 Comment 'Giá gốc (VNĐ)',
    `Gia ban`       Decimal(12,0) Not Null Default 0 Comment 'Giá bán sau giảm',
    `Anh chinh`     Varchar(255)  Default Null Comment 'Ảnh thumbnail chính',
    `Noi bat`       Tinyint(1)   Not Null Default 0 Comment '1=Sản phẩm nổi bật',
    `Trang thai`    Tinyint(1)   Not Null Default 1 Comment '1=Đang bán',
    `Tao luc`       Datetime     Not Null Default Current_timestamp,
    `Cap nhat`      Datetime     Not Null Default Current_timestamp On Update Current_timestamp,
    Foreign Key (`Danh muc id`) References `Categories`(`Id`) On Delete Restrict
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Sản phẩm thời trang';

-- ----------------------------------------------------------------
-- Bảng 4: Product images - Ảnh phụ của sản phẩm
-- ----------------------------------------------------------------
Create Table `Product images` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `San pham id`   Int Unsigned Not Null,
    `Duong dan`     Varchar(255) Not Null Comment 'Đường dẫn file ảnh',
    `Thu tu`        Int          Not Null Default 0,
    Foreign Key (`San pham id`) References `Products`(`Id`) On Delete Cascade
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Ảnh phụ của sản phẩm';

-- ----------------------------------------------------------------
-- Bảng 5: Product variants - Biến thể sản phẩm (màu sắc + size)
-- ----------------------------------------------------------------
Create Table `Product variants` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `San pham id`   Int Unsigned Not Null,
    `Mau sac`       Varchar(50)  Not Null Comment 'Tên màu: Hồng Pastel, Xám...',
    `Ma mau`        Varchar(10)  Default Null Comment 'Mã HEX màu: #FFAABB',
    `Size`          Varchar(10)  Not Null Comment 'S, M, L, XL, XXL...',
    `So luong`      Int Unsigned Not Null Default 0 Comment 'Tồn kho',
    `Gia them`      Decimal(12,0) Not Null Default 0 Comment 'Phụ phí thêm so với giá gốc',
    Foreign Key (`San pham id`) References `Products`(`Id`) On Delete Cascade
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Biến thể sản phẩm theo màu và size';

-- ----------------------------------------------------------------
-- Bảng 6: Carts - Giỏ hàng (cho user đã đăng nhập)
-- ----------------------------------------------------------------
Create Table `Carts` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `Nguoi dung id` Int Unsigned Not Null,
    `Bien the id`   Int Unsigned Not Null,
    `So luong`      Int Unsigned Not Null Default 1,
    `Tao luc`       Datetime     Not Null Default Current_timestamp,
    `Cap nhat`      Datetime     Not Null Default Current_timestamp On Update Current_timestamp,
    Unique Key `Uq cart item` (`Nguoi dung id`, `Bien the id`),
    Foreign Key (`Nguoi dung id`) References `Users`(`Id`) On Delete Cascade,
    Foreign Key (`Bien the id`)   References `Product variants`(`Id`) On Delete Cascade
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Giỏ hàng người dùng đã đăng nhập';

-- ----------------------------------------------------------------
-- Bảng 7: Orders - Đơn hàng
-- ----------------------------------------------------------------
Create Table `Orders` (
    `Id`              Int Unsigned Auto_increment Primary Key,
    `Ma don`          Varchar(20)  Not Null Unique Comment 'Mã đơn: FOX-20240001',
    `Nguoi dung id`   Int Unsigned Default Null Comment 'Null = mua không đăng nhập',
    `Ten nguoi nhan`  Varchar(100) Not Null,
    `Dien thoai`      Varchar(15)  Not Null,
    `Dia chi giao`    Varchar(255) Not Null,
    `Ghi chu`         Text         Default Null,
    `Tong tien`       Decimal(12,0) Not Null Default 0,
    `Phi van chuyen`  Decimal(12,0) Not Null Default 0,
    `Giam gia`        Decimal(12,0) Not Null Default 0,
    `Thanh tien`      Decimal(12,0) Not Null Default 0 Comment 'Tong tien + phi - giam gia',
    `Phuong thuc tt`  Enum('Cod','Chuyen khoan','Vi dien tu') Not Null Default 'Cod',
    `Trang thai tt`   Enum('Chua thanh toan','Da thanh toan') Not Null Default 'Chua thanh toan',
    `Phuong thuc vc`  Varchar(100) Default Null COMMENT 'Giao hàng tiêu chuẩn, Nhanh...',
    `Trang thai`      Enum('Cho xac nhan','Dang xu ly','Dang giao','Hoan thanh','Da huy') Not Null Default 'Cho xac nhan',
    `Ma giam gia`     Varchar(50) Default Null,
    `Tao luc`         Datetime    Not Null Default Current_timestamp,
    `Cap nhat`        Datetime    Not Null Default Current_timestamp On Update Current_timestamp,
    Foreign Key (`Nguoi dung id`) References `Users`(`Id`) On Delete Set Null
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Đơn hàng của khách hàng';

-- ----------------------------------------------------------------
-- Bảng 8: Order details - Chi tiết sản phẩm trong đơn hàng
-- ----------------------------------------------------------------
Create Table `Order details` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `Don hang id`   Int Unsigned Not Null,
    `Bien the id`   Int Unsigned Not Null,
    `Ten san pham`  Varchar(200) Not Null Comment 'Snapshot tên lúc đặt hàng',
    `Mau sac`       Varchar(50)  Not Null,
    `Size`          Varchar(10)  Not Null,
    `So luong`      Int Unsigned Not Null,
    `Don gia`       Decimal(12,0) Not Null Comment 'Giá lúc đặt hàng',
    `Thanh tien`    Decimal(12,0) Not Null Comment 'So luong * don gia',
    Foreign Key (`Don hang id`) References `Orders`(`Id`) On Delete Cascade,
    Foreign Key (`Bien the id`) References `Product variants`(`Id`) On Delete Restrict
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Chi tiết sản phẩm trong từng đơn hàng';

-- ----------------------------------------------------------------
-- Bảng 9: Wishlists - Danh sách yêu thích
-- ----------------------------------------------------------------
Create Table `Wishlists` (
    `Id`            Int Unsigned Auto_increment Primary Key,
    `Nguoi dung id` Int Unsigned Not Null,
    `San pham id`   Int Unsigned Not Null,
    `Tao luc`       Datetime     Not Null Default Current_timestamp,
    Unique Key `Uq wishlist` (`Nguoi dung id`, `San pham id`),
    Foreign Key (`Nguoi dung id`) References `Users`(`Id`) On Delete Cascade,
    Foreign Key (`San pham id`)   References `Products`(`Id`) On Delete Cascade
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Sản phẩm yêu thích của người dùng';

-- ----------------------------------------------------------------
-- Bảng 10: Discount codes - Mã giảm giá
-- ----------------------------------------------------------------
Create Table `Discount codes` (
    `Id`                 Int Unsigned Auto_increment Primary Key,
    `Ma`                 Varchar(50)  Not Null Unique Comment 'Mã nhập: SALE20',
    `Loai`               Enum('Phan tram','So tien') Not Null Default 'Phan tram',
    `Gia tri`            Decimal(12,0) Not Null Comment '20 = 20% hoặc 50000 VNĐ',
    `Don hang toi thieu` Decimal(12,0) Not Null Default 0 Comment 'Giá trị đơn tối thiểu',
    `So luot dung`       Int Unsigned Not Null Default 1 COMMENT 'Số lần dùng còn lại',
    `Ngay bat dau`       Date Default Null,
    `Ngay het han`       Date Default Null,
    `Trang thai`         Tinyint(1) Not Null Default 1,
    `Tao luc`            Datetime   Not Null Default Current_timestamp
) Engine=Innodb Default Charset=utf8mb4 Collate=utf8mb4_unicode_ci
  Comment='Mã giảm giá khuyến mãi';

-- ================================================================
-- Dữ liệu mẫu (Seed data)
-- ================================================================

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
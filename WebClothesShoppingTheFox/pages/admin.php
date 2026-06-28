<?php 
require_once '../middleware/checkAdmin.php'; 
/** 
 * Khởi tạo định danh biến toàn cục cho trang Admin View 
 * @var string $adminFullName Tên quản trị viên đăng nhập
 */
$adminFullName = $adminFullName ?? ($fullname ?? 'Admin Manager');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <title>WEB ADMIN - Control Panel</title>
</head>
<body>

    <!-- TOP HEADER -->
    <header class="main-header">
        <div class="logo-area">
            WEB ADMIN
        </div>
        <div class="navbar-top">
            <div class="nav-left">
                <button class="toggle-btn" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                <a href="home.php" class="view-site-btn"><i class="fa-solid fa-square-share-nodes"></i> Xem website</a>
            </div>
            <div class="nav-right" style="display:flex; align-items:center; gap:15px;">
                <div class="user-profile">
                    <i class="fa-solid fa-user-shield"></i>
                    <span><?php echo htmlspecialchars($adminFullName); ?></span>
                </div>
                <button onclick="adminLogout()" class="view-site-btn" style="background: #dd4b39; border-color: #d73925; cursor:pointer;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</button>
            </div>
        </div>
    </header>

    <!-- LEFT SIDEBAR WITH ALL RESTORED SUBMENUS MATCHING SCREENSHOT -->
    <aside class="main-sidebar">
        <div class="user-panel">
            <div class="avatar-placeholder">
                <?php echo strtoupper(substr($adminFullName, 0, 1)); ?>
            </div>
            <div class="info">
                <p><?php echo htmlspecialchars($adminFullName); ?></p>
                <div class="status"><i class="fa-solid fa-circle"></i> Online</div>
            </div>
        </div>
        <div class="nav-header">MAIN NAVIGATION</div>
        <ul class="sidebar-menu">
            <li class="active" id="nav-dashboard">
                <a onclick="switchTab('tab-dashboard')">
                    <span class="menu-label"><i class="fas fa-chart-line"></i> Dashboard</span>
                </a>
            </li>

            <!-- QUẢN TRỊ DANH MỤC ACCORDION (RESTORED ALL 6 SUBMENUS MATCHING SCREENSHOT) -->
            <li class="menu-item-has-children open" id="menu-categories">
                <a onclick="toggleSubmenu('menu-categories')">
                    <span class="menu-label"><i class="fas fa-folder-open"></i> QUẢN TRỊ DANH MỤC</span>
                    <i class="fas fa-chevron-down chevron"></i>
                </a>
                <ul class="treeview-menu">
                    <li id="sub-cat-type"><a onclick="switchTab('tab-categories')"><span class="sub-icon">○</span> Loại danh mục</a></li>
                    <li id="sub-cat-articles"><a onclick="switchTab('sub-articles-view')"><span class="sub-icon">○</span> Bài viết</a></li>
                    <li id="sub-cat-products"><a onclick="switchTab('tab-products')"><span class="sub-icon">○</span> Sản phẩm</a></li>
                    <li id="sub-cat-services"><a onclick="switchTab('sub-services-view')"><span class="sub-icon">○</span> Sản phẩm Dịch vụ</a></li>
                    <li id="sub-cat-album"><a onclick="switchTab('sub-album-view')"><span class="sub-icon">○</span> Album ảnh</a></li>
                    <li id="sub-cat-videos"><a onclick="switchTab('sub-videos-view')"><span class="sub-icon">○</span> Videos</a></li>
                </ul>
            </li>

            <!-- QUẢN TRỊ GIAO DIỆN ACCORDION (RESTORED ALL 4 SUBMENUS MATCHING SCREENSHOT) -->
            <li class="menu-item-has-children" id="menu-interface">
                <a onclick="toggleSubmenu('menu-interface')">
                    <span class="menu-label"><i class="fas fa-desktop"></i> QUẢN TRỊ GIAO DIỆN</span>
                    <i class="fas fa-chevron-down chevron"></i>
                </a>
                <ul class="treeview-menu">
                    <li id="sub-ui-support"><a onclick="switchTab('sub-support-view')"><span class="sub-icon">○</span> Hỗ trợ trực tuyến</a></li>
                    <li id="sub-ui-content"><a onclick="switchTab('sub-content-view')"><span class="sub-icon">○</span> Nội dung</a></li>
                    <li id="sub-ui-general"><a onclick="switchTab('sub-general-view')"><span class="sub-icon">○</span> Cấu hình chung</a></li>
                    <li id="sub-ui-text"><a onclick="switchTab('sub-text-view')"><span class="sub-icon">○</span> Text</a></li>
                </ul>
            </li>

            <!-- QUẢN LÝ KHÁCH HÀNG -->
            <li id="nav-customers">
                <a onclick="switchTab('tab-customers')">
                    <span class="menu-label"><i class="fas fa-users"></i> QUẢN LÝ KHÁCH HÀNG</span>
                    <i class="fas fa-chevron-left chevron"></i>
                </a>
            </li>

            <!-- QUẢN LÝ BÁN HÀNG -->
            <li id="nav-orders">
                <a onclick="switchTab('tab-orders')">
                    <span class="menu-label"><i class="fas fa-shopping-cart"></i> QUẢN LÝ BÁN HÀNG</span>
                    <span class="badge" id="pendingOrdersCount">0</span>
                </a>
            </li>

            <!-- PHÂN QUYỀN -->
            <li id="nav-roles">
                <a onclick="switchTab('tab-roles')">
                    <span class="menu-label"><i class="fas fa-user-shield"></i> PHÂN QUYỀN</span>
                    <i class="fas fa-chevron-left chevron"></i>
                </a>
            </li>

            <!-- QUẢN LÝ USER -->
            <li id="nav-users">
                <a onclick="switchTab('tab-users')">
                    <span class="menu-label"><i class="fas fa-user-cog"></i> QUẢN LÝ USER</span>
                    <i class="fas fa-chevron-left chevron"></i>
                </a>
            </li>

            <!-- ĐĂNG XUẤT -->
            <li id="nav-logout" style="margin-top: 15px; border-top: 1px solid #1a2226;">
                <a onclick="adminLogout()" style="color: #ff6b6b; font-weight: bold;">
                    <span class="menu-label"><i class="fas fa-sign-out-alt"></i> ĐĂNG XUẤT</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN WORKSPACE -->
    <main class="content-wrapper">
        <div class="content-header">
            <h1 id="pageMainTitle">Dashboard <small>Control panel</small></h1>
            <div class="breadcrumb">
                <a href="#"><i class="fas fa-tachometer-alt"></i> Home</a>
                <i class="fas fa-chevron-right"></i>
                <span id="breadcrumbCurrent">Dashboard</span>
            </div>
        </div>

        <!-- 1. DASHBOARD OVERVIEW TAB -->
        <div id="tab-dashboard" class="tab-pane active">
            <div class="stat-grid">
                <div class="stat-box bg-aqua" onclick="switchTab('tab-orders')">
                    <div class="inner">
                        <h3 id="statOrders">0</h3>
                        <p>ĐƠN HÀNG</p>
                    </div>
                    <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                    <a href="javascript:void(0)" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
                <div class="stat-box bg-green" onclick="switchTab('tab-products')">
                    <div class="inner">
                        <h3 id="statProducts">0</h3>
                        <p>SẢN PHẨM</p>
                    </div>
                    <div class="icon"><i class="fas fa-chart-bar"></i></div>
                    <a href="javascript:void(0)" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
                <div class="stat-box bg-yellow" onclick="switchTab('tab-customers')">
                    <div class="inner">
                        <h3 id="statUsers">0</h3>
                        <p>KHÁCH HÀNG</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-plus"></i></div>
                    <a href="javascript:void(0)" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
                <div class="stat-box bg-red" onclick="switchTab('tab-categories')">
                    <div class="inner">
                        <h3 id="statCategories">0</h3>
                        <p>DANH MỤC</p>
                    </div>
                    <div class="icon"><i class="fas fa-chart-pie"></i></div>
                    <a href="javascript:void(0)" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="admin-card card-primary">
                    <div class="card-header">
                        <h3>ĐƠN ĐẶT HÀNG MỚI (DỮ LIỆU THẬT)</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-cms">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Tên khách hàng</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                    </tr>
                                </thead>
                                <tbody id="recentOrdersTableBody">
                                    <!-- Loaded from real DB -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="admin-card card-success">
                    <div class="card-header">
                        <h3>DOANH THU HỆ THỐNG</h3>
                    </div>
                    <div class="card-body" style="text-align: center; padding: 35px 20px;">
                        <h2 id="statRevenue" style="font-size: 34px; color: #00a65a; font-weight: bold; margin-bottom: 10px;">0đ</h2>
                        <p style="color: #666; font-size: 13.5px;">Tổng doanh thu đơn hàng thành công trong CSDL</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. LOẠI DANH MỤC (REAL DB CATEGORIES) -->
        <div id="tab-categories" class="tab-pane" style="display:none;">
            <div class="filter-toolbar">
                <div class="filter-group">
                    <select><option>Tác vụ</option></select>
                    <input type="text" placeholder="Nhập tên danh mục cần tìm..." id="searchCategoryInput" style="width: 220px;" onkeyup="filterCategories()">
                    <button class="btn-refresh" onclick="loadCategories()"><i class="fas fa-sync-alt"></i></button>
                </div>
                <button class="btn-add-new" onclick="openCategoryModal()"><i class="fas fa-plus"></i> Thêm danh mục mới</button>
            </div>

            <div id="categoryAlert" class="alert alert-success"></div>
            <div class="table-responsive">
                <table class="table-cms">
                    <thead>
                        <tr>
                            <th style="width:30px;"><input type="checkbox"></th>
                            <th style="width:50px;">ID</th>
                            <th>TÊN DANH MỤC</th>
                            <th>SỐ SẢN PHẨM</th>
                            <th>TRẠNG THÁI</th>
                            <th style="width:140px;">TÁC VỤ</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody">
                        <!-- Loaded from real DB -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. QUẢN LÝ SẢN PHẨM (WITH TABLE & RICH EDIT FORM MATCHING SCREENSHOT 3) -->
        <div id="tab-products" class="tab-pane" style="display:none;">
            <!-- View 1: Products Table List -->
            <div id="productTableView">
                <div class="filter-toolbar">
                    <div class="filter-group">
                        <select><option>Tác vụ</option></select>
                        <input type="text" placeholder="Tìm tên sản phẩm..." id="searchProductInput" style="width: 220px;" onkeyup="filterProducts()">
                        <button class="btn-refresh" onclick="loadProducts()"><i class="fas fa-sync-alt"></i></button>
                    </div>
                    <button class="btn-add-new" onclick="showRichProductForm(0)"><i class="fas fa-plus"></i> Thêm sản phẩm mới</button>
                </div>

                <div id="productAlert" class="alert alert-success"></div>
                <div class="table-responsive">
                    <table class="table-cms">
                        <thead>
                            <tr>
                                <th style="width:30px;"><input type="checkbox"></th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá bán</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                <th>Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <!-- Loaded from real DB -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- View 2: Full Rich Edit Screen (Exact Matching Screenshot 3) -->
            <div id="productRichEditView" style="display:none;">
                <div style="margin-bottom:15px; display:flex; justify-content:space-between; align-items:center;">
                    <h3 style="font-size:18px; font-weight:bold; color:#444;">SẢN PHẨM <small style="color:#777;">[Chỉnh sửa / Thêm mới]</small></h3>
                    <div>
                        <button onclick="hideRichProductForm()" class="btn-action" style="background:#888; padding:6px 12px; font-size:13px;"><i class="fas fa-undo"></i> Quay lại</button>
                        <button onclick="saveRichProductSubmit()" class="btn-add-new" style="padding:6px 15px; font-size:13px; background:#3c8dbc;"><i class="fas fa-save"></i> Cập nhật vào CSDL</button>
                    </div>
                </div>

                <div id="richProductAlert" class="alert alert-danger"></div>

                <div class="product-edit-container">
                    <form id="richProductForm">
                        <input type="hidden" id="richId">
                        <div class="product-edit-grid">
                            <!-- Left Main Fields -->
                            <div>
                                <div class="form-group" style="display:flex; align-items:center; gap:15px;">
                                    <label style="width:110px; margin:0;">Tên sản phẩm :</label>
                                    <input type="text" id="richName" class="form-control" placeholder="Sản phẩm top beauty" style="flex:1;" required>
                                </div>
                                <div class="form-group" style="display:flex; align-items:center; gap:15px;">
                                    <label style="width:110px; margin:0;">Đường dẫn :</label>
                                    <span style="color:#777;">/</span>
                                    <input type="text" id="richSlug" class="form-control" placeholder="san-pham-top-beauty" style="flex:1;">
                                </div>
                                <div class="form-group" style="display:flex; align-items:center; gap:15px;">
                                    <label style="width:110px; margin:0;">Thương hiệu :</label>
                                    <input type="text" id="richBrand" class="form-control" value="Top Beauty" style="flex:1;">
                                </div>

                                <div class="form-group" style="margin-top:20px;">
                                    <label>Mô tả :</label>
                                    <div class="editor-toolbar">
                                        <button type="button"><b>B</b></button><button type="button"><i>I</i></button><button type="button"><u>U</u></button><button type="button">S</button>
                                        <button type="button"><i class="fas fa-align-left"></i></button><button type="button"><i class="fas fa-align-center"></i></button>
                                    </div>
                                    <textarea id="richShortDesc" class="editor-textarea" placeholder="Nhập dòng mô tả ngắn về sản phẩm..."></textarea>
                                </div>

                                <div class="form-group" style="margin-top:20px;">
                                    <label>Nội dung sản phẩm :</label>
                                    <div class="editor-toolbar">
                                        <button type="button"><b>B</b></button><button type="button"><i>I</i></button><button type="button"><u>U</u></button><button type="button"><i class="fas fa-image"></i> Hình ảnh</button>
                                    </div>
                                    <textarea id="richFullDesc" class="editor-textarea" style="height:180px;" placeholder="Nhập nội dung thông tin sản phẩm chi tiết tại khung này..."></textarea>
                                </div>
                            </div>

                            <!-- Right Sidebar Properties -->
                            <div>
                                <div class="side-panel-box">
                                    <div class="tab-header">
                                        <span class="active">Thông tin</span>
                                        <span>Album ảnh</span>
                                        <span>Thuộc tính</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Hình ảnh :</label>
                                        <div class="image-preview-area">
                                            <img id="richImgPreview" src="../assets/images/sp1.jpg" alt="Preview">
                                            <div class="upload-btn-overlay" onclick="document.getElementById('richImgUrl').focus();"><i class="fas fa-upload"></i> Chọn hình ảnh</div>
                                        </div>
                                        <input type="text" id="richImgUrl" class="form-control" value="../assets/images/sp1.jpg" style="font-size:12px;" onchange="document.getElementById('richImgPreview').src=this.value">
                                    </div>
                                    <div class="form-group">
                                        <label>Danh mục :</label>
                                        <select id="richCategory" class="form-control">
                                            <!-- Dynamic Loaded -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tình trạng :</label>
                                        <div style="display:flex; gap:15px; font-size:13px; margin-top:4px;">
                                            <label style="font-weight:normal; cursor:pointer;"><input type="radio" name="stock" checked> Còn hàng</label>
                                            <label style="font-weight:normal; cursor:pointer;"><input type="radio" name="stock"> Hết hàng</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Mã sản phẩm :</label>
                                        <input type="text" id="richCode" class="form-control" value="PNVN100">
                                    </div>
                                    <div class="form-group">
                                        <label>Giá :</label>
                                        <div style="display:flex; align-items:center; gap:5px;">
                                            <input type="number" id="richPrice" class="form-control" value="250000" required>
                                            <span style="font-size:12px; font-weight:bold; color:#777;">VND</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Khuyến mãi :</label>
                                        <div style="display:flex; align-items:center; gap:5px;">
                                            <input type="number" id="richDiscount" class="form-control" value="100000">
                                            <span style="font-size:12px; font-weight:bold; color:#777;">VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Số thứ tự :</label>
                                        <input type="number" id="richSort" class="form-control" value="0">
                                    </div>
                                    <div class="form-group" style="margin-top:10px;">
                                        <label style="font-weight:normal; cursor:pointer;"><input type="checkbox" checked> Hiển thị</label>
                                    </div>
                                    <div class="form-group" style="display:flex; gap:15px; font-size:12px;">
                                        <label style="font-weight:normal; cursor:pointer;"><input type="checkbox"> Nofollow</label>
                                        <label style="font-weight:normal; cursor:pointer;"><input type="checkbox"> Noindex</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 4. OTHER SUBTAB PLACEHOLDERS (ARTICLE, SERVICES, ALBUM, VIDEOS, INTERFACE) -->
        <div id="sub-articles-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Bài viết & Tin tức thời trang</h3></div><div class="card-body"><p>Quản lý các bài viết xu hướng thời trang...</p></div></div>
        </div>
        <div id="sub-services-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Sản phẩm Dịch vụ</h3></div><div class="card-body"><p>Quản lý các gói dịch vụ tư vấn outfit...</p></div></div>
        </div>
        <div id="sub-album-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Album ảnh Bộ sưu tập</h3></div><div class="card-body"><p>Quản lý album lookbook...</p></div></div>
        </div>
        <div id="sub-videos-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Videos trình diễn</h3></div><div class="card-body"><p>Quản lý các video catwalk...</p></div></div>
        </div>
        <div id="sub-support-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Hỗ trợ trực tuyến</h3></div><div class="card-body"><p>Cấu hình tổng đài hỗ trợ...</p></div></div>
        </div>
        <div id="sub-content-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Nội dung khối website</h3></div><div class="card-body"><p>Quản lý Banner, Slider và Footer...</p></div></div>
        </div>
        <div id="sub-general-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Cấu hình chung</h3></div><div class="card-body"><p>Thiết lập thông tin thương hiệu The Fox...</p></div></div>
        </div>
        <div id="sub-text-view" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary"><div class="card-header"><h3>Nhãn Text ngôn ngữ</h3></div><div class="card-body"><p>Chỉnh sửa văn bản hiển thị trên website...</p></div></div>
        </div>

        <!-- 5. QUẢN LÝ KHÁCH HÀNG -->
        <div id="tab-customers" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary">
                <div class="card-header">
                    <h3>Khách hàng đăng ký (Dữ liệu thật CSDL)</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-cms">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên khách hàng</th>
                                    <th>SĐT</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                </tr>
                            </thead>
                            <tbody id="customersTableBody">
                                <!-- Loaded from real DB -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. QUẢN LÝ BÁN HÀNG -->
        <div id="tab-orders" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary">
                <div class="card-header">
                    <h3>Quản lý đơn đặt hàng</h3>
                </div>
                <div class="card-body">
                    <div id="orderAlert" class="alert alert-success"></div>
                    <div class="table-responsive">
                        <table class="table-cms">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ nhận hàng</th>
                                    <th>Thành tiền</th>
                                    <th>Trạng thái giao hàng</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <!-- Loaded from real DB -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. PHÂN QUYỀN -->
        <div id="tab-roles" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary">
                <div class="card-header">
                    <h3>Thiết lập quyền vai trò hệ thống</h3>
                </div>
                <div class="card-body">
                    <div id="roleAlert" class="alert alert-success"></div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;" id="rolesListContainer">
                        <!-- Loaded from real DB -->
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. QUẢN LÝ USER -->
        <div id="tab-users" class="tab-pane" style="display:none;">
            <div class="admin-card card-primary">
                <div class="card-header">
                    <h3>Quản lý thành viên Admin & Khách hàng</h3>
                    <button class="btn-add-new" onclick="openUserModal()"><i class="fas fa-user-plus"></i> Thêm thành viên</button>
                </div>
                <div class="card-body">
                    <div id="userAlert" class="alert alert-success"></div>
                    <div class="table-responsive">
                        <table class="table-cms">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ và tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Vai trò</th>
                                    <th>Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <!-- Loaded from real DB -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- REAL MODAL: ADD / EDIT CATEGORY -->
    <div class="modal" id="categoryModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="categoryModalTitle">Thêm danh mục mới</h3>
                <button onclick="closeCategoryModal()" style="background:none; border:none; color:white; font-size:22px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <div id="categoryModalAlert" class="alert alert-danger"></div>
                <form id="categoryForm" onsubmit="event.preventDefault(); saveCategorySubmit();">
                    <input type="hidden" id="catModalId">
                    <div class="form-group">
                        <label for="catModalName">Tên danh mục (*)</label>
                        <input type="text" id="catModalName" class="form-control" placeholder="Ví dụ: Áo Khoác Unisex" required>
                    </div>
                    <div class="modal-footer" style="padding:0; margin-top:15px; border:none;">
                        <button type="button" class="btn-action" style="background:#888; padding:8px 15px;" onclick="closeCategoryModal()">Hủy</button>
                        <button type="submit" class="btn-add-new"><i class="fas fa-save"></i> Lưu danh mục vào CSDL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- REAL MODAL: ADD / EDIT USER -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="userModalTitle">Thêm thành viên mới</h3>
                <button onclick="closeUserModal()" style="background:none; border:none; color:white; font-size:22px; cursor:pointer;">&times;</button>
            </div>
            <div class="modal-body">
                <div id="userModalAlert" class="alert alert-danger"></div>
                <form id="userForm" onsubmit="event.preventDefault(); saveUserSubmit();">
                    <input type="hidden" id="modalUserId">
                    <div class="form-group">
                        <label for="modalFullname">Họ và tên (*)</label>
                        <input type="text" id="modalFullname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="modalEmail">Email (*)</label>
                        <input type="email" id="modalEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="modalPhone">Số điện thoại (*)</label>
                        <input type="text" id="modalPhone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="modalRole">Vai trò</label>
                        <select id="modalRole" class="form-control">
                            <option value="user">User (Khách hàng)</option>
                            <option value="admin">Admin (Quản trị viên)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modalPassword" id="modalPasswordLabel">Mật khẩu</label>
                        <input type="password" id="modalPassword" class="form-control">
                    </div>
                    <div class="modal-footer" style="padding:0; margin-top:15px; border:none;">
                        <button type="button" class="btn-action" style="background:#888; padding:8px 15px;" onclick="closeUserModal()">Hủy</button>
                        <button type="submit" class="btn-add-new"><i class="fas fa-save"></i> Lưu thông tin thành viên</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>

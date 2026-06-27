// ============================================================
// ADMIN CONTROL PANEL - JavaScript Controller
// File: assets/js/admin.js
// ============================================================

// ---- 0. UI CONTROLS ----

document.getElementById("sidebarToggle").addEventListener("click", () => {
    document.body.classList.toggle("sidebar-open");
});

window.toggleSubmenu = function(menuId) {
    const item = document.getElementById(menuId);
    if (item) item.classList.toggle("open");
};

const tabPanes = document.querySelectorAll(".tab-pane");
const sidebarSubItems = document.querySelectorAll(".treeview-menu li, .sidebar-menu > li");

window.switchTab = function(tabId) {
    tabPanes.forEach(pane => {
        pane.style.display = pane.id === tabId ? "block" : "none";
    });

    sidebarSubItems.forEach(li => li.classList.remove("active"));
    if (tabId === "tab-dashboard") document.getElementById("nav-dashboard")?.classList.add("active");
    else if (tabId === "tab-categories") document.getElementById("sub-cat-type")?.classList.add("active");
    else if (tabId === "sub-articles-view") document.getElementById("sub-cat-articles")?.classList.add("active");
    else if (tabId === "tab-products") document.getElementById("sub-cat-products")?.classList.add("active");
    else if (tabId === "sub-services-view") document.getElementById("sub-cat-services")?.classList.add("active");
    else if (tabId === "sub-album-view") document.getElementById("sub-cat-album")?.classList.add("active");
    else if (tabId === "sub-videos-view") document.getElementById("sub-cat-videos")?.classList.add("active");
    else if (tabId === "sub-support-view") document.getElementById("sub-ui-support")?.classList.add("active");
    else if (tabId === "sub-content-view") document.getElementById("sub-ui-content")?.classList.add("active");
    else if (tabId === "sub-general-view") document.getElementById("sub-ui-general")?.classList.add("active");
    else if (tabId === "sub-text-view") document.getElementById("sub-ui-text")?.classList.add("active");
    else if (tabId === "tab-customers") document.getElementById("nav-customers")?.classList.add("active");
    else if (tabId === "tab-orders") document.getElementById("nav-orders")?.classList.add("active");
    else if (tabId === "tab-roles") document.getElementById("nav-roles")?.classList.add("active");
    else if (tabId === "tab-users") document.getElementById("nav-users")?.classList.add("active");

    const tabTitles = {
        "tab-dashboard": "Dashboard",
        "tab-categories": "Loại danh mục",
        "sub-articles-view": "Bài viết",
        "tab-products": "Sản phẩm",
        "sub-services-view": "Sản phẩm Dịch vụ",
        "sub-album-view": "Album ảnh",
        "sub-videos-view": "Videos",
        "sub-support-view": "Hỗ trợ trực tuyến",
        "sub-content-view": "Nội dung",
        "sub-general-view": "Cấu hình chung",
        "sub-text-view": "Text",
        "tab-customers": "Quản lý Khách hàng",
        "tab-orders": "Quản lý Bán hàng",
        "tab-roles": "Phân quyền",
        "tab-users": "Quản lý User"
    };

    const title = tabTitles[tabId] || "Admin Control Panel";
    document.getElementById("pageMainTitle").innerHTML = `${title} <small>Control panel</small>`;
    document.getElementById("breadcrumbCurrent").textContent = title;

    if (tabId === "tab-dashboard") loadDashboardData();
    else if (tabId === "tab-categories") loadCategories();
    else if (tabId === "tab-products") loadProducts();
    else if (tabId === "tab-customers") loadCustomers();
    else if (tabId === "tab-orders") loadOrders();
    else if (tabId === "tab-roles") loadRoles();
    else if (tabId === "tab-users") loadUsers();
};

window.adminLogout = async function() {
    if (confirm("Bạn có chắc chắn muốn đăng xuất khỏi tài khoản Admin?")) {
        try {
            await fetch("../routes/auth.php?action=logout");
            window.location.href = "login.php";
        } catch (err) {
            window.location.href = "login.php";
        }
    }
};

function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");
}

// ---- 1. DASHBOARD ----

async function loadDashboardData() {
    try {
        const response = await fetch("../routes/admin.php?action=get_stats");
        const result = await response.json();
        if (result.success) {
            const data = result.data;
            document.getElementById("statRevenue").textContent = parseFloat(data.total_revenue).toLocaleString('vi-VN') + "đ";
            document.getElementById("statOrders").textContent = data.total_orders;
            document.getElementById("statProducts").textContent = data.total_products;
            document.getElementById("statUsers").textContent = data.total_users;
            document.getElementById("statCategories").textContent = data.total_categories || 0;
            document.getElementById("pendingOrdersCount").textContent = data.pending_orders || 0;

            const tbody = document.getElementById("recentOrdersTableBody");
            if (data.recent_orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;">Chưa có đơn hàng nào trong CSDL.</td></tr>`;
            } else {
                tbody.innerHTML = data.recent_orders.map(o => `
                    <tr>
                        <td><strong>#${o.order_code || o.id}</strong></td>
                        <td>${escapeHtml(o.fullname)}</td>
                        <td><span class="badge-status">${o.status}</span></td>
                        <td>${o.created_at}</td>
                    </tr>
                `).join('');
            }
        }
    } catch (err) {}
}

// ---- 2. CATEGORIES CRUD ----

let globalCategories = [];

async function loadCategories() {
    const tbody = document.getElementById("categoriesTableBody");
    try {
        const response = await fetch("../routes/admin.php?action=list_categories");
        const result = await response.json();
        if (result.success) {
            globalCategories = result.data;
            renderCategoriesTable(globalCategories);
        }
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:red;">Lỗi kết nối máy chủ.</td></tr>`;
    }
}

function renderCategoriesTable(data) {
    const tbody = document.getElementById("categoriesTableBody");
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Chưa có danh mục nào trong CSDL.</td></tr>`;
        return;
    }
    tbody.innerHTML = data.map(c => `
        <tr>
            <td><input type="checkbox"></td>
            <td>${c.id}</td>
            <td><strong>${escapeHtml(c.name)}</strong></td>
            <td><span style="color:#3c8dbc; font-weight:bold;">${c.total_products || 0} sản phẩm</span></td>
            <td><span class="badge-status">● Hiển thị</span></td>
            <td>
                <button onclick="editCategory(${c.id}, '${escapeHtml(c.name)}')" class="btn-action btn-action-edit"><i class="fas fa-edit"></i> Sửa</button>
                <button onclick="deleteCategory(${c.id})" class="btn-action btn-action-delete"><i class="fas fa-trash"></i> Xóa</button>
            </td>
        </tr>
    `).join('');
}

function filterCategories() {
    const query = document.getElementById("searchCategoryInput").value.toLowerCase();
    const filtered = globalCategories.filter(c => c.name.toLowerCase().includes(query));
    renderCategoriesTable(filtered);
}

window.openCategoryModal = () => {
    document.getElementById("categoryModalTitle").textContent = "Thêm danh mục mới";
    document.getElementById("catModalId").value = "";
    document.getElementById("catModalName").value = "";
    document.getElementById("categoryModalAlert").style.display = "none";
    document.getElementById("categoryModal").classList.add("active");
};
window.closeCategoryModal = () => document.getElementById("categoryModal").classList.remove("active");

window.editCategory = (id, name) => {
    document.getElementById("categoryModalTitle").textContent = "Cập nhật danh mục";
    document.getElementById("catModalId").value = id;
    document.getElementById("catModalName").value = name;
    document.getElementById("categoryModalAlert").style.display = "none";
    document.getElementById("categoryModal").classList.add("active");
};

window.saveCategorySubmit = async () => {
    const alertBox = document.getElementById("categoryModalAlert");
    alertBox.style.display = "none";
    const id = document.getElementById("catModalId").value;
    const name = document.getElementById("catModalName").value;
    try {
        const response = await fetch("../routes/admin.php?action=save_category", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, name })
        });
        const result = await response.json();
        if (result.success) { closeCategoryModal(); loadCategories(); }
        else { alertBox.textContent = result.message; alertBox.style.display = "block"; }
    } catch (err) {}
};

window.deleteCategory = async (id) => {
    if (!confirm("Bạn có chắc chắn muốn xóa danh mục này?")) return;
    try {
        const response = await fetch("../routes/admin.php?action=delete_category", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) loadCategories();
        else alert(result.message);
    } catch (err) {}
};

// ---- 3. PRODUCTS CRUD ----

let globalProducts = [];

async function loadProducts() {
    const tbody = document.getElementById("productsTableBody");
    try {
        const response = await fetch("../routes/admin.php?action=list_products");
        const result = await response.json();
        if (result.success) {
            globalProducts = result.data;
            renderProductsTable(globalProducts);
        }
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">Lỗi tải sản phẩm.</td></tr>`;
    }
}

function renderProductsTable(data) {
    const tbody = document.getElementById("productsTableBody");
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;">Chưa có sản phẩm nào trong CSDL.</td></tr>`;
        return;
    }
    tbody.innerHTML = data.map(p => `
        <tr>
            <td><input type="checkbox"></td>
            <td><img src="${p.image.startsWith('..') ? p.image : '../assets/images/' + p.image}" style="width:38px; height:38px; object-fit:cover; border-radius:3px;"></td>
            <td><strong>${escapeHtml(p.name)}</strong></td>
            <td style="color:#00a65a; font-weight:bold;">${parseFloat(p.price).toLocaleString('vi-VN')}đ</td>
            <td>${escapeHtml(p.category_name || 'Khác')}</td>
            <td><span class="badge-status">● Còn hàng</span></td>
            <td>
                <button onclick="showRichProductForm(${p.id}, '${escapeHtml(p.name)}', ${p.price}, '${escapeHtml(p.image)}', ${p.category_id || 0}, '${escapeHtml(p.description || '')}')" class="btn-action btn-action-edit"><i class="fas fa-edit"></i> Sửa</button>
                <button onclick="deleteProduct(${p.id})" class="btn-action btn-action-delete"><i class="fas fa-trash"></i> Xóa</button>
            </td>
        </tr>
    `).join('');
}

function filterProducts() {
    const query = document.getElementById("searchProductInput").value.toLowerCase();
    const filtered = globalProducts.filter(p => p.name.toLowerCase().includes(query));
    renderProductsTable(filtered);
}

async function populateCategoryDropdown() {
    const select = document.getElementById("richCategory");
    try {
        const response = await fetch("../routes/admin.php?action=list_categories");
        const result = await response.json();
        if (result.success) {
            select.innerHTML = result.data.map(c => `<option value="${c.id}">${escapeHtml(c.name)}</option>`).join('');
        }
    } catch (err) {}
}

window.showRichProductForm = async (id = 0, name = '', price = 250000, image = '../assets/images/sp1.jpg', categoryId = 0, desc = '') => {
    await populateCategoryDropdown();
    document.getElementById("productTableView").style.display = "none";
    document.getElementById("productRichEditView").style.display = "block";
    document.getElementById("richProductAlert").style.display = "none";
    document.getElementById("richId").value = id;
    document.getElementById("richName").value = name;
    document.getElementById("richPrice").value = price;
    document.getElementById("richImgUrl").value = image || '../assets/images/sp1.jpg';
    document.getElementById("richImgPreview").src = image || '../assets/images/sp1.jpg';
    document.getElementById("richFullDesc").value = desc;
    if (categoryId > 0) document.getElementById("richCategory").value = categoryId;
};

window.hideRichProductForm = () => {
    document.getElementById("productTableView").style.display = "block";
    document.getElementById("productRichEditView").style.display = "none";
};

window.saveRichProductSubmit = async () => {
    const alertBox = document.getElementById("richProductAlert");
    alertBox.style.display = "none";
    const payload = {
        id: document.getElementById("richId").value,
        name: document.getElementById("richName").value,
        price: document.getElementById("richPrice").value,
        image: document.getElementById("richImgUrl").value,
        category_id: document.getElementById("richCategory").value,
        description: document.getElementById("richFullDesc").value
    };
    if (!payload.name || payload.price <= 0) {
        alertBox.textContent = "Vui lòng nhập tên và giá sản phẩm hợp lệ.";
        alertBox.style.display = "block";
        return;
    }
    try {
        const response = await fetch("../routes/admin.php?action=save_product", {
            method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(payload)
        });
        const result = await response.json();
        if (result.success) {
            hideRichProductForm();
            const mainAlert = document.getElementById("productAlert");
            mainAlert.textContent = result.message;
            mainAlert.style.display = "block";
            setTimeout(() => mainAlert.style.display = "none", 3000);
            loadProducts();
        } else { alertBox.textContent = result.message; alertBox.style.display = "block"; }
    } catch (err) { alertBox.textContent = "Lỗi kết nối CSDL."; alertBox.style.display = "block"; }
};

window.deleteProduct = async (id) => {
    if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi cơ sở dữ liệu?")) return;
    try {
        const response = await fetch("../routes/admin.php?action=delete_product", {
            method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) loadProducts();
        else alert(result.message);
    } catch (err) { alert("Lỗi kết nối."); }
};

// ---- 4. CUSTOMERS ----

async function loadCustomers() {
    const tbody = document.getElementById("customersTableBody");
    try {
        const response = await fetch("../routes/auth.php?action=admin_list_users");
        const result = await response.json();
        if (result.success) {
            const customers = result.data.filter(u => u.role === 'user');
            if (customers.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Chưa có khách hàng nào đăng ký.</td></tr>`;
                return;
            }
            tbody.innerHTML = customers.map((c, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${escapeHtml(c.fullname)}</strong></td>
                    <td>${escapeHtml(c.phone)}</td>
                    <td>${escapeHtml(c.email)}</td>
                    <td><span class="badge-status">${c.role}</span></td>
                </tr>
            `).join('');
        }
    } catch (err) {}
}

// ---- 5. ORDERS ----

async function loadOrders() {
    const tbody = document.getElementById("ordersTableBody");
    try {
        const response = await fetch("../routes/admin.php?action=list_orders");
        const result = await response.json();
        if (result.success) {
            if (result.data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;">Không có đơn hàng nào trong CSDL.</td></tr>`;
                return;
            }
            tbody.innerHTML = result.data.map(o => `
                <tr>
                    <td><strong>#${o.order_code || o.id}</strong></td>
                    <td>${escapeHtml(o.fullname)}</td>
                    <td>${escapeHtml(o.phone)}</td>
                    <td>${escapeHtml(o.address)}</td>
                    <td style="color:#00a65a; font-weight:bold;">${parseFloat(o.final_total).toLocaleString('vi-VN')}đ</td>
                    <td>
                        <select onchange="updateOrderStatus(${o.id}, this.value)" style="padding:4px 8px; border-radius:3px; border:1px solid #3c8dbc; font-size:12.5px; font-weight:bold; cursor:pointer;">
                            <option value="Chờ xác nhận" ${o.status === 'Chờ xác nhận' ? 'selected' : ''}>Chờ xác nhận</option>
                            <option value="Đang xử lý" ${o.status === 'Đang xử lý' ? 'selected' : ''}>Đang xử lý</option>
                            <option value="Đang giao hàng" ${o.status === 'Đang giao hàng' ? 'selected' : ''}>Đang giao hàng</option>
                            <option value="Đã hoàn thành" ${o.status === 'Đã hoàn thành' ? 'selected' : ''}>Đã hoàn thành</option>
                            <option value="Đã hủy" ${o.status === 'Đã hủy' ? 'selected' : ''}>Đã hủy</option>
                        </select>
                    </td>
                </tr>
            `).join('');
        }
    } catch (err) {}
}

window.updateOrderStatus = async (id, status) => {
    try {
        await fetch("../routes/admin.php?action=update_order_status", {
            method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ id, status })
        });
        loadDashboardData();
    } catch (err) {}
};

// ---- 6. ROLES & PERMISSIONS ----

const APP_PERMISSIONS = {
    "manage_users": "Quản lý thành viên (CRUD)",
    "manage_products": "Quản lý sản phẩm (CRUD)",
    "manage_orders": "Quản lý đơn hàng",
    "manage_categories": "Quản lý danh mục",
    "view_dashboard": "Xem thống kê doanh thu",
    "view_products": "Xem sản phẩm & Xem bài viết",
    "search_filter": "Tìm kiếm & Lọc sản phẩm",
    "add_to_cart": "Thêm sản phẩm vào giỏ hàng",
    "checkout": "Đặt hàng & Thanh toán",
    "view_orders": "Xem lịch sử đơn hàng cá nhân",
    "edit_profile": "Cập nhật thông tin cá nhân",
    "save_outfit": "Sử dụng Outfit Builder (Phối đồ)",
    "manage_wishlist": "Quản lý Danh sách yêu thích"
};

async function loadRoles() {
    const container = document.getElementById("rolesListContainer");
    try {
        const response = await fetch("../routes/auth.php?action=admin_list_roles");
        const result = await response.json();
        if (result.success) {
            container.innerHTML = result.data.map(role => `
                <div style="background:#fff; border:1px solid #d2d6de; border-radius:3px; padding:18px; display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <h4 style="color:#3c8dbc; margin-bottom:6px; font-size:16px;"><i class="fas fa-shield-alt"></i> ${role.name.toUpperCase()}</h4>
                        <p style="font-size:12px; color:#777; margin-bottom:15px; border-bottom:1px solid #eee; padding-bottom:8px;">${role.description}</p>
                        <div style="font-size:11px; font-weight:bold; color:#444; margin-bottom:8px; text-transform:uppercase;">Quyền hạn trên hệ thống:</div>
                        ${Object.keys(APP_PERMISSIONS).map(k => `
                            <label style="display:flex; align-items:center; gap:8px; margin-bottom:7px; font-size:13px; cursor:pointer; color:#333;">
                                <input type="checkbox" class="perm-check-${role.name}" value="${k}" ${role.permissions.includes(k) ? 'checked' : ''} style="accent-color:#3c8dbc; width:15px; height:15px;"> ${APP_PERMISSIONS[k]}
                            </label>
                        `).join('')}
                    </div>
                    <div style="margin-top:15px; padding-top:12px; border-top:1px solid #eee; text-align:right;">
                        <button onclick="saveRolePermissions('${role.name}')" class="btn-add-new" style="padding:6px 12px; font-size:12.5px;"><i class="fas fa-save"></i> Lưu phân quyền</button>
                    </div>
                </div>
            `).join('');
        }
    } catch (err) {}
}

window.saveRolePermissions = async (roleName) => {
    const checkboxes = document.querySelectorAll(`.perm-check-${roleName}:checked`);
    const selectedPerms = Array.from(checkboxes).map(cb => cb.value);
    try {
        const response = await fetch("../routes/auth.php?action=admin_update_role_permissions", {
            method: "POST", headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ role: roleName, permissions: selectedPerms })
        });
        const result = await response.json();
        const alertBox = document.getElementById("roleAlert");
        alertBox.textContent = result.message;
        alertBox.style.display = "block";
        setTimeout(() => alertBox.style.display = "none", 3000);
        loadRoles();
    } catch (err) { alert("Lỗi cập nhật phân quyền."); }
};

// ---- 7. USER MANAGEMENT ----

async function loadUsers() {
    const tbody = document.getElementById("usersTableBody");
    try {
        const response = await fetch("../routes/auth.php?action=admin_list_users");
        const result = await response.json();
        if (result.success) {
            tbody.innerHTML = result.data.map(u => `
                <tr>
                    <td>${u.id}</td>
                    <td><strong>${escapeHtml(u.fullname)}</strong></td>
                    <td>${escapeHtml(u.email)}</td>
                    <td>${escapeHtml(u.phone)}</td>
                    <td><span class="badge-status">${u.role}</span></td>
                    <td>
                        <button onclick="editUser(${u.id}, '${escapeHtml(u.fullname)}', '${escapeHtml(u.email)}', '${escapeHtml(u.phone)}', '${u.role}')" class="btn-action btn-action-edit"><i class="fas fa-edit"></i> Sửa</button>
                        <button onclick="deleteUser(${u.id})" class="btn-action btn-action-delete"><i class="fas fa-trash"></i> Xóa</button>
                    </td>
                </tr>
            `).join('');
        }
    } catch (err) {}
}

window.openUserModal = () => {
    document.getElementById("userModalTitle").textContent = "Thêm thành viên mới";
    document.getElementById("modalUserId").value = "";
    document.getElementById("modalFullname").value = "";
    document.getElementById("modalEmail").value = "";
    document.getElementById("modalPhone").value = "";
    document.getElementById("modalRole").value = "user";
    document.getElementById("modalPassword").value = "";
    document.getElementById("userModalAlert").style.display = "none";
    document.getElementById("userModal").classList.add("active");
};
window.closeUserModal = () => document.getElementById("userModal").classList.remove("active");

window.editUser = (id, name, email, phone, role) => {
    document.getElementById("userModalTitle").textContent = "Cập nhật thành viên";
    document.getElementById("modalUserId").value = id;
    document.getElementById("modalFullname").value = name;
    document.getElementById("modalEmail").value = email;
    document.getElementById("modalPhone").value = phone;
    document.getElementById("modalRole").value = role;
    document.getElementById("modalPassword").value = "";
    document.getElementById("userModalAlert").style.display = "none";
    document.getElementById("userModal").classList.add("active");
};

window.saveUserSubmit = async () => {
    const alertBox = document.getElementById("userModalAlert");
    alertBox.style.display = "none";
    const id = document.getElementById("modalUserId").value;
    const fullname = document.getElementById("modalFullname").value;
    const email = document.getElementById("modalEmail").value;
    const phone = document.getElementById("modalPhone").value;
    const role = document.getElementById("modalRole").value;
    const password = document.getElementById("modalPassword").value;
    const url = id ? "../routes/auth.php?action=admin_update_user" : "../routes/auth.php?action=admin_create_user";
    const payload = id ? { id, fullname, email, phone, role, password } : { fullname, email, phone, role, password };
    try {
        const response = await fetch(url, {
            method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(payload)
        });
        const result = await response.json();
        if (result.success) { closeUserModal(); loadUsers(); }
        else { alertBox.textContent = result.message; alertBox.style.display = "block"; }
    } catch (err) {}
};

window.deleteUser = async (id) => {
    if (!confirm("Xóa thành viên này?")) return;
    try {
        const response = await fetch("../routes/auth.php?action=admin_delete_user", {
            method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) loadUsers();
        else alert(result.message);
    } catch (err) {}
};

// ---- INIT ----
loadDashboardData();

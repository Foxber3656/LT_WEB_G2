/* ==========================================================================
   THE FOX - Module Hiệu Ứng Thu Gọn Danh Mục (Category Sidebar Animation JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const categorySidebarItemElementsList = document.querySelectorAll('.cartegory-left-li');

    // Đăng ký sự kiện bật/tắt menu con cấp 2 cho từng nhóm danh mục ở cột bên trái
    categorySidebarItemElementsList.forEach((singleCategoryItem) => {
        const categoryTitleElement = singleCategoryItem.querySelector('.cartegory-title');

        if (categoryTitleElement) {
            categoryTitleElement.addEventListener('click', (clickEvent) => {
                clickEvent.preventDefault();
                singleCategoryItem.classList.toggle('active');
            });
        }
    });
});

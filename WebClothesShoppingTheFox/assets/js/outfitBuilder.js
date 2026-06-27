/* ==========================================================================
   THE FOX - Module Giao Diện Phối Đồ Trực Quan (Outfit Builder JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener("DOMContentLoaded", () => {
    // Trạng thái lưu trữ toàn cục danh mục sản phẩm và bộ đồ đang phối trên Mannequin
    let catalogProductsData = { tops: [], bottoms: [], accessories: [] };
    let activeSelectedOutfit = { top: null, bottom: null, accessory: null };

    const tabNavigationButtons = document.querySelectorAll(".catalog-tab-btn");
    const catalogGridElements = document.querySelectorAll(".catalog-grid");
    const outfitTotalPriceDisplay = document.getElementById("outfitTotalPrice");
    const saveOutfitModalElement = document.getElementById("saveOutfitModal");

    // Xử lý sự kiện chuyển đổi giữa các Tab danh mục (Áo, Quần, Phụ kiện)
    tabNavigationButtons.forEach(singleButton => {
        singleButton.addEventListener("click", () => {
            tabNavigationButtons.forEach(btnItem => btnItem.classList.remove("active"));
            catalogGridElements.forEach(gridItem => {
                gridItem.style.display = "none";
                gridItem.classList.remove("active-grid");
            });

            singleButton.classList.add("active");
            const targetCatalogType = singleButton.getAttribute("data-catalog");
            const activeCatalogGrid = document.getElementById(`catalog-${targetCatalogType}`);
            if (activeCatalogGrid) {
                activeCatalogGrid.style.display = "grid";
                activeCatalogGrid.classList.add("active-grid");
            }
        });
    });

    // Truy xuất danh sách sản phẩm phân loại từ máy chủ CSDL
    const fetchCatalogProducts = async () => {
        try {
            const apiResponse = await fetch("../routes/outfit.php?action=get_builder_products");
            const apiResult = await apiResponse.json();
            if (apiResult.success) {
                catalogProductsData = apiResult.data;
                renderCatalogGrid("tops", catalogProductsData.tops);
                renderCatalogGrid("bottoms", catalogProductsData.bottoms);
                renderCatalogGrid("accessories", catalogProductsData.accessories);
            }
        } catch (error) {
            console.error("Lỗi lấy danh sách sản phẩm", error);
            document.querySelectorAll(".catalog-grid").forEach(gridItem => {
                gridItem.innerHTML = `<p style="padding:20px; color:red;">Lỗi kết nối CSDL.</p>`;
            });
        }
    };
    fetchCatalogProducts();

    // Hiển thị mảng sản phẩm ra lưới danh mục
    function renderCatalogGrid(catalogType, productsArray) {
        const targetGrid = document.getElementById(`catalog-${catalogType}`);
        if (!targetGrid) return;
        if (!productsArray || productsArray.length === 0) {
            targetGrid.innerHTML = `<p style="padding: 20px; color: var(--gray);">Không tìm thấy sản phẩm thuộc danh mục này.</p>`;
            return;
        }

        targetGrid.innerHTML = productsArray.map(singleProduct => `
            <div class="catalog-item-card" data-id="${singleProduct.id}" onclick="selectOutfitItem('${catalogType}', ${singleProduct.id})">
                <img src="${singleProduct.image}" alt="${singleProduct.name}" onerror="this.src='../assets/images/sp1.jpg'">
                <h4>${singleProduct.name}</h4>
                <p>${parseFloat(singleProduct.price).toLocaleString('vi-VN')}đ</p>
            </div>
        `).join('');
    }

    // Chọn một sản phẩm từ danh mục đưa vào mô hình Mannequin
    window.selectOutfitItem = (catalogCategory, selectedItemId) => {
        const singularSlotKey = catalogCategory.replace(/s$/, ''); // tops -> top, bottoms -> bottom, accessories -> accessory
        const matchedProductItem = catalogProductsData[catalogCategory].find(item => item.id === selectedItemId);
        if (!matchedProductItem) return;

        activeSelectedOutfit[singularSlotKey] = matchedProductItem;
        updateMannequinSlotDisplay(singularSlotKey, matchedProductItem);
        recalculateTotalPrice();

        const targetGrid = document.getElementById(`catalog-${catalogCategory}`);
        if (targetGrid) {
            targetGrid.querySelectorAll(".catalog-item-card").forEach(cardItem => {
                if (parseInt(cardItem.getAttribute("data-id")) === selectedItemId) {
                    cardItem.classList.add("selected");
                } else {
                    cardItem.classList.remove("selected");
                }
            });
        }
    };

    // Cập nhật giao diện trực quan vị trí slot sản phẩm trên Mannequin
    function updateMannequinSlotDisplay(slotNameKey, productItem) {
        const slotElement = document.getElementById(`slot-${slotNameKey}`);
        if (!slotElement) return;
        if (productItem) {
            slotElement.innerHTML = `
                <div class="slot-item">
                    <img src="${productItem.image}" alt="${productItem.name}" onerror="this.src='../assets/images/sp1.jpg'">
                    <div class="slot-details">
                        <h4>${productItem.name}</h4>
                        <p>${parseFloat(productItem.price).toLocaleString('vi-VN')}đ</p>
                    </div>
                </div>
                <button class="slot-remove" onclick="removeSlotItem('${slotNameKey}', event)"><i class="fas fa-times"></i></button>
            `;
            slotElement.classList.add("active");
        } else {
            const defaultPlaceholderHtmlMap = {
                accessory: '<div class="placeholder"><i class="fas fa-gem"></i><span>Phụ kiện trống</span></div>',
                top: '<div class="placeholder"><i class="fas fa-tshirt"></i><span>Áo trống</span></div>',
                bottom: '<div class="placeholder"><i class="fas fa-socks"></i><span>Quần / Chân váy trống</span></div>'
            };
            slotElement.innerHTML = defaultPlaceholderHtmlMap[slotNameKey];
            slotElement.classList.remove("active");
        }
    }

    // Loại bỏ sản phẩm khỏi slot trên Mannequin
    window.removeSlotItem = (slotNameKey, mouseClickEvent) => {
        if (mouseClickEvent) mouseClickEvent.stopPropagation();
        activeSelectedOutfit[slotNameKey] = null;
        updateMannequinSlotDisplay(slotNameKey, null);
        recalculateTotalPrice();

        const pluralCategoryKey = slotNameKey === 'accessory' ? 'accessories' : slotNameKey + 's';
        const targetGrid = document.getElementById(`catalog-${pluralCategoryKey}`);
        if (targetGrid) {
            targetGrid.querySelectorAll(".catalog-item-card").forEach(cardItem => cardItem.classList.remove("selected"));
        }
    };

    // Tính toán tổng số tiền của toàn bộ bộ đồ phối hiện tại
    function recalculateTotalPrice() {
        if (!outfitTotalPriceDisplay) return;
        let accumulatedTotalAmount = 0;
        Object.values(activeSelectedOutfit).forEach(productItem => {
            if (productItem) accumulatedTotalAmount += parseFloat(productItem.price);
        });
        outfitTotalPriceDisplay.textContent = accumulatedTotalAmount.toLocaleString('vi-VN') + "đ";
    }

    // Xóa sạch toàn bộ sản phẩm đang chọn trên Mannequin
    const clearOutfitButton = document.getElementById("clearOutfitBtn");
    if (clearOutfitButton) {
        clearOutfitButton.addEventListener("click", () => {
            removeSlotItem('top');
            removeSlotItem('bottom');
            removeSlotItem('accessory');
        });
    }

    const saveOutfitTriggerButton = document.getElementById("saveOutfitBtn");
    if (saveOutfitTriggerButton) {
        saveOutfitTriggerButton.addEventListener("click", () => {
            const selectedProductIds = getSelectedProductIdsArray();
            if (selectedProductIds.length === 0) {
                alert("Vui lòng chọn ít nhất 1 sản phẩm để phối đồ trước khi lưu!");
                return;
            }
            const alertBoxElement = document.getElementById("saveOutfitAlert");
            if (alertBoxElement) alertBoxElement.style.display = "none";
            if (saveOutfitModalElement) saveOutfitModalElement.classList.add("active");
        });
    }

    const closeSaveOutfitModal = () => {
        if (saveOutfitModalElement) saveOutfitModalElement.classList.remove("active");
    };
    const closeSaveModalIconButton = document.getElementById("closeSaveModalBtn");
    const cancelSaveModalButton = document.getElementById("cancelSaveModalBtn");
    if (closeSaveModalIconButton) closeSaveModalIconButton.addEventListener("click", closeSaveOutfitModal);
    if (cancelSaveModalButton) cancelSaveModalButton.addEventListener("click", closeSaveOutfitModal);

    const saveOutfitSubmitForm = document.getElementById("saveOutfitForm");
    if (saveOutfitSubmitForm) {
        saveOutfitSubmitForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            const alertBoxElement = document.getElementById("saveOutfitAlert");
            if (alertBoxElement) alertBoxElement.style.display = "none";

            const outfitTitleName = document.getElementById("outfitName").value;
            const outfitDescriptionText = document.getElementById("outfitDesc").value;
            const selectedProductIds = getSelectedProductIdsArray();

            try {
                const apiResponse = await fetch("../routes/outfit.php?action=save_outfit", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ name: outfitTitleName, description: outfitDescriptionText, product_ids: selectedProductIds })
                });

                const apiResult = await apiResponse.json();
                if (apiResult.success) {
                    alert("Chúc mừng! Bộ phối đồ đã được lưu thành công vào tài khoản của bạn.");
                    closeSaveOutfitModal();
                    document.getElementById("outfitName").value = "";
                    document.getElementById("outfitDesc").value = "";
                    window.location.href = "profile.php";
                } else {
                    if (alertBoxElement) {
                        alertBoxElement.textContent = apiResult.message;
                        alertBoxElement.style.display = "block";
                    }
                }
            } catch (error) {
                if (alertBoxElement) {
                    alertBoxElement.textContent = "Không thể lưu phối đồ do lỗi kết nối.";
                    alertBoxElement.style.display = "block";
                }
            }
        });
    }

    // Thêm toàn bộ các mặt hàng trong bộ phối đồ vào giỏ hàng mua sắm
    const addWholeOutfitToCartButton = document.getElementById("addWholeOutfitToCart");
    if (addWholeOutfitToCartButton) {
        addWholeOutfitToCartButton.addEventListener("click", async () => {
            const selectedProductIds = getSelectedProductIdsArray();
            if (selectedProductIds.length === 0) {
                alert("Hãy chọn ít nhất 1 sản phẩm để thêm vào giỏ!");
                return;
            }

            let successfullyAddedCount = 0;
            const validItemsToAddToCart = Object.values(activeSelectedOutfit).filter(item => item !== null);

            for (const singleItem of validItemsToAddToCart) {
                try {
                    const apiResponse = await fetch("../routes/cart.php?action=add_to_cart", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            product_id: singleItem.id,
                            color: "Tiêu chuẩn",
                            size: "F",
                            quantity: 1
                        })
                    });
                    const apiResult = await apiResponse.json();
                    if (apiResult.success) successfullyAddedCount++;
                } catch (error) {
                    console.error("Lỗi thêm item: " + singleItem.id, error);
                }
            }

            alert(`Đã thêm thành công ${successfullyAddedCount}/${validItemsToAddToCart.length} sản phẩm của bộ đồ này vào giỏ hàng của bạn.`);
            if (typeof getCart === 'function') getCart();
        });
    }

    function getSelectedProductIdsArray() {
        const productIdList = [];
        if (activeSelectedOutfit.top) productIdList.push(activeSelectedOutfit.top.id);
        if (activeSelectedOutfit.bottom) productIdList.push(activeSelectedOutfit.bottom.id);
        if (activeSelectedOutfit.accessory) productIdList.push(activeSelectedOutfit.accessory.id);
        return productIdList;
    }
});

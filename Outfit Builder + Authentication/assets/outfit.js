// assets/js/outfit.js

// Lưu trữ ID đồ đang chọn mặc thử
let currentOutfit = {
    top: null,
    bottom: null,
    accessory: null // Bổ sung phụ kiện nếu có
};

// Hàm khi bấm nút "Mặc thử"
function selectItem(type, id, imgSrc) {
    if (type === 'top') {
        currentOutfit.top = id;
        document.getElementById('slot-top').innerHTML = `<img src="${imgSrc}" alt="Áo" style="max-width:100%; max-height:210px; object-fit:contain;">`;
    } else if (type === 'bottom') {
        currentOutfit.bottom = id;
        document.getElementById('slot-bottom').innerHTML = `<img src="${imgSrc}" alt="Quần" style="max-width:100%; max-height:210px; object-fit:contain;">`;
    }
}

// Hàm khi bấm nút "Lưu bộ phối đồ này"
function submitOutfit() {
    if (!currentOutfit.top || !currentOutfit.bottom) {
        alert('Vui lòng chọn đủ cả Áo và Quần trước khi lưu nhé!');
        return;
    }

    const outfitNameInput = document.getElementById('outfit-name');
    const outfitName = outfitNameInput ? outfitNameInput.value.trim() : 'Bộ phối của tôi';

    fetch('../controllers/OutfitController.php?action=save_outfit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            outfit_name: outfitName,
            top_id: currentOutfit.top,
            bottom_id: currentOutfit.bottom,
            accessory_id: currentOutfit.accessory
        })
    })
    .then(response => response.json())
    .then(data => {
        // HIỂN THỊ THẲNG LỜI NHẮC TỪ SERVER
        alert(data.message); 
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Không thể kết nối đến hệ thống.');
    });
}

// Hàm khi bấm nút Trái tim (Yêu thích)
function addToWishlist(productId) {
    // GỬI DỮ LIỆU DẠNG JSON ĐỒNG BỘ VỚI OUTFITCONTROLLER MỚI
    fetch('../controllers/OutfitController.php?action=add_wishlist', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => console.error('Error:', error));
}

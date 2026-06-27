<?php
// Form thêm Địa chỉ giao hàng (Thực thể con / yếu thuộc về Thực thể mạnh User Profile)
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($user['id']) ? $user['id'] : 0);
?>
<!-- MODAL THÊM ĐỊA CHỈ CON (WEAK ENTITY) CHO THỰC THỂ MẠNH USER -->
<div id="addAddressModal" class="custom-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
    <div class="custom-modal-content" style="background: #fff; width: 100%; max-width: 550px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: modalFadeIn 0.3s ease-out;">
        
        <!-- Modal Header -->
        <div style="padding: 20px 24px; background: #fafafa; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #222;">
                <i class="fa-solid fa-location-dot" style="color: #BF8A49; margin-right: 8px;"></i> Thêm Địa Chỉ Mới
            </h3>
            <button type="button" onclick="closeAddAddressModal()" style="background: none; border: none; font-size: 20px; color: #888; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body / Form -->
        <form id="addAddressForm" onsubmit="handleSaveAddress(event)" style="padding: 24px;">
            <!-- Khóa ngoại kết nối Thực thể mạnh (Strong Entity: User) -->
            <input type="hidden" name="user_id" id="addr_user_id" value="<?php echo htmlspecialchars($currentUserId); ?>">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13.5px; font-weight: 600; color: #444;">Họ và tên người nhận <span style="color: red;">*</span></label>
                    <input type="text" name="recipient_name" id="addr_recipient_name" required placeholder="Nhập họ và tên" style="height: 42px; border: 1px solid #ccc; border-radius: 6px; padding: 0 14px; font-size: 14px; outline: none;">
                </div>
                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13.5px; font-weight: 600; color: #444;">Số điện thoại <span style="color: red;">*</span></label>
                    <input type="tel" name="recipient_phone" id="addr_recipient_phone" required placeholder="Nhập số điện thoại" style="height: 42px; border: 1px solid #ccc; border-radius: 6px; padding: 0 14px; font-size: 14px; outline: none;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 15px;">
                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13.5px; font-weight: 600; color: #444;">Tỉnh / Thành phố <span style="color: red;">*</span></label>
                    <input type="text" name="city" id="addr_city" required placeholder="VD: TP. Hồ Chí Minh" style="height: 42px; border: 1px solid #ccc; border-radius: 6px; padding: 0 12px; font-size: 13.5px; outline: none;">
                </div>
                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13.5px; font-weight: 600; color: #444;">Quận / Huyện <span style="color: red;">*</span></label>
                    <input type="text" name="district" id="addr_district" required placeholder="VD: Quận 12" style="height: 42px; border: 1px solid #ccc; border-radius: 6px; padding: 0 12px; font-size: 13.5px; outline: none;">
                </div>
                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13.5px; font-weight: 600; color: #444;">Phường / Xã <span style="color: red;">*</span></label>
                    <input type="text" name="ward" id="addr_ward" required placeholder="VD: P. An Phú Đông" style="height: 42px; border: 1px solid #ccc; border-radius: 6px; padding: 0 12px; font-size: 13.5px; outline: none;">
                </div>
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 15px;">
                <label style="font-size: 13.5px; font-weight: 600; color: #444;">Địa chỉ cụ thể (Số nhà, đường) <span style="color: red;">*</span></label>
                <textarea name="street_address" id="addr_street_address" rows="2" required placeholder="Ví dụ: 112/35 Đường An Phú Đông" style="border: 1px solid #ccc; border-radius: 6px; padding: 10px 14px; font-size: 14px; outline: none; resize: vertical; font-family: inherit;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-size: 13.5px; font-weight: 600; color: #444; display: block; margin-bottom: 8px;">Loại địa chỉ</label>
                <div style="display: flex; gap: 15px;">
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 14px; color: #333;">
                        <input type="radio" name="address_type" value="home" checked style="accent-color: #BF8A49;"> Nhà Riêng
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 14px; color: #333;">
                        <input type="radio" name="address_type" value="office" style="accent-color: #BF8A49;"> Văn Phòng
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #333; user-select: none;">
                    <input type="checkbox" name="is_default" id="addr_is_default" value="1" style="width: 16px; height: 16px; accent-color: #BF8A49;">
                    Đặt làm địa chỉ mặc định
                </label>
            </div>

            <!-- Modal Footer Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #eee; padding-top: 18px;">
                <button type="button" onclick="closeAddAddressModal()" style="height: 42px; padding: 0 20px; background: #fff; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-weight: 600; color: #555; cursor: pointer; transition: all 0.2s;">
                    Trở Lại
                </button>
                <button type="submit" style="height: 42px; padding: 0 24px; background: #BF8A49; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; color: #fff; cursor: pointer; box-shadow: 0 3px 8px rgba(191, 138, 73, 0.3); transition: all 0.2s;">
                    Hoàn Thành
                </button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/addAddressProfile.js"></script>

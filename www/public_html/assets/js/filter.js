document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================================
    // 1. CƠ CHẾ GIỮ BỘ LỌC KHÔNG BỊ ẨN TRÊN ĐIỆN THOẠI
    // ========================================================
    const filterSidebar = document.getElementById('filterSidebar');
    const filterToggleBtn = document.querySelector('button[onclick*="filterSidebar"]');

    if (filterSidebar) {
        // [A] Kiểm tra bộ nhớ tạm, nếu trước khi load trang bộ lọc đang mở -> Mở lại ngay!
        if (sessionStorage.getItem('mobile_filter_open') === 'true') {
            filterSidebar.classList.add('show-mobile');
        }

        // [B] Tiếp quản nút bấm bật/tắt của HTML để hệ thống tự kiểm soát
        if (filterToggleBtn) {
            filterToggleBtn.removeAttribute('onclick'); // Xóa lệnh cũ tránh chập mạch
            filterToggleBtn.addEventListener('click', function() {
                filterSidebar.classList.toggle('show-mobile');
                // Ghi nhớ ngay trạng thái mỗi khi người dùng chủ động bấm nút
                sessionStorage.setItem('mobile_filter_open', filterSidebar.classList.contains('show-mobile'));
            });
        }

        // [C] LƯU TRẠNG THÁI NGAY TRƯỚC KHI TRANG BỊ TẢI LẠI
        // (Áp dụng khi người dùng check vào ô checkbox khiến form tự submit)
        window.addEventListener('beforeunload', function() {
            if (filterSidebar.classList.contains('show-mobile')) {
                sessionStorage.setItem('mobile_filter_open', 'true');
            } else {
                sessionStorage.setItem('mobile_filter_open', 'false');
            }
        });
    }

    // ========================================================
    // 2. CƠ CHẾ XỬ LÝ THANH TRƯỢT GIÁ TIỀN (GIỮ NGUYÊN BẢN GỐC CỦA BẠN)
    // ========================================================
    const minInput = document.getElementById('minPriceInput');
    const maxInput = document.getElementById('maxPriceInput');
    const minDisplay = document.getElementById('minPriceDisplay');
    const maxDisplay = document.getElementById('maxPriceDisplay');
    const filterForm = document.getElementById('filterForm');
    const sliderTrack = document.getElementById('sliderTrack');

    // Dừng khối lệnh giá tiền nếu trang không có thanh giá, nhưng không làm chết code mobile ở trên
    if (!minInput || !maxInput) return;

    const formatVND = new Intl.NumberFormat('vi-VN');
    const maxLimit = parseInt(maxInput.max); 

    function updatePriceDisplay() {
        let minVal = parseInt(minInput.value);
        let maxVal = parseInt(maxInput.value);

        // Chặn 2 nút đi xuyên qua nhau
        if (minVal > maxVal) {
            let tmp = minVal;
            minVal = maxVal;
            maxVal = tmp;
        }

        // Cập nhật con số Text
        minDisplay.innerText = formatVND.format(minVal) + ' đ';
        maxDisplay.innerText = formatVND.format(maxVal) + ' đ';

        // Tính toán CSS để bôi đỏ dải nằm giữa 2 nút
        if (sliderTrack && maxLimit > 0) {
            let leftPercent = (minVal / maxLimit) * 100;
            let rightPercent = 100 - ((maxVal / maxLimit) * 100);
            
            sliderTrack.style.left = leftPercent + '%';
            sliderTrack.style.right = rightPercent + '%';
        }
    }

    function handlePriceSubmit() {
        if (parseInt(minInput.value) > parseInt(maxInput.value)) {
            let temp = minInput.value;
            minInput.value = maxInput.value;
            maxInput.value = temp;
        }
        
        // Đảm bảo trạng thái cũng được lưu trước khi kéo giá tiền xong
        if (filterSidebar && filterSidebar.classList.contains('show-mobile')) {
            sessionStorage.setItem('mobile_filter_open', 'true');
        }
        
        filterForm.submit();
    }

    // Gắn sự kiện (Events)
    minInput.addEventListener('input', updatePriceDisplay);
    maxInput.addEventListener('input', updatePriceDisplay);
    minInput.addEventListener('change', handlePriceSubmit);
    maxInput.addEventListener('change', handlePriceSubmit);

    // Vẽ giao diện thanh trượt ngay khi load xong trang
    updatePriceDisplay();
});
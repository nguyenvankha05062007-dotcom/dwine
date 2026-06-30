<?php 
// 1. Khởi động SESSION nếu hệ thống chưa tự bật
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// 2. Lệnh cấm trình duyệt lưu Cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
        if (isset($_GET['page']) && $_GET['page'] === 'detail' && isset($product['name'])) {
            echo htmlspecialchars($product['name']) . " | D-WINE";
        } else {
            echo "D-WINE | Tràn Ly Đam Mê";
        }
        ?>
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css?v=5">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
/* Tùy chỉnh hộp thoại trong suốt sang trọng */
.wine-glass-modal .modal-content {
    background: rgba(20, 3, 7, 0.65) !important; /* Tối màu pha chút ánh đỏ rượu */
    backdrop-filter: blur(15px) !important; /* Làm mờ nền phía sau */
    -webkit-backdrop-filter: blur(15px) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 20px !important;
    color: #ffffff !important;
    box-shadow: 0 8px 32px 0 rgba(141, 14, 44, 0.3) !important; /* Bóng đổ có ánh đỏ */
}

/* Định dạng các ô nhập liệu tối giản gạch chân */
.wine-glass-modal .form-control {
    background: transparent !important;
    border: none !important;
    border-bottom: 2px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 0 !important;
    color: #ffffff !important;
    padding-left: 0 !important;
    transition: all 0.3s ease;
}

.wine-glass-modal .form-control:focus {
    border-bottom-color: #e61c47 !important; /* Màu đỏ rượu sáng khi click vào */
    box-shadow: none !important;
}

.wine-glass-modal .form-control::placeholder {
    color: rgba(255, 255, 255, 0.5) !important;
}

/* Nút bấm đỏ rượu đồng bộ trang chủ */
.wine-glass-modal .btn-wine {
    background: #bd092f !important; /* Màu đỏ mận/đỏ rượu giống nút Xem thêm */
    color: #ffffff !important;
    border: none !important;
    border-radius: 30px !important;
    font-weight: bold;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.wine-glass-modal .btn-wine:hover {
    background: #e61c47 !important;
    box-shadow: 0 0 15px rgba(230, 28, 71, 0.5);
}
/* Tuyệt chiêu ép dọn dẹp các lớp nền đen bị kẹt khi chuyển đổi modal */
.modal-backdrop + .modal-backdrop {
    display: none !important;
}
body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

.custom-user-dropdown {
    position: relative;
    padding-bottom: 15px !important; /* Tạo một vùng đệm vô hình bên dưới chữ để giữ chuột */
    margin-bottom: -15px !important;
}

.custom-user-dropdown:hover .dropdown-menu {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.custom-user-dropdown .dropdown-menu {
    display: none;
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    margin-top: 0 !important; /* Đẩy sát menu lên vùng đệm */
    z-index: 999999 !important;
}

/* Chiếc cầu vô hình kết nối giữa chữ và menu con */
.custom-user-dropdown::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 120%; /* Phủ kín toàn bộ khoảng trống từ chữ xuống menu */
    z-index: -1;
}
.age-verify-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(10, 10, 10, 0.96); /* Nền tối mờ che toàn trang */
    z-index: 999999; /* Đảm bảo luôn đè lên tất cả các menu, chatbox khác */
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px); /* Làm mờ nhẹ nền web phía sau */
}

.age-verify-box {
    background: linear-gradient(135deg, #161616 0%, #2a050b 100%);
    border: 1px solid #5a101d;
    padding: 3rem 2rem;
    border-radius: 12px;
    max-width: 550px;
    width: 90%;
    text-align: center;
    box-shadow: 0 15px 40px rgba(200, 16, 46, 0.25);
    animation: fadeInAgeBox 0.4s ease-out;
}

.age-verify-logo {
    font-family: 'Oswald', sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 2px;
    margin-bottom: 1.5rem;
}

.age-verify-title {
    color: #fff;
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 1rem;
}

.age-verify-text {
    color: #b3b3b3;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2.5rem;
}

.age-verify-buttons {
    display: flex;
    gap: 1.2rem;
    justify-content: center;
}

.btn-age-verify {
    padding: 0.8rem 2rem;
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
    width: 50%;
}

.btn-age-success {
    background-color: #c8102e; /* Màu đỏ rượu đồng bộ */
    color: #fff;
    box-shadow: 0 4px 15px rgba(200, 16, 46, 0.4);
}

.btn-age-success:hover {
    background-color: #e01638;
    transform: translateY(-2px);
}

.btn-age-fail {
    background-color: #262626;
    color: #a3a3a3;
    border: 1px solid #333;
}

.btn-age-fail:hover {
    background-color: #333;
    color: #fff;
}

@keyframes fadeInAgeBox {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}

/* Ngăn cuộn trang web khi popup đang hiển thị */
body.modal-open-age {
    overflow: hidden;
}
</style>

</head>
<body>
<div id="age-verify-modal" class="age-verify-overlay" style="display: none;">
    <div class="age-verify-box">
        <div class="age-verify-logo">
            <span style="color: var(--accent-red, #c8102e);">WINE</span> SHOP
        </div>
        <h2 class="age-verify-title">XÁC NHẬN ĐỘ TUỔI CỦA BẠN</h2>
        <p class="age-verify-text">
            Nội dung trên trang web này không dành cho người dưới 18 tuổi. <br>
            Bằng việc truy cập vào website, bạn khẳng định mình đã đủ tuổi hợp pháp để sử dụng đồ uống có cồn.
        </p>
        <div class="age-verify-buttons">
            <button class="btn-age-verify btn-age-success" onclick="verifyAge(true)">Tôi đã đủ 18 tuổi</button>
            <button class="btn-age-verify btn-age-fail" onclick="verifyAge(false)">Rời khỏi trang</button>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg">
    <div class="container nav-grid">
        <a class="navbar-brand" href="/">D-WINE</a>
        <button class="nav-toggle-btn" type="button"
        onclick="document.getElementById('navCenterMenu').classList.toggle('show-mobile')">
         <i class="bi bi-list"></i>
        </button>

        <!-- THANH TÌM KIẾM SẢN PHẨM -->
        <div class="nav-search position-relative me-2 d-none d-lg-block" style="width: 350px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark border-secondary text-light" style="border-right: none;"><i class="bi bi-search"></i></span>
                    
                <input type="text" id="live-search-input" 
                    class="form-control bg-dark text-white border-secondary" 
                    style="border-left: none; box-shadow: none;" 
                    placeholder="Tìm tên rượu..." autocomplete="off"
                    data-logged-in="<?= isset($_SESSION['username']) ? 'true' : 'false' ?>">
                        
            </div>
            <div id="search-results" class="dropdown-menu dropdown-menu-dark shadow w-100" style="display: none; position: absolute; top: 100%; left: 0; z-index: 1050; max-height: 350px; overflow-y: auto; background: rgba(20, 3, 7, 0.95);">
            </div>
        </div>

        <div class="navbar-nav d-flex justify-content-center gap-3 nav-center" id="navCenterMenu">
            <a class="nav-link" href="/?page=home">Trang chủ</a>
            <a class="nav-link" href="/?page=products">Sản phẩm</a>
            <a class="nav-link" href="/?page=quality">Đánh giá</a>
        </div>

        <div class="navbar-nav d-flex flex-row align-items-center gap-2 nav-right flex-wrap">        
            <?php if (isset($_SESSION['username'])): ?>
                 <a class="nav-link" href="/?page=cart">
                        🛒 Giỏ hàng <?php if (!empty($_SESSION['cart'])): ?><span class="badge bg-danger"><?= array_sum($_SESSION['cart']) ?></span><?php endif; ?>
                 </a>
                <div class="nav-item dropdown custom-user-dropdown">
                    <a class="nav-link dropdown-toggle fw-bold" href="#" id="userDropdown" role="button"
   data-bs-toggle="dropdown" aria-expanded="false"
   style="color: #e61c47 !important; padding-bottom: 10px;">
                        🍇 <?= htmlspecialchars($_SESSION['fullname']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-secondary shadow" aria-labelledby="userDropdown" style="background: rgba(20, 3, 7, 0.98) !important;">
                        
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li>
                                <a class="dropdown-item text-warning small py-2 fw-bold" href="/index.php?page=admin">
                                    <i class="bi bi-shield-lock me-2"></i>Vào Trang Quản Trị
                                </a>
                            </li>
                        <?php endif; ?>
                        <li><a class="dropdown-item small py-2" href="/index.php?page=order_history"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn</a></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><a class="dropdown-item text-danger small py-2 fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</a>
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>
</nav> 
<script src="/assets/js/live_search.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const isAdmin = <?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'true' : 'false'; ?>;
    if (isAdmin) return;
    const isVerified = sessionStorage.getItem("age_verified");
        if (!isVerified) {
        const modal = document.getElementById("age-verify-modal");
        if (modal) {
            modal.style.display = "flex";
            document.body.classList.add("modal-open-age");
        }
    }
});

function verifyAge(isOfAge) {
    if (isOfAge) {
    sessionStorage.setItem("age_verified", "true");
    document.getElementById("age-verify-modal").style.display = "none";
    document.body.classList.remove("modal-open-age");
    } else {
        
        window.location.href = "https://www.google.com";
    }
}
function checkDeviceAndAdjust() {
    const width = window.innerWidth;
    let deviceType = 'desktop';
    
    if (width <= 768) {
        deviceType = 'mobile';
    } else if (width <= 991) {
        deviceType = 'tablet';
    }

    // Gắn thẻ data-device vào body để CSS có thể nhận diện và tự động đổi giao diện
    document.body.setAttribute('data-device', deviceType);
    
    // Tùy chọn: Có thể thu gọn/ẩn bớt các thành phần nặng nếu là mobile ở đây
}

// Chạy hàm ngay khi tải trang và mỗi khi người dùng thay đổi kích thước cửa sổ
window.addEventListener('resize', checkDeviceAndAdjust);
document.addEventListener('DOMContentLoaded', checkDeviceAndAdjust);

window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        window.location.reload(); 
    }
});
</script>
<?php 
// SỬA LẠI ĐƯỜNG DẪN CHUẨN:
include_once __DIR__ . '/../user/login.php'; 
include_once __DIR__ . '/../user/register.php'; 
?>
<div class="container mt-4">
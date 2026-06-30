

<footer class="site-footer mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="footer-brand">D-WINE</h5>
                <p class="footer-desc small">
                    Tuyển chọn rượu vang, whisky, vodka chính hãng từ các thương hiệu nổi tiếng trên thế giới. Giao hàng nhanh, uy tín.
                </p>
            </div>

            <div class="col-md-2 mb-4">
                <h6 class="footer-heading">Liên kết</h6>
                 <?php if (!isset($_SESSION['username'])): ?>
                    <ul class="list-unstyled">
                    <li><a href="?page=home" class="footer-link">Trang chủ</a></li>
                    <li><a href="?page=products" class="footer-link">Sản phẩm</a></li>
                    <li><a href="?page=quality#reviews-section" class="footer-link">Đánh giá</a></li>
                    <li><a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</a></li>
                </ul>
                <?php else: ?>
                <ul class="list-unstyled">
                    <li><a href="?page=home" class="footer-link">Trang chủ</a></li>
                    <li><a href="?page=products" class="footer-link">Sản phẩm</a></li>
                    <li><a href="?page=quality#reviews-section" class="footer-link">Đánh giá</a></li>
                </ul>
                <?php endif; ?>
            </div>
    
            <div class="col-md-3 mb-4">
                <h6 class="footer-heading">Liên hệ</h6>
                <ul class="list-unstyled footer-contact small">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Võ Văn kiệt,Ninh Kiều,Cần Thơ</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>0909 123 456</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>contact@dwine.vn</li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="footer-heading">Theo dõi chúng tôi</h6>
                <div class="footer-social">
                    <a href="#" class="footer-social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social-icon"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom text-center py-3">
        <p class="mb-0 small">&copy; 2026 D-WINE. Đồ án CNTT - Trần Quốc Đạt.</p>
    </div>
</footer>

<script src="/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword(inputId, button) {
    const passwordInput = document.getElementById(inputId);
    const icon = button.querySelector('i'); // Tìm thẻ <i> (con mắt) bên trong nút
    
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        // Đổi từ mắt đóng thành mắt mở
        icon.className = "bi bi-eye";
    } else {
        passwordInput.type = "password";
        // Đổi từ mắt mở thành mắt đóng lại
        icon.className = "bi bi-eye-slash";
    }
}
</script>
<script> 
    // Hàm chuyển đổi mượt mà giữa các Modal (Không bao giờ lo kẹt màn hình đen)
function switchModal(currentModalId, nextModalId) {
    const currentEl = document.getElementById(currentModalId);
    const nextEl = document.getElementById(nextModalId);
    
    const currentModal = bootstrap.Modal.getInstance(currentEl) || new bootstrap.Modal(currentEl);
    const nextModal = bootstrap.Modal.getInstance(nextEl) || new bootstrap.Modal(nextEl);

    // Lắng nghe sự kiện khi modal cũ đã biến mất HOÀN TOÀN
    currentEl.addEventListener('hidden.bs.modal', function onHidden() {
        // Dọn dẹp thủ công toàn bộ lớp nền đen bị thừa (nếu có) trước khi mở cái mới
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Kích hoạt mở modal tiếp theo
        nextModal.show();
        
        // Hủy bỏ lắng nghe để tránh lặp lại
        currentEl.removeEventListener('hidden.bs.modal', onHidden);
    });

    // Ra lệnh ẩn modal hiện tại
    currentModal.hide();
}
</script>
<script> 
    // 1. Hàm ẩn hiện mật khẩu dùng chung cho cả Login và Register
function togglePassword(inputId, buttonEl) {
    const passwordInput = document.getElementById(inputId);
    const icon = buttonEl.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    }
}

// 2. Hàm chuyển đổi mượt mà giữa các Modal không bị kẹt màn hình đen
function switchModal(currentModalId, nextModalId) {
    const currentEl = document.getElementById(currentModalId);
    const nextEl = document.getElementById(nextModalId);
    
    const currentModal = bootstrap.Modal.getInstance(currentEl) || new bootstrap.Modal(currentEl);
    const nextModal = bootstrap.Modal.getInstance(nextEl) || new bootstrap.Modal(nextEl);

    currentModal.hide();

    currentEl.addEventListener('hidden.bs.modal', function onHidden() {
        nextModal.show();
        currentEl.removeEventListener('hidden.bs.modal', onHidden);
    });
}

document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    e.preventDefault(); 

    const errorDiv = document.getElementById('registerError');
    errorDiv.classList.add('d-none'); 

    const formData = new FormData(this);

    fetch('register_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
        showToast(data.message, true); 
        setTimeout(() => {
        if (data.role === 'admin') {
            window.location.href = 'index.php?page=admin';
        } else {
            window.location.reload();
        }
    }, 2000);

        } else {
            errorDiv.textContent = data.message;
            errorDiv.classList.remove('d-none');
        }
    })
    .catch(error => {
        console.error('Lỗi kết nối hệ thống:', error);
        showToast('Lỗi kết nối hệ thống đăng ký!', false);
    });
});
</script>
<script>
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    e.preventDefault(); // Chặn load lại trang

    const errorDiv = document.getElementById('loginError');
    errorDiv.classList.add('d-none'); // Ẩn thông báo lỗi cũ đi mỗi lần bấm lại

    const formData = new FormData(this);

    fetch('login_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // ĐÃ SỬA: Thay alert bằng showToast
            showToast(data.message, true); 
            
            // Chờ 2 giây cho người dùng nhìn thông báo rồi mới refresh trang cập nhật Session
           setTimeout(() => {
    if (data.role === 'admin') {
        window.location.href = 'index.php?page=admin';
    } else {
        window.location.reload();
    }
}, 2000);
        } else {
            // Hiện hộp lỗi màu đỏ và in thông báo sai mật khẩu/tài khoản công khai trên form
            errorDiv.textContent = data.message;
            errorDiv.classList.remove('d-none');
        }
    })
    .catch(error => {
        console.error('Lỗi kết nối hệ thống đăng nhập:', error);
        showToast('Lỗi kết nối hệ thống đăng nhập!', false);
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Kiểm tra trạng thái đăng nhập từ PHP Session
    const isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;

    // 2. Tìm tất cả đường link cần bảo mật (nhấp vào là bắt đăng nhập)
    // Đoạn này lấy các link có chứa page=detail, page=products, page=quality, hoặc nút "Xem thêm"
    const protectedLinks = document.querySelectorAll('a[href*="page=detail"], a[href*="page=products"], a[href*="page=quality"], .btn-view-more, a[href*="id="]');

    protectedLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            // Nếu chưa đăng nhập, lập tức chặn hành động chuyển trang
            if (!isLoggedIn) {
                e.preventDefault(); 
                
                // 3. Gọi lệnh mở chuẩn của Bootstrap 5 dựa vào đúng ID "loginModal" của bạn
                var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
                myModal.show(); 
            }
        });
    });
});
</script>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="liveToast" class="toast align-items-center text-white bg-dark border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <span id="toastIcon">🍷</span>
                <span id="toastMessage">Thông báo ở đây...</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
// Hàm JavaScript dùng chung để hiển thị thông báo đẹp thay cho alert
function showToast(message, isSuccess = true) {
    const toastEl = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    // Đổi màu nền và icon tùy theo thành công hay thất bại
    if (isSuccess) {
        toastEl.className = "toast align-items-center text-white border-0 shadow";
        toastEl.style.backgroundColor = "#722F37"; // Màu đỏ rượu vang quý phái giống tông web của bạn
        toastIcon.innerText = "🍷";
    } else {
        toastEl.className = "toast align-items-center text-white bg-danger border-0 shadow";
        toastIcon.innerText = "⚠️";
    }
    
    toastMessage.innerText = message;
    
    // Kích hoạt hiển thị Toast của Bootstrap 5
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}
</script>
</body>
</html>
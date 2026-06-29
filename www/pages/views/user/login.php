<div class="modal fade wine-glass-modal" id="loginModal" tabindex="-1" aria-hidden="true" data-bs-config='{"backdrop": true}'>
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content p-4 position-relative">
            <div class="text-center mt-3 mb-2">
                <span style="font-size: 2.5rem;">🍷</span>
                <h3 class="fw-bold text-uppercase mt-2" style="letter-spacing: 1px;">Đăng Nhập</h3>
                <p class="small text-white-50">Khai mở đặc quyền của sự thượng lưu</p>
            </div>
            
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end m-3" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body">
                <form id="loginForm" method="POST">
                    
                    <div id="loginError" class="alert alert-danger d-none small py-2 text-center mb-3" role="alert" style="border-radius: 6px;"></div>

                    <div class="mb-4">
                        <label class="form-label small text-uppercase text-white-50 fw-bold" style="letter-spacing:0.5px;">Tên Đăng Nhập</label>
                        <input type="text" class="form-control" name="username" placeholder="Nhập tên đăng nhập" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small text-uppercase text-white-50 fw-bold" style="letter-spacing:0.5px;">Mật Khẩu</label>
                        <div class="input-group border-bottom" style="border-color:rgba(255,255,255,0.3) !important;">
                            <input type="password" class="form-control border-0 mb-0" id="loginPassword" name="password" placeholder="............" required style="padding-bottom: 8px;">
                            <button class="btn border-0 text-white-50 px-2" type="button" onclick="togglePassword('loginPassword', this)" style="background: transparent !important; box-shadow: none !important;">
                                <i class="bi bi-eye-slash"></i> 
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 small">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label text-white-50" for="rememberMe">Ghi nhớ</label>
                        </div>
                        <a href="#" class="text-white-50 text-decoration-none hover-light">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-wine w-100 py-2.5 text-uppercase shadow-sm">Đăng Nhập</button>
                </form>
            </div>
            
            <div class="modal-footer border-0 justify-content-center pt-0">
                <p class="small text-white-50 mb-0">Chưa có tài khoản? 
                    <a href="javascript:void(0)" class="text-white fw-bold text-decoration-none ms-1" onclick="switchModal('loginModal', 'registerModal')">Đăng ký ngay</a>
                </p>
            </div>

        </div>
    </div>
</div>
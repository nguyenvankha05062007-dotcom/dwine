<div class="modal fade wine-glass-modal" id="registerModal" tabindex="-1" aria-hidden="true" data-bs-config='{"backdrop": true}'>
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content p-4 position-relative">
            
            <div class="text-center mt-3 mb-2">
                <span style="font-size: 2.5rem;">🍇</span>
                <h3 class="fw-bold text-uppercase mt-2" style="letter-spacing: 1px;">Đăng Ký</h3>
                <p class="small text-white-50">Gia nhập cộng đồng tinh hoa Wine Shop</p>
            </div>

            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

            <div class="modal-body">
                <form id="registerForm" method="POST">
                    
                    <div id="registerError" class="alert alert-danger d-none small py-2 text-center mb-3" role="alert" style="border-radius: 6px;"></div>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase text-white-50 fw-bold">Họ và tên</label>
                        <input type="text" class="form-control" name="fullname" placeholder="Nguyễn Văn A" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase text-white-50 fw-bold">Địa chỉ Email</label>
                        <input type="email" class="form-control" name="email" placeholder="example@gmail.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-uppercase text-white-50 fw-bold">Tên đăng nhập</label>
                        <input type="text" class="form-control" name="username" placeholder="username123" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-uppercase text-white-50 fw-bold">Mật khẩu</label>
                        <div class="input-group border-bottom" style="border-color: rgba(255, 255, 255, 0.3) !important;">
                           <input type="password" class="form-control border-0 mb-0" id="regPassword" name="password" placeholder="••••••••" required style="padding-bottom: 8px;">
        
                           <button class="btn border-0 text-white-50 px-2" type="button" onclick="togglePassword('regPassword', this)" style="background: transparent !important; box-shadow: none !important;">
                              <i class="bi bi-eye-slash"></i>
                           </button>
                       </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small text-uppercase text-white-50 fw-bold">Xác nhận mật khẩu</label>
                        <div class="input-group border-bottom" style="border-color: rgba(255, 255, 255, 0.3) !important;">
                          <input type="password" class="form-control border-0 mb-0" id="regConfirmPassword" name="confirm_password" placeholder="••••••••" required style="padding-bottom: 8px;">
        
                           <button class="btn border-0 text-white-50 px-2" type="button" onclick="togglePassword('regConfirmPassword', this)" style="background: transparent !important; box-shadow: none !important;">
                             <i class="bi bi-eye-slash"></i>
                           </button>
                        </div>
                    </div>

                   

                    <button type="submit" class="btn btn-wine w-100 py-2.5 text-uppercase">Tạo Tài Khoản</button>
                </form>
            </div>

            <div class="modal-footer border-0 justify-content-center pt-0">
                <p class="small text-white-50 mb-0">Đã có tài khoản? 
                   <a href="javascript:void(0)" class="text-white fw-bold text-decoration-none ms-1" onclick="switchModal('registerModal', 'loginModal')">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
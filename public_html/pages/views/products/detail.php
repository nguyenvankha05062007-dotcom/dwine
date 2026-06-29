</div>

<link rel="stylesheet" href="assets/css/style.css">
<div class="container mt-4">
    <nav aria-label="breadcrumb" class="p-3 mb-4 rounded" 
         style="background: linear-gradient(90deg, #1a1a1a 0%, rgba(138, 14, 32, 0.15) 100%); border-left: 3px solid var(--accent-red);">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="?page=home" class="text-decoration-none" style="color: var(--text-muted);">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="?page=products" class="text-decoration-none" style="color: var(--text-muted);">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #fff; font-weight: 600;"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="card mb-5" 
         style="background: linear-gradient(135deg, var(--bg-card) 55%, #4a0812 150%); border: 1px solid #5a101d; box-shadow: 0 10px 30px rgba(200, 16, 46, 0.15);">
        <div class="row g-0">
            <div class="col-md-5 d-flex align-items-center justify-content-center p-4" style="background-color: #fff; border-radius: 4px 0 0 4px;">
                <img src="images/<?= htmlspecialchars($product['image']) ?>" 
                     class="img-fluid rounded-start" alt="<?= htmlspecialchars($product['name']) ?>"
                     style="max-height: 450px; object-fit: contain;">
            </div>
            
            <div class="col-md-7">
                <div class="card-body p-5 d-flex flex-column h-100">
                    <h2 class="card-title mb-2" style="font-size: 2.2rem; font-weight: 700; font-family: 'Oswald', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                        <?= htmlspecialchars($product['name']) ?>
                    </h2>
                    <h3 class="text-danger fw-bold mb-4" style="font-size: 1.8rem;">
                        <?= number_format($product['price'], 0, ',', '.') ?> đ
                    </h3>

                    <p class="card-text text-light mb-4 flex-grow-1" style="font-size: 1.1rem; line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>

                    <ul class="list-group list-group-flush mb-4" style="background-color: transparent;">
                        <li class="list-group-item text-light d-flex justify-content-between" style="background-color: transparent; border-color: rgba(255,255,255,0.1); padding-left: 0;">
                            <strong>Xuất xứ:</strong> <span><?= htmlspecialchars($product['origin']) ?></span>
                        </li>
                        <li class="list-group-item text-light d-flex justify-content-between" style="background-color: transparent; border-color: rgba(255,255,255,0.1); padding-left: 0;">
                            <strong>Độ mạnh:</strong> <span><?= htmlspecialchars($product['alcohol_percent']) ?>%</span>
                        </li>
                        <li class="list-group-item text-light d-flex justify-content-between" style="background-color: transparent; border-color: rgba(255,255,255,0.1); padding-left: 0;">
                            <strong>Dung tích:</strong> <span><?= htmlspecialchars($product['volume_ml']) ?> ml</span>
                        </li>
                        <li class="list-group-item text-light d-flex justify-content-between" style="background-color: transparent; border-color: rgba(255,255,255,0.1); padding-left: 0;">
                            <strong>Tình trạng:</strong>
                            <?php if ($product['stock'] > 0): ?>
                                <span class="text-success fw-bold">Còn hàng (<?= $product['stock'] ?>)</span>
                            <?php else: ?>
                                <span class="text-danger fw-bold">Hết hàng</span>
                            <?php endif; ?>
                        </li>
                    </ul>

                    <?php if ($product['stock'] > 0): ?>
                        <div class="d-flex gap-3 align-items-center w-100">
                            <div class="input-group" style="max-width: 140px; border: 1px solid #5a101d; border-radius: 6px; overflow: hidden;">
                                <button class="btn btn-outline-secondary border-0 text-white fw-bold bg-dark" type="button" onclick="changeBuyQty(-1)" style="width: 40px;">-</button>
                                <input type="number" id="buy-quantity" class="form-control text-center bg-dark text-white border-0 fw-bold" value="1" min="1" max="<?= $product['stock'] ?>" oninput="updateBuyQtyLink(this)" style="font-size: 1.1rem;">
                                <button class="btn btn-outline-secondary border-0 text-white fw-bold bg-dark" type="button" onclick="changeBuyQty(1)" style="width: 40px;">+</button>
                            </div>
                            
                          <a href="?page=cart&action=add&id=<?= $product['id'] ?>&qty=1"
                              id="btn-add-cart"
                              data-base-url="?page=cart&action=add&id=<?= $product['id'] ?>"
                              class="btn btn-wine">Thêm vào giỏ</a>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg w-100 text-uppercase fw-bold" disabled>
                            Hết hàng tạm thời
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($relatedProducts)): ?>
        <div class="mt-5 mb-4">
            <h3 class="section-title" style="font-size: 1.5rem;">Sản phẩm tương tự</h3>
            <div class="row mt-4">
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 position-relative" style="box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                            <?php if ($related['stock'] <= 0): ?>
                                <span class="badge bg-secondary position-absolute top-0 end-0 m-2" style="z-index: 2;">Hết hàng</span>
                            <?php endif; ?>

                            <img src="images/<?= htmlspecialchars($related['image']) ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($related['name']) ?>"
                                 style="height: 180px; object-fit: contain; background-color: #fff; padding: 10px; opacity: <?= $related['stock'] <= 0 ? '0.6' : '1' ?>;">
                            
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title text-truncate mb-2" title="<?= htmlspecialchars($related['name']) ?>" style="font-size: 0.95rem; font-family: 'Inter', sans-serif; font-weight: 600;">
                                    <?= htmlspecialchars($related['name']) ?>
                                </h6>

                                <p class="card-text text-danger fw-bold mb-3 mt-auto" style="font-size: 1rem;"><?= number_format($related['price'], 0, ',', '.') ?> đ</p>
                                <a href="?page=detail&id=<?= $related['id'] ?>" class="btn btn-wine">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="review-form-box mt-5 p-4 rounded-3" style="background-color: var(--bg-card, #161616); border: 1px solid #2a2a2a;">
        <h4 class="text-uppercase fw-bold mb-3" style="color: #fff; letter-spacing: 1px;">Để lại cảm nhận của bạn</h4>
        
        <form id="formReviewProduct">
            <input type="hidden" name="product_id" value="<?php echo isset($_GET['id']) ? (int)$_GET['id'] : 0; ?>">

            <div class="mb-3" style="max-width: 250px;">
                <label class="form-label small text-white-50 fw-bold">Chọn số sao bình chọn:</label>
                <select class="form-select text-warning" name="rating" style="background-color: #121212; border: 1px solid #333; color: #fff;">
                    <option value="5" selected>⭐⭐⭐⭐⭐ (Tuyệt hảo)</option>
                    <option value="4">⭐⭐⭐⭐ (Rất ngon)</option>
                    <option value="3">⭐⭐⭐ (Khá ổn)</option>
                    <option value="2">⭐⭐ (Bình thường)</option>
                    <option value="1">⭐ (Kém chất lượng)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small text-white-50 fw-bold">Cảm nhận hương vị thực tế:</label>
                <textarea class="form-control text-white" name="comment" rows="4" placeholder="Nhập nhận xét về hậu vị, nồng độ cồn, hoặc cách đóng gói chai rượu này..." required style="background-color: #121212; border: 1px solid #333; resize: none;"></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-wine px-4 py-2 text-uppercase fw-bold" style="font-size: 0.85rem;">Gửi đánh giá 🍷</button>
            </div>
        </form>
    </div>

    <script>
    // Xử lý gửi Form đánh giá
    document.getElementById('formReviewProduct').addEventListener('submit', function(e) {
        e.preventDefault(); 

        const formData = new FormData(this);

        fetch('index.php?page=review_submit', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.status === 'success') {
                    showToast(data.message, true); 
                    setTimeout(() => {
                        location.reload(); 
                    }, 2000);
                } else {
                    showToast(data.message, false);
                }
            } catch (err) {
                console.error("Dữ liệu trả về không phải JSON chuẩn:", text);
                showToast("Có lỗi hệ thống trong quá trình xử lý phản hồi!", false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast("Không thể kết nối đến máy chủ!", false);
        });
    });
        // Hàm xử lý chung để cập nhật giá trị và link giỏ hàng
        function updateBuyQtyLink(input) {
            let val = parseInt(input.value);
            const max = parseInt(input.getAttribute('max'));
            
            // Nếu nhập chữ hoặc số âm thì trả về 1, nếu vượt quá tồn kho thì trả về max
            if (isNaN(val) || val < 1) val = 1;
            if (val > max) val = max;
            
            // Cập nhật lại giá trị hiển thị trên input
            input.value = val;
        
            // Cập nhật href của nút "Thêm vào giỏ"
            const btn = document.getElementById('btn-add-cart');
            const baseUrl = btn.getAttribute('data-base-url');
            btn.href = baseUrl + '&qty=' + val;
        }

        // Xử lý khi nhấn nút + hoặc -
        function changeBuyQty(delta) {
            const input = document.getElementById('buy-quantity');
            let val = parseInt(input.value) || 1;
            input.value = val + delta;
            updateBuyQtyLink(input); 
        }
    </script>
    
</div>
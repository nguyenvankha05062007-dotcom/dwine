<h2 class="section-title">Giỏ hàng của bạn</h2>
<?php if (!empty($error_cart)): ?>
    <div class="alert alert-warning text-dark fw-bold mb-3">
        ⚠️ <?= htmlspecialchars($error_cart) ?>
    </div>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <p class="text-muted">Giỏ hàng đang trống.</p>
    <a href="?page=products" class="btn btn-wine">Tiếp tục mua sắm</a>
<?php else: ?>

<form method="POST" action="?page=cart&action=update">
    <table class="table table-dark align-middle">
        <thead>
            <tr><th>Sản phẩm</th><th>Giá</th><th style="width:100px;">Số lượng</th><th>Tạm tính</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td class="d-flex align-items-center gap-2">
                        <img src="images/<?= htmlspecialchars($item['product']['image']) ?>" style="width:50px;height:50px;object-fit:contain;background:#fff;">
                        <?= htmlspecialchars($item['product']['name']) ?>
                    </td>
                    <td><?= number_format($item['product']['price'], 0, ',', '.') ?> đ</td>
                    <td>
                        <input type="number" 
                               name="quantities[<?= $item['product']['id'] ?>]" 
                               value="<?= $item['quantity'] ?>" 
                               min="1" 
                               max="<?= $item['product']['stock'] ?>" 
                               oninput="this.value = Math.min(Math.max(this.value, 1), <?= $item['product']['stock'] ?>)"
                               class="form-control form-control-sm">
                    </td>
                    <td><?= number_format($item['subtotal'], 0, ',', '.') ?> đ</td>
                    <td><a href="?page=cart&action=remove&id=<?= $item['product']['id'] ?>" class="btn btn-sm btn-outline-danger">Xóa</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button type="submit" class="btn btn-outline-light btn-sm">Cập nhật giỏ hàng</button>
        <h5 class="mb-0">Tổng cộng: <span class="text-danger"><?= number_format($total, 0, ',', '.') ?> đ</span></h5>
    </div>
</form>

<?php if (isset($_SESSION['user_id'])): ?>
    <form method="POST" action="?page=cart&action=checkout" class="card p-4">
        <h5 class="mb-3">Thông tin đặt hàng</h5>
        <div class="mb-3">
            <label class="form-label">Địa chỉ giao hàng</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-wine">Đặt hàng</button>
    </form>
<?php else: ?>
    <p class="text-muted">Vui lòng <a href="?page=login" class="text-review-product">đăng nhập</a> để đặt hàng.</p>
<?php endif; ?>
<?php endif; ?>
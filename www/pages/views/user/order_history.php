<?php
$status_labels = [
    'pending'   => '⏳ Chờ xử lý',
    'shipping'  => '🚚 Đang giao',
    'done'      => '✅ Hoàn thành',
    'cancelled' => '❌ Đã hủy',
];
$status_colors = [
    'pending'   => '#f39c12',
    'shipping'  => '#2980b9',
    'done'      => '#27ae60',
    'cancelled' => '#e74c3c',
];
?>

<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container" style="max-width:860px;margin:40px auto;padding:0 16px">

  <h2 style="font-family:'Oswald',sans-serif;font-size:28px;margin-bottom:6px">
    📦 Lịch sử đơn hàng
  </h2>
  <p style="color:#888;margin-bottom:28px">Xin chào <strong><?= htmlspecialchars($_SESSION['fullname']) ?></strong>, đây là danh sách đơn hàng của bạn.</p>

  <?php if (empty($orders)): ?>
    <div style="text-align:center;padding:60px 0;color:#aaa">
      <div style="font-size:48px;margin-bottom:12px">🛒</div>
      <p style="font-size:16px">Bạn chưa có đơn hàng nào.</p>
      <a href="index.php?page=products" style="display:inline-block;margin-top:14px;padding:10px 24px;background:#c0392b;color:#fff;border-radius:6px;text-decoration:none">Mua sắm ngay</a>
    </div>

  <?php else: ?>
    <?php foreach ($orders as $o): ?>
    <div style="background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:18px;overflow:hidden">
      
      <!-- Header đơn -->
      <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;background:#fafafa;border-bottom:1px solid #f0f0f0">
        <div>
          <span style="font-weight:700;color:#333">Đơn #<?= $o['id'] ?></span>
          <span style="margin-left:12px;font-size:13px;color:#888"><?= $o['created_at'] ?></span>
        </div>
        <span style="padding:4px 14px;border-radius:20px;font-size:12px;font-weight:700;background:<?= $status_colors[$o['status']] ?>22;color:<?= $status_colors[$o['status']] ?>">
          <?= $status_labels[$o['status']] ?? $o['status'] ?>
        </span>
      </div>

      <!-- Body đơn -->
      <div style="padding:16px 20px">
        <div style="font-size:14px;color:#555;margin-bottom:10px">
          <strong style="color:#333">Sản phẩm:</strong>
          <?php
          $names = explode(', ', $o['product_names'] ?? '');
          $qtys  = explode(', ', $o['quantities'] ?? '');
          $items = [];
          foreach ($names as $i => $name) {
              $qty = $qtys[$i] ?? 1;
              $items[] = htmlspecialchars($name) . ' x' . $qty;
          }
          echo implode(' &nbsp;|&nbsp; ', $items);
          ?>
        </div>
        <div style="font-size:13px;color:#888">
          <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($o['shipping_address'] ?? 'N/A') ?>
        </div>
      </div>

      <!-- Footer đơn -->
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 20px;border-top:1px solid #f0f0f0">
        <span style="font-size:13px;color:#888">Tổng thanh toán</span>
        <span style="font-size:18px;font-weight:700;color:#c0392b"><?= number_format($o['total_amount'],0,',','.') ?>đ</span>
      </div>

    </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php ob_start();
$status_list = [
    'pending'   => '⏳ Chờ xử lý', 
    'confirmed' => '📋 Đã xác nhận',
    'shipping'  => '🚚 Đang giao', 
    'completed' => '✅ Hoàn thành', 
    'cancelled' => '❌ Đã hủy'
];
?>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success">✅ Đã cập nhật trạng thái đơn hàng</div>
<?php endif; ?>

<div class="toolbar">
  <input class="search-input" type="text" id="search" placeholder="🔍 Tìm theo khách hàng...">
  <select id="filter-status" style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px">
    <option value="">Tất cả trạng thái</option>
    <?php foreach ($status_list as $val => $label): ?>
    <option value="<?= $val ?>"><?= $label ?></option>
    <?php endforeach; ?>
  </select>
  <span style="margin-left:auto;font-size:13px;color:#888">
    Tổng: <strong id="total-count"><?= count($orders) ?></strong> đơn
  </span>
</div>

<div class="card" style="padding:0;overflow:hidden">
<table class="tbl" id="order-table">
  <thead>
    <tr>
        <th>STT</th>
        <th>Khách hàng</th>
        <th>Chi tiết đơn hàng</th> <th>Tổng tiền</th>
        <th>Địa chỉ</th>
        <th>Ngày đặt</th>
        <th>Trạng thái</th>
        <th>Cập nhật</th>
    </tr>
  </thead>
  <tbody>
  <?php $stt = 1; foreach ($orders as $o): ?>
<tr data-status="<?= $o['status'] ?>">
  <td style="color:#888; font-weight: bold;"><?= $stt++ ?></td>
  <td><strong><?= htmlspecialchars($o['username']) ?></strong></td>
  
  <td style="font-size: 13px; color: #444; line-height: 1.5; max-width: 250px;">
      <?= nl2br(htmlspecialchars($o['order_details'] ?? 'Không có chi tiết')) ?>
  </td>
  
  <td><strong style="color: #c0392b;"><?= number_format($o['total'],0,',','.') ?>đ</strong></td>
  <td style="max-width:180px;font-size:13px;color:#666"><?= htmlspecialchars($o['shipping_address'] ?? '') ?></td>
    <td style="font-size:13px;color:#888;white-space:nowrap"><?= $o['created_at'] ?></td>
    <td><span class="badge badge-<?= $o['status'] ?>"><?= $status_list[$o['status']] ?? $o['status'] ?></span></td>
    <td>
      <form method="POST" style="display:flex;gap:6px;align-items:center">
        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
        <select name="status" style="padding:6px 8px;border:1px solid #ddd;border-radius:6px;font-size:13px">
          <?php foreach ($status_list as $val => $label): ?>
          <option value="<?= $val ?>" <?= $o['status']===$val?'selected':'' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-green" style="padding:6px 12px">Lưu</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>

<script>
function applyFilter() {
  const q      = document.getElementById('search').value.toLowerCase();
  const status = document.getElementById('filter-status').value;
  let count = 0;
  document.querySelectorAll('#order-table tbody tr').forEach(tr => {
    const nameMatch   = tr.children[1].textContent.toLowerCase().includes(q);
    const statusMatch = !status || tr.dataset.status === status;
    const show = nameMatch && statusMatch;
    tr.style.display = show ? '' : 'none';
    if (show) count++;
  });
  document.getElementById('total-count').textContent = count;
}
document.getElementById('search').addEventListener('input', applyFilter);
document.getElementById('filter-status').addEventListener('change', applyFilter);
</script>

<?php require_once __DIR__ . '/layout.php'; ?>
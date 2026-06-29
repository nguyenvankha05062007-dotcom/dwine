<?php ob_start();
// Chuẩn bị data cho chart
$chartDays = array_column($data['revenue_by_day'], 'date');
$chartRevenue = array_column($data['revenue_by_day'], 'revenue');

$statusData = $data['orders_by_status'] ?? [];

$statusLabels = [
    'pending'   => 'Chờ xử lý', 
    'confirmed' => 'Đã xác nhận',
    'shipping'  => 'Đang giao', 
    'completed' => 'Hoàn thành', 
    'cancelled' => 'Đã hủy'
];

$statusColors = [
    'pending'   => '#f39c12', 
    'confirmed' => '#8e44ad',
    'shipping'  => '#2980b9', 
    'completed' => '#27ae60', 
    'cancelled' => '#e74c3c'
];
?>

<!-- STAT CARDS -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="lbl">Sản phẩm</div>
    <div class="num"><?= number_format($data['total_products']) ?></div>
    <div class="icon2">🛍</div>
  </div>
  <div class="stat-card">
    <div class="lbl">Đơn hàng</div>
    <div class="num"><?= number_format($data['total_orders']) ?></div>
    <div class="icon2">📦</div>
  </div>
  <div class="stat-card">
    <div class="lbl">Người dùng</div>
    <div class="num"><?= number_format($data['total_users']) ?></div>
    <div class="icon2">👥</div>
  </div>
  <div class="stat-card">
    <div class="lbl">Doanh thu</div>
    <div class="num" style="font-size:18px"><?= number_format($data['total_revenue'],0,',','.') ?>đ</div>
    <div class="icon2">💰</div>
  </div>
  <div class="stat-card">
    <div class="lbl">Đánh giá</div>
    <div class="num"><?= number_format($data['total_reviews']) ?></div>
    <div class="icon2">⭐</div>
  </div>
</div>

<!-- CHARTS ROW -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px">

  <!-- Biểu đồ doanh thu 7 ngày -->
  <div class="card">
    <div class="card-title">📈 Doanh thu 7 ngày gần nhất</div>
    <canvas id="revenueChart" height="100"></canvas>
  </div>

  <!-- Biểu đồ tròn trạng thái đơn -->
  <div class="card">
    <div class="card-title">🥧 Trạng thái đơn hàng</div>
    <canvas id="statusChart" height="200"></canvas>
    <div id="status-legend" style="margin-top:12px;display:flex;flex-direction:column;gap:6px"></div>
  </div>

</div>

<!-- TABLES ROW -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

  <div class="card">
    <div class="card-title">📦 Đơn hàng gần đây
      <a href="index.php?page=admin_orders" style="margin-left:auto;font-size:12px;color:#c0392b;text-decoration:none">Xem tất cả →</a>
    </div>
    <table class="tbl">
      <thead><tr><th>#</th><th>Khách</th><th>Tổng</th><th>Trạng thái</th></tr></thead>
      <tbody>
      <?php foreach ($data['recent_orders'] as $o): ?>
      <tr>
        <td style="color:#888">#<?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['username']) ?></td>
        <td><?= number_format($o['total'],0,',','.') ?>đ</td>
        <td><span class="badge badge-<?= $o['status'] ?>"><?= $o['status'] ?></span></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="card">
    <div class="card-title">🏆 Sản phẩm bán chạy
      <a href="index.php?page=admin_products" style="margin-left:auto;font-size:12px;color:#c0392b;text-decoration:none">Xem tất cả →</a>
    </div>
    <table class="tbl">
      <thead><tr><th>Hạng</th><th>Sản phẩm</th><th>Đã bán</th></tr></thead>
      <tbody>
      <?php foreach ($data['top_products'] as $i => $p): ?>
      <tr>
        <td style="color:#c0392b;font-weight:700">#<?= $i+1 ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><strong><?= $p['sold'] ?></strong> chai</td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- Đánh giá gần đây -->
<div class="card">
  <div class="card-title">⭐ Đánh giá gần đây
    <a href="index.php?page=admin_reviews" style="margin-left:auto;font-size:12px;color:#c0392b;text-decoration:none">Xem tất cả →</a>
  </div>
  <table class="tbl">
    <thead><tr><th>Người dùng</th><th>Sản phẩm</th><th>Sao</th><th>Nội dung</th></tr></thead>
    <tbody>
    <?php foreach ($data['recent_reviews'] as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['fullname']) ?></td>
      <td><?= htmlspecialchars($r['product_name'] ?? 'N/A') ?></td>
      <td><span class="stars"><?= str_repeat('★', $r['rating']) ?></span></td>
      <td style="color:#555;font-size:13px"><?= mb_substr(htmlspecialchars($r['comment']), 0, 60) ?>...</td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ── DOANH THU 7 NGÀY ──
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'bar',
  data: {
    labels: <?= json_encode($chartDays) ?>,
    datasets: [{
      label: 'Doanh thu (đ)',
      data: <?= json_encode($chartRevenue) ?>,
      backgroundColor: 'rgba(192,57,43,0.2)',
      borderColor: '#c0392b',
      borderWidth: 2,
      borderRadius: 6,
      fill: true,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: {
        ticks: {
          callback: val => val.toLocaleString('vi-VN') + 'đ'
        },
        grid: { color: '#f0f0f0' }
      },
      x: { grid: { display: false } }
    }
  }
});

// ── TRẠNG THÁI ĐƠN HÀNG ──
const statusRaw   = <?= json_encode($statusData) ?>;
const statusMeta  = <?= json_encode($statusLabels) ?>;
const statusColor = <?= json_encode($statusColors) ?>;

const sLabels = Object.keys(statusRaw).map(k => statusMeta[k] ?? k);
const sData   = Object.values(statusRaw);
const sColors = Object.keys(statusRaw).map(k => statusColor[k] ?? '#ccc');

const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
  type: 'doughnut',
  data: {
    labels: sLabels,
    datasets: [{ data: sData, backgroundColor: sColors, borderWidth: 2 }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    cutout: '65%'
  }
});


const legend = document.getElementById('status-legend');
sLabels.forEach((label, i) => {
  legend.innerHTML += `
    <div style="display:flex;align-items:center;gap:8px;font-size:13px">
      <span style="width:12px;height:12px;border-radius:50%;background:${sColors[i]};flex-shrink:0"></span>
      <span style="color:#555">${label}</span>
      <strong style="margin-left:auto">${sData[i]}</strong>
    </div>`;
});
</script>

<?php require_once __DIR__ . '/layout.php'; ?>
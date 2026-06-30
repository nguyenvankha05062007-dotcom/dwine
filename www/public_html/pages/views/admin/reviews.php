<?php ob_start(); ?>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success">✅ Đã xóa đánh giá</div>
<?php endif; ?>

<div class="toolbar">
  <input class="search-input" type="text" id="search" placeholder="🔍 Tìm username hoặc sản phẩm...">
  <select id="filter-star" style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px">
    <option value="">Tất cả số sao</option>
    <option value="5">⭐⭐⭐⭐⭐ 5 sao</option>
    <option value="4">⭐⭐⭐⭐ 4 sao</option>
    <option value="3">⭐⭐⭐ 3 sao</option>
    <option value="2">⭐⭐ 2 sao</option>
    <option value="1">⭐ 1 sao</option>
  </select>
  <span style="margin-left:auto;font-size:13px;color:#888">
    Tổng: <strong id="total-count"><?= count($reviews) ?></strong> đánh giá
  </span>
</div>

<div class="card" style="padding:0;overflow:hidden">
<table class="tbl" id="review-table">
  <thead>
    <tr><th>STT</th><th>Người dùng</th><th>Sản phẩm</th><th>Sao</th><th>Nội dung</th><th>Thời gian</th><th>Hành động</th></tr>
  </thead>
  <tbody>
  <?php $stt = 1; foreach ($reviews as $r): ?>
  <tr data-star="<?= $r['rating'] ?>">
    <td style="color:#888; font-weight: bold;"><?= $stt++ ?></td>
    <td>
      <div style="font-weight:600"><?= htmlspecialchars($r['username']) ?></div>
      <div style="font-size:12px;color:#888"><?= htmlspecialchars($r['fullname']) ?></div>
    </td>
<td>
  <?php if (!empty($r['product_id'])): ?>
    <a href="index.php?page=detail&id=<?= $r['product_id'] ?>" 
       target="_blank" 
       style="text-decoration: none; color: #000000; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
       <?= htmlspecialchars($r['product_name']) ?> <i class="bi bi-box-arrow-up-right" style="font-size: 11px;"></i>
    </a>
  <?php else: ?>
    <span style="color:#888;">Sản phẩm đã xóa</span>
  <?php endif; ?>
</td>
    <td>
      <span class="stars"><?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?></span>
      <span style="font-size:12px;color:#888">(<?= $r['rating'] ?>/5)</span>
    </td>
    <td style="max-width:220px;font-size:13px;color:#555">
      <?= mb_substr(htmlspecialchars($r['comment']), 0, 80) ?><?= mb_strlen($r['comment']) > 80 ? '...' : '' ?>
    </td>
    <td style="font-size:13px;color:#888;white-space:nowrap"><?= $r['created_at'] ?></td>
    <td>
      <form method="POST" onsubmit="return confirm('Xóa đánh giá này?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
        <button class="btn btn-gray" style="color:#c0392b">Xóa</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>

<script>
function applyFilter() {
  const q    = document.getElementById('search').value.toLowerCase();
  const star = document.getElementById('filter-star').value;
  let count  = 0;
  document.querySelectorAll('#review-table tbody tr').forEach(tr => {
    const textMatch = tr.children[1].textContent.toLowerCase().includes(q) ||
                      tr.children[2].textContent.toLowerCase().includes(q);
    const starMatch = !star || tr.dataset.star === star;
    const show = textMatch && starMatch;
    tr.style.display = show ? '' : 'none';
    if (show) count++;
  });
  document.getElementById('total-count').textContent = count;
}
document.getElementById('search').addEventListener('input', applyFilter);
document.getElementById('filter-star').addEventListener('change', applyFilter);
</script>

<?php require_once __DIR__ . '/layout.php'; ?>
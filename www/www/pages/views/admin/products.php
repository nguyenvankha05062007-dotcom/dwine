<?php ob_start(); ?>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success">
  <?= ['added'=>'✅ Đã thêm sản phẩm','updated'=>'✅ Đã cập nhật','deleted'=>'✅ Đã xóa'][$_GET['msg']] ?? '' ?>
</div>
<?php endif; ?>

<div class="toolbar">
  <input class="search-input" type="text" id="search" placeholder="🔍 Tìm tên sản phẩm...">
  <button class="btn btn-red" onclick="document.getElementById('modal-add').classList.add('open')">
    + Thêm sản phẩm
  </button>
</div>

<div class="card" style="padding:0;overflow:hidden">
<table class="tbl" id="prod-table">
  <thead>
    <tr>
      <th>STT</th><th>Ảnh</th><th>Tên sản phẩm</th><th>Danh mục</th>
      <th>Giá</th><th>Tồn kho</th><th>Xuất xứ</th><th>Hành động</th>
    </tr>
  </thead>
  <tbody>
  <?php $stt = 1; foreach ($products as $p): ?>
  <tr>
    <td style="color:#888; font-weight: bold;"><?= $stt++ ?></td>
    <td>
      <?php if (!empty($p['image'])): ?>
        <img src="images/<?= htmlspecialchars($p['image']) ?>" class="thumb">
      <?php else: ?>
        <div class="no-thumb">🍷</div>
      <?php endif; ?>
    </td>
    <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
    <td><?= htmlspecialchars($p['category_name'] ?? $p['category_id']) ?></td>
    <td><?= number_format($p['price'],0,',','.') ?>đ</td>
    <td>
      <?php if ($p['stock'] == 0): ?>
        <span style="color:#c0392b;font-weight:600">Hết hàng</span>
      <?php elseif ($p['stock'] <= 5): ?>
        <span style="color:#f39c12;font-weight:600"><?= $p['stock'] ?></span>
      <?php else: ?>
        <?= $p['stock'] ?>
      <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($p['origin'] ?? '') ?></td>
    <td>
      <button class="btn btn-yellow" onclick='openEdit(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)'>Sửa</button>
      <form method="POST" style="display:inline" onsubmit="return confirm('Xóa sản phẩm «<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>»?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <button class="btn btn-gray" style="color:#c0392b">Xóa</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>

<!-- Modal Thêm -->
<div id="modal-add" class="overlay">
<div class="modal">
  <div class="modal-head">
    <h3>Thêm sản phẩm mới</h3>
    <button onclick="document.getElementById('modal-add').classList.remove('open')">✕</button>
  </div>
  <form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <div class="form-grid">
      <div class="fg col-span2" style="grid-column:span 2">
        <label>Tên sản phẩm</label>
        <input name="name" required>
      </div>
      <div class="fg">
        <label>Giá (VNĐ)</label>
        <input name="price" type="number" min="0" required>
      </div>
      <div class="fg">
        <label>Tồn kho</label>
        <input name="stock" type="number" min="0" required>
      </div>
      <div class="fg">
        <label>Danh mục</label>
        <select name="category_id">
          <option value="">-- Chọn danh mục --</option>
          <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="fg">
        <label>Xuất xứ</label>
        <input name="origin">
      </div>
      <div class="fg">
        <label>Nồng độ cồn (%)</label>
        <input name="alcohol_percent" type="number" step="0.1" min="0">
      </div>
      <div class="fg">
        <label>Thể tích (ml)</label>
        <input name="volume_ml" type="number" min="0">
      </div>
      <div class="fg" style="grid-column:span 2">
        <label>Mô tả</label>
        <textarea name="description" rows="3"></textarea>
      </div>
      <div class="fg" style="grid-column:span 2">
        <label>Ảnh sản phẩm</label>
        <input type="file" name="image" accept="image/*">
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn btn-gray" onclick="document.getElementById('modal-add').classList.remove('open')">Hủy</button>
      <button class="btn btn-red">Thêm sản phẩm</button>
    </div>
  </form>
</div>
</div>

<!-- Modal Sửa -->
<div id="modal-edit" class="overlay">
<div class="modal">
  <div class="modal-head">
    <h3>Sửa sản phẩm</h3>
    <button onclick="document.getElementById('modal-edit').classList.remove('open')">✕</button>
  </div>
  <form method="POST" enctype="multipart/form-data" id="edit-form">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" id="e-id">
    <div class="form-grid">
      <div class="fg" style="grid-column:span 2">
        <label>Tên sản phẩm</label>
        <input name="name" id="e-name" required>
      </div>
      <div class="fg">
        <label>Giá (VNĐ)</label>
        <input name="price" id="e-price" type="number" min="0" required>
      </div>
      <div class="fg">
        <label>Tồn kho</label>
        <input name="stock" id="e-stock" type="number" min="0" required>
      </div>
      <div class="fg">
        <label>Danh mục</label>
        <select name="category_id" id="e-cat">
          <option value="">-- Chọn danh mục --</option>
          <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="fg">
        <label>Xuất xứ</label>
        <input name="origin" id="e-origin">
      </div>
      <div class="fg">
        <label>Nồng độ cồn (%)</label>
        <input name="alcohol_percent" id="e-alcohol" type="number" step="0.1" min="0">
      </div>
      <div class="fg">
        <label>Thể tích (ml)</label>
        <input name="volume_ml" id="e-volume" type="number" min="0">
      </div>
      <div class="fg" style="grid-column:span 2">
        <label>Mô tả</label>
        <textarea name="description" id="e-desc" rows="3"></textarea>
      </div>
      <div class="fg" style="grid-column:span 2">
        <label>Ảnh mới (để trống nếu không đổi)</label>
        <input type="file" name="image" accept="image/*">
      </div>
    </div>
    <div class="modal-foot">
      <button type="button" class="btn btn-gray" onclick="document.getElementById('modal-edit').classList.remove('open')">Hủy</button>
      <button class="btn btn-yellow">Lưu thay đổi</button>
    </div>
  </form>
</div>
</div>

<script>
function openEdit(p) {
  document.getElementById('e-id').value      = p.id;
  document.getElementById('e-name').value    = p.name;
  document.getElementById('e-price').value   = p.price;
  document.getElementById('e-stock').value   = p.stock;
  document.getElementById('e-cat').value     = p.category_id || '';
  document.getElementById('e-origin').value  = p.origin || '';
  document.getElementById('e-alcohol').value = p.alcohol_percent || '';
  document.getElementById('e-volume').value  = p.volume_ml || '';
  document.getElementById('e-desc').value    = p.description || '';
  document.getElementById('modal-edit').classList.add('open');
}

// Search filter
document.getElementById('search').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#prod-table tbody tr').forEach(tr => {
    tr.style.display = tr.children[2].textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});
</script>

<?php require_once __DIR__ . '/layout.php'; ?>
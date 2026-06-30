<?php ob_start(); ?>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success">✅ Đã cập nhật thông tin người dùng thành công</div>
<?php endif; ?>

<div class="toolbar">
  <input class="search-input" type="text" id="search" placeholder="🔍 Tìm username hoặc email...">
  <select id="filter-role" style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px">
    <option value="">Tất cả role</option>
    <option value="admin">Admin</option>
    <option value="user">User</option>
  </select>
  <span style="margin-left:auto;font-size:13px;color:#888">
    Tổng: <strong id="total-count"><?= count($users) ?></strong> người dùng
  </span>
</div>

<div class="card" style="padding:0;overflow:hidden">
<table class="tbl" id="user-table">
  <thead>
    <tr>
        <th>STT</th>
        <th>Họ tên</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
  <?php $stt = 1; foreach ($users as $u): ?>
  <tr data-role="<?= $u['role'] ?>">
    <td style="color:#888; font-weight: bold;"><?= $stt++ ?></td>
    <td><?= htmlspecialchars($u['fullname'] ?? '') ?></td>
    <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
    <td style="font-size:13px;color:#666"><?= htmlspecialchars($u['email'] ?? '') ?></td>
    <td><span class="badge badge-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
    
    <td>
        <?php if (($u['status'] ?? 'active') === 'active'): ?>
            <span class="badge" style="background: #d4edda; color: #155724;">Hoạt động</span>
        <?php else: ?>
            <span class="badge" style="background: #f8d7da; color: #721c24;">Vô hiệu hóa</span>
        <?php endif; ?>
    </td>
    
    <td style="font-size:13px;color:#888"><?= $u['created_at'] ?? '' ?></td>
    <td>
      <button class="btn btn-yellow" style="padding:5px 12px" onclick='openEdit(<?= htmlspecialchars(json_encode($u), ENT_QUOTES) ?>)'>Sửa</button>

      <?php if ($u['id'] != ($_SESSION['user_id'] ?? 0)): ?>
      <form method="POST" style="display:inline" onsubmit="return confirm('Xóa tài khoản «<?= htmlspecialchars($u['username'], ENT_QUOTES) ?>»?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
        <button class="btn btn-gray" style="color:#c0392b;padding:5px 10px">Xóa</button>
      </form>
      <?php else: ?>
        <span style="font-size:12px;color:#aaa;padding-left:10px">(bạn)</span>
      <?php endif; ?>
      
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>

<div id="modal-edit-user" class="overlay">
<div class="modal">
  <div class="modal-head">
    <h3>Cập nhật thành viên: <span id="e-username" style="color: #c0392b;"></span></h3>
    <button onclick="document.getElementById('modal-edit-user').classList.remove('open')">✕</button>
  </div>
  <form method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="user_id" id="e-user-id">
    
    <div class="form-grid col1" style="display: flex; flex-direction: column; gap: 15px;">
      <div class="fg">
        <label>Quyền hạn hệ thống</label>
        <select name="role" id="e-role" style="width: 100%;">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      
      <div class="fg">
        <label>Trạng thái hoạt động</label>
        <select name="status" id="e-status" style="width: 100%;">
          <option value="active">Hoạt động</option>
          <option value="disabled">Vô hiệu hóa</option>
        </select>
      </div>
      
      <div class="fg">
        <label>Đổi mật khẩu mới (Để trống nếu giữ nguyên)</label>
        <input type="password" name="password" placeholder="Nhập mật khẩu mới từ 6 ký tự..." style="width: 100%;">
      </div>
    </div>
    
    <div class="modal-foot">
      <button type="button" class="btn btn-gray" onclick="document.getElementById('modal-edit-user').classList.remove('open')">Hủy</button>
      <button class="btn btn-yellow">Lưu thay đổi</button>
    </div>
  </form>
</div>
</div>

<script>
// Hàm đẩy dữ liệu thành viên lên Modal Sửa
function openEdit(u) {
  document.getElementById('e-user-id').value = u.id;
  document.getElementById('e-username').textContent = u.username;
  document.getElementById('e-role').value = u.role;
  document.getElementById('e-status').value = u.status || 'active';
  document.getElementById('modal-edit-user').classList.add('open');
}

// Bộ lọc tìm kiếm tên và phân quyền
function applyFilter() {
  const q    = document.getElementById('search').value.toLowerCase();
  const role = document.getElementById('filter-role').value;
  let count  = 0;
  document.querySelectorAll('#user-table tbody tr').forEach(tr => {
    const textMatch = tr.children[2].textContent.toLowerCase().includes(q) ||
                      tr.children[3].textContent.toLowerCase().includes(q);
    const roleMatch = !role || tr.dataset.role === role;
    const show = textMatch && roleMatch;
    tr.style.display = show ? '' : 'none';
    if (show) count++;
  });
  document.getElementById('total-count').textContent = count;
}
document.getElementById('search').addEventListener('input', applyFilter);
document.getElementById('filter-role').addEventListener('change', applyFilter);
</script>

<?php require_once __DIR__ . '/layout.php'; ?>
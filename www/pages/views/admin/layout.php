<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$content = ob_get_clean();
$cur = $_GET['page'] ?? 'admin';
$titles = [
    'admin'          => 'Dashboard',
    'admin_products' => 'Quản lý Sản phẩm',
    'admin_orders'   => 'Quản lý Đơn hàng',
    'admin_users'    => 'Quản lý Người dùng',
    'admin_reviews'  => 'Quản lý Đánh giá',
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $titles[$cur] ?? 'Admin' ?> — WineShop</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; display: flex; min-height: 100vh; background: #f0f2f5; color: #222; }

  /* ── SIDEBAR ── */
  .sb { width: 230px; background: #1c1c2e; display: flex; flex-direction: column; flex-shrink: 0; position: sticky; top: 0; height: 100vh; }
  .sb-logo { padding: 24px 20px 20px; border-bottom: 1px solid #2e2e45; }
  .sb-logo h2 { color: #c0392b; font-size: 20px; letter-spacing: 1px; }
  .sb-logo p  { color: #666; font-size: 11px; margin-top: 3px; }
  .sb nav { flex: 1; padding: 12px 0; overflow-y: auto;}
  .sb nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 22px; color: #aaa; text-decoration: none;
    font-size: 14px; transition: background .15s, color .15s;
    border-left: 3px solid transparent;
  }
  .sb nav a:hover { background: #2a2a40; color: #fff; }
  .sb nav a.active { background: #2a2a40; color: #fff; border-left-color: #c0392b; }
  .sb nav a .icon { font-size: 16px; width: 20px; text-align: center; }
  .sb-footer { padding: 14px 0; border-top: 1px solid #2e2e45; }
  .sb-footer a { display: flex; align-items: center; gap: 10px; padding: 10px 22px; color: #888; text-decoration: none; font-size: 13px; }
  .sb-footer a:hover { color: #fff; }

  /* ── MAIN ── */
  .main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
  .topbar {
    background: #fff; padding: 0 28px; height: 56px;
    display: flex; justify-content: space-between; align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,.08); flex-shrink: 0;
  }
  .topbar h1 { font-size: 17px; font-weight: 600; color: #333; }
  .topbar .user-info { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #555; }
  .avatar { width: 32px; height: 32px; background: #c0392b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 13px; }
  .content { padding: 24px 28px; flex: 1; }

  /* ── STATS ── */
  .stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px; }
  .stat-card {
    background: #fff; border-radius: 10px; padding: 18px 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.07); border-top: 3px solid transparent;
  }
  .stat-card:nth-child(1) { border-color: #c0392b; }
  .stat-card:nth-child(2) { border-color: #2980b9; }
  .stat-card:nth-child(3) { border-color: #27ae60; }
  .stat-card:nth-child(4) { border-color: #f39c12; }
  .stat-card:nth-child(5) { border-color: #8e44ad; }
  .stat-card .num   { font-size: 26px; font-weight: 700; color: #222; margin-bottom: 4px; }
  .stat-card .lbl   { font-size: 12px; color: #888; }
  .stat-card .icon2 { font-size: 24px; float: right; margin-top: -30px; opacity: .15; }

/* ── CARD ── */
  .card { 
    background: #fff; 
    border-radius: 10px; 
    padding: 20px; 
    box-shadow: 0 1px 4px rgba(0,0,0,.07); 
    margin-bottom: 20px; 
    /* Đã gỡ bỏ overflow-x để không làm trôi tiêu đề */
  }
  .card-title { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }

  /* ── BỘ BỌC BẢNG (TABLE WRAPPER) ── */
  .table-wrap {
    overflow-x: auto;
    width: 100%;
  }

  /* Tùy chỉnh thanh cuộn ngang sang trọng (màu đỏ rượu) cho Bảng */
  .table-wrap::-webkit-scrollbar { height: 8px; }
  .table-wrap::-webkit-scrollbar-track { background: #f0f2f5; border-radius: 4px; }
  .table-wrap::-webkit-scrollbar-thumb { background: #c0392b; border-radius: 4px; }
  .table-wrap::-webkit-scrollbar-thumb:hover { background: #8a281e; }

/* ── TABLE ── */
  .tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
  
  /* Ép độ rộng tối thiểu CHỈ cho các bảng quản lý (có khai báo ID) 
     Màn hình nhỏ hơn 1050px sẽ tự sinh ra thanh cuộn ngang */
  table[id] { min-width: 1050px; }
  
  .tbl th { background: #f8f9fa; padding: 10px 14px; text-align: left; font-weight: 600; color: #555; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
  .tbl td { padding: 10px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
  .tbl tr:last-child td { border-bottom: none; }
  .tbl tr:hover td { background: #fafafa; }

  /* ── BADGE ── */
  .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
  .badge-pending   { background: #fff3cd; color: #856404; }
  .badge-shipping  { background: #cce5ff; color: #004085; }
  .badge-done      { background: #d4edda; color: #155724; }
  .badge-cancelled { background: #f8d7da; color: #721c24; }
  .badge-admin     { background: #f8d7da; color: #721c24; }
  .badge-user      { background: #e2e3e5; color: #383d41; }

  /* ── BUTTONS ── */
  .btn { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; text-decoration: none; transition: opacity .15s; }
  .btn:hover { opacity: .85; }
  .btn-red    { background: #c0392b; color: #fff; }
  .btn-blue   { background: #2980b9; color: #fff; }
  .btn-green  { background: #27ae60; color: #fff; }
  .btn-yellow { background: #f39c12; color: #fff; }
  .btn-gray   { background: #e2e3e5; color: #333; }
  .btn + .btn { margin-left: 4px; }

  /* ── FORM ── */
  .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .form-grid.col3 { grid-template-columns: 1fr 1fr 1fr; }
  .form-grid.col1 { grid-template-columns: 1fr; }
  .fg { display: flex; flex-direction: column; gap: 5px; }
  .fg label { font-size: 12px; font-weight: 600; color: #555; text-transform: uppercase; letter-spacing: .4px; }
  .fg input, .fg select, .fg textarea {
    padding: 9px 11px; border: 1px solid #ddd; border-radius: 6px;
    font-size: 14px; font-family: inherit; transition: border-color .15s;
  }
  .fg input:focus, .fg select:focus, .fg textarea:focus { outline: none; border-color: #c0392b; }

  /* ── ALERT ── */
  .alert { padding: 10px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; }
  .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

  /* ── MODAL ── */
  .overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 900; align-items: center; justify-content: center; }
  .overlay.open { display: flex; }
  .modal { background: #fff; border-radius: 12px; padding: 26px; width: 520px; max-width: 95vw; max-height: 90vh; overflow-y: auto; }
  .modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
  .modal-head h3 { font-size: 16px; font-weight: 600; }
  .modal-head button { background: none; border: none; font-size: 20px; cursor: pointer; color: #888; line-height: 1; }
  .modal-foot { margin-top: 20px; display: flex; justify-content: flex-end; gap: 8px; }

  /* ── TOOLBAR ── */
  .toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; flex-wrap: wrap; }
  .search-input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; width: 240px; }
  .search-input:focus { outline: none; border-color: #c0392b; }

  /* ── THUMB ── */
  .thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 6px; }
  .no-thumb { width: 48px; height: 48px; background: #f0f0f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #bbb; font-size: 20px; }

  /* ── STARS ── */
  .stars { color: #f59e0b; letter-spacing: 1px; }
  /* ── RESPONSIVE THIẾT BỊ ── */
  .menu-toggle { display: none; font-size: 24px; border: none; background: transparent; cursor: pointer; color: #333; margin-right: 15px; }
  .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 998; }
  
  /* Chế độ Tablet (Máy tính bảng) */
  @media (max-width: 991px) {
    .stats-grid { grid-template-columns: repeat(3, 1fr); }
  }

  /* Chế độ Mobile (Điện thoại) */
  @media (max-width: 768px) {
    /* Thanh menu bên trái tự động trượt ra ngoài màn hình */
    .sb { position: fixed; left: -250px; height: 100vh; z-index: 999; transition: left 0.3s ease; box-shadow: 2px 0 10px rgba(0,0,0,0.5); }
    .sb.active { left: 0; } /* Hiển thị khi bấm nút */
    
    .sidebar-overlay.active { display: block; }
    .menu-toggle { display: inline-block; }
    
    /* Ép các ô biểu đồ, lưới form thông tin dọc xuống (1 cột) */
    div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; display: flex !important; flex-direction: column; }
    .form-grid { grid-template-columns: 1fr !important; }
    
    /* Thu gọn thẻ thống kê thành 2 cột nhỏ */
    .stats-grid { grid-template-columns: 1fr 1fr; }
    
    /* Bảng dữ liệu có thể lướt ngang (Scroll) để không bị tràn chữ ra ngoài */
    .card { overflow-x: auto; }
    table.tbl { min-width: 650px; }
    
    /* Sắp xếp lại thanh tìm kiếm */
    .toolbar { flex-direction: column; align-items: stretch; gap: 10px; }
    .toolbar input, .toolbar select, .toolbar button { width: 100%; margin: 0 !important; }
  }
  
  /* Chế độ điện thoại quá nhỏ */
  @media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<aside class="sb">
  <div class="sb-logo">
    <h2>D-WINE</h2>
    <p>Bảng điều khiển Admin</p>
  </div>
  <nav>
    <a href="index.php?page=admin"          class="<?= $cur==='admin'?'active':'' ?>">
      <span class="icon">📊</span> Dashboard
    </a>
    <a href="index.php?page=admin_products" class="<?= $cur==='admin_products'?'active':'' ?>">
      <span class="icon">🛍</span> Sản phẩm
    </a>
    <a href="index.php?page=admin_orders"   class="<?= $cur==='admin_orders'?'active':'' ?>">
      <span class="icon">📦</span> Đơn hàng
    </a>
    <a href="index.php?page=admin_users"    class="<?= $cur==='admin_users'?'active':'' ?>">
      <span class="icon">👥</span> Người dùng
    </a>
    <a href="index.php?page=admin_reviews"  class="<?= $cur==='admin_reviews'?'active':'' ?>">
      <span class="icon">⭐</span> Đánh giá
    </a>
  </nav>
  <div class="sb-footer">
    <a href="index.php?page=home">🏠 Về trang chủ</a>
    <a href="logout.php" style="color:#c0392b">🚪 Đăng xuất</a>
  </div>
</aside>

<div class="main">
  <div class="topbar">
    <div style="display: flex; align-items: center;">
        <button class="menu-toggle" id="menu-toggle">☰</button>
        <h1><?= $titles[$cur] ?? 'Admin' ?></h1>
    </div>
    <div class="user-info">
      <div class="avatar"><?= strtoupper(mb_substr($_SESSION['username'], 0, 1)) ?></div>
      <?= htmlspecialchars($_SESSION['username']) ?>
    </div>
  </div>
  <div class="content">
    <?= $content ?>
  </div>
</div>

<script>
// Dùng DOMContentLoaded để đảm bảo web tải xong 100% mới chạy code
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Logic đóng modal khi click ra ngoài
    document.querySelectorAll('.overlay').forEach(el =>
      el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); })
    );

    // 2. Logic Menu điện thoại (Đã nâng cấp chống lỗi)
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sb');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    // Nếu tìm thấy nút bấm và thanh menu, gắn sự kiện Click
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn chặn các hành vi mặc định
            sidebar.classList.add('active');
            if (sidebarOverlay) sidebarOverlay.classList.add('active');
        });
    }

    // Đóng menu khi bấm vào vùng đen
    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.tbl').forEach(table => {
        // Kiểm tra xem bảng đã được bọc chưa để tránh bọc trùng lặp
        if (!table.parentElement.classList.contains('table-wrap')) {
            const wrap = document.createElement('div');
            wrap.className = 'table-wrap';
            table.parentNode.insertBefore(wrap, table);
            wrap.appendChild(table);
        }
    });
});

// 1. MẸO UNLOAD: Lệnh rỗng này ép trình duyệt KHÔNG ĐƯỢC PHÉP lưu trang Admin vào RAM 
window.addEventListener('unload', function() {});

window.addEventListener('pageshow', function (event) {
    let isBack = false;
    
    // 2. Quét hành vi bằng Performance API 
    if (window.performance && window.performance.getEntriesByType) {
        let navEntries = window.performance.getEntriesByType("navigation");
        if (navEntries.length > 0 && navEntries[0].type === "back_forward") {
            isBack = true;
        }
    }
    
    // 3. Kết hợp bắt event.persisted 
    if (event.persisted || isBack) {
        window.location.reload(true); 
    }
});
</script>
</body>
</html>
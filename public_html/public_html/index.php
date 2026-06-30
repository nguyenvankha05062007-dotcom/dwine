<?php

define('ROOT_PATH', __DIR__);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['page']) && $_GET['page'] === 'login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: index.php?page=home");
    exit;
    
}

// 1. Lấy trang hiện tại mà người dùng muốn vào
$page = $_GET['page'] ?? 'home';

// 2. BỘ LỌC CHẶN (MIDDLEWARE) PHÂN QUYỀN
// Định nghĩa danh sách các trang "CÔNG CỘNG" công khai
$public_pages = ['home', 'login', 'register', 'detail', 'products', 'quality', 'cart'];

if (!isset($_SESSION['username']) && !in_array($page, $public_pages)) {
    header("Location: index.php?page=home");
    exit;
}
if (str_starts_with($page, 'admin')) {
    if (!isset($_SESSION['username'])) {
        header("Location: index.php?page=home");
        exit;
    }
    if (($_SESSION['role'] ?? '') !== 'admin') {
        header("Location: index.php?page=home");
        exit;
    }
}

// 3. ROUTER ĐIỀU HƯỚNG CÁC TRANG (Đã đồng bộ Usercontrollers.php có chữ s của bạn)
switch ($page) {
    case 'detail':
        require_once __DIR__ . '/pages/controllers/Detailcontrollers.php';
        break;

    case 'home':
        require_once __DIR__ . '/pages/controllers/Homecontrollers.php';
        require_once __DIR__ . '/pages/controllers/Usercontrollers.php';
        break;

    case 'products':
        require_once __DIR__ . '/pages/controllers/Productcontrollers.php';
        break;

    case 'login':
    case 'register':
        require_once __DIR__ . '/pages/controllers/Usercontrollers.php';
        break;
    case 'order_history':
    require_once __DIR__ . '/pages/controllers/Orderhistorycontrollers.php';
    break;    
        
    case 'quality':
        require_once __DIR__ . '/pages/controllers/Winequalitycontroller.php';
        break;

    case 'review_submit':
        require_once __DIR__ . '/pages/controllers/Reviewcontrollers.php';
        break; 
    case 'cart':
        require_once __DIR__ . '/pages/controllers/Cartcontrollers.php';
        break;
    case 'admin':
    case 'admin_products':
    case 'admin_orders':
    case 'admin_users':
    case 'admin_reviews':    
        require_once __DIR__ . '/pages/controllers/Admincontrollers.php';
        break;
    default:
        echo "404 - Trang không tồn tại";
}
?>

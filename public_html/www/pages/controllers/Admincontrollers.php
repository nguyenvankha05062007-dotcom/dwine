<?php
// pages/controllers/Admincontrollers.php
// 1. Đảm bảo Session đang chạy
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Lệnh cấm trình duyệt lưu Cache 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 3. NGƯỜI BẢO VỆ: Nếu không có session hoặc không phải 'admin', đá văng về trang chủ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=home");
    exit;
}

require_once ROOT_PATH . '/pages/models/Productmodels.php';
require_once ROOT_PATH . '/pages/models/Ordermodels.php';
require_once ROOT_PATH . '/pages/models/Usermodels.php';
require_once ROOT_PATH . '/pages/models/Reviewmodels.php';

$page = $_GET['page'] ?? 'admin';

switch ($page) {

case 'admin':
    $productModel = new Productmodel();  
    $orderModel   = new Ordermodel();
    $userModel    = new UserModel();
    $reviewModel  = new Reviewmodel();  // ← thêm dòng này

    $data = [
        'revenue_by_day'  => $orderModel->getRevenueByDay(7),
        'orders_by_status' => $orderModel->countByStatus(),
        'total_products' => $productModel->countAll(),
        'total_orders'   => $orderModel->countAll(),
        'total_users'    => $userModel->countAll(),
        'total_revenue'  => $orderModel->totalRevenue(),
        'recent_orders'  => $orderModel->getRecent(5),
        'top_products'   => $productModel->getTopSelling(5),
        'total_reviews'  => $reviewModel->countAll(),       
        'recent_reviews' => $reviewModel->getRecent(5),    
    ];
    require_once ROOT_PATH . '/pages/views/admin/dashboard.php';
    break;

    case 'admin_products':
        $productModel = new Productmodel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $act = $_POST['action'] ?? '';

            if ($act === 'add') {
                $image = '';
                if (!empty($_FILES['image']['name'])) {
                    $ext   = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowed_ext = ['jpg','jpeg','png','webp'];
                    if (in_array($ext, $allowed_ext)) {
                        $image = uniqid('prod_') . '.' . $ext;
                        move_uploaded_file(
                            $_FILES['image']['tmp_name'],
                            ROOT_PATH . '/public_html/assets/images/' . $image
                        );
                    }
                }
                $productModel->add(array_merge($_POST, ['image' => $image]));
                header("Location: index.php?page=admin_products&msg=added"); exit;
            }

            if ($act === 'edit') {
                $productModel->update($_POST['id'], $_POST);
                // Nếu có upload ảnh mới
                if (!empty($_FILES['image']['name'])) {
                    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $image = uniqid('prod_') . '.' . $ext;
                    move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        ROOT_PATH . '/public_html/images/' . $image
                    );
                    $productModel->updateImage($_POST['id'], $image);
                }
                header("Location: index.php?page=admin_products&msg=updated"); exit;
            }

            if ($act === 'delete') {
                $productModel->delete($_POST['id']);
                header("Location: index.php?page=admin_products&msg=deleted"); exit;
            }
        }

        $products   = $productModel->getAll();       // đã có sẵn
        $categories = $productModel->getCategories(); // đã có sẵn
        require_once ROOT_PATH . '/pages/views/admin/products.php';
        break;

    case 'admin_orders':
        $orderModel = new Ordermodel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel->updateStatus($_POST['order_id'], $_POST['status']);
            header("Location: index.php?page=admin_orders&msg=updated"); exit;
        }

        $orders = $orderModel->getAllWithUser();
        require_once ROOT_PATH . '/pages/views/admin/orders.php';
        break;

    case 'admin_users':
        $userModel = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $act = $_POST['action'] ?? '';
            
            // XỬ LÝ CẬP NHẬT TÀI KHOẢN TỪ ADMIN
            if ($act === 'edit') {
                $userModel->updateUserAdmin($_POST['user_id'], $_POST['role'], $_POST['status'], $_POST['password'] ?? '');
                header("Location: index.php?page=admin_users&msg=updated"); exit;
            }
            
            if ($act === 'delete') {
                
                if ($_POST['user_id'] != $_SESSION['user_id']) {
                    $userModel->deleteUser($_POST['user_id']);
                }
                header("Location: index.php?page=admin_users&msg=updated"); exit;
            }

        }

        $users = $userModel->getAll();
        require_once ROOT_PATH . '/pages/views/admin/users.php';
        break;


    case 'admin_reviews':
    $reviewModel = new Reviewmodel();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (($_POST['action'] ?? '') === 'delete') {
            $reviewModel->delete($_POST['review_id']);
        }
        header("Location: index.php?page=admin_reviews&msg=deleted"); 
        exit;
    } 

    $reviews = $reviewModel->getAllWithDetails();
    require_once ROOT_PATH . '/pages/views/admin/reviews.php';
    break;   
}
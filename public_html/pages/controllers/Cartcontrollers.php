<?php
require_once __DIR__ . '/../models/Productmodels.php';
require_once __DIR__ . '/../models/Ordermodels.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // dạng [product_id => quantity]
}

$productModel = new Productmodel();
$action = $_GET['action'] ?? 'view';

switch ($action) {

    case 'add':
        $id = (int)($_GET['id'] ?? 0);
        $qty = (int)($_GET['qty'] ?? 1);
        if ($id > 0) {
            $product = $productModel->getById($id);
            if ($product) {
                $stock = (int)$product['stock'];
                $current_qty = $_SESSION['cart'][$id] ?? 0;
                $new_qty = $current_qty + $qty;

                // Kiểm tra nếu tổng số lượng vượt quá tồn kho
                if ($new_qty > $stock) {
                    $_SESSION['cart'][$id] = $stock; // Ép về mức tồn kho tối đa
                    $_SESSION['error_cart'] = "Sản phẩm '{$product['name']}' chỉ còn $stock sản phẩm trong kho. Chúng tôi đã điều chỉnh số lượng!";
                } else {
                    $_SESSION['cart'][$id] = $new_qty;
                }
            }
        }
        header("Location: ?page=cart");
        exit();

    case 'remove':
        $id = (int)($_GET['id'] ?? 0);
        unset($_SESSION['cart'][$id]);
        header("Location: ?page=cart");
        exit();

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
            $has_error = false;
            foreach ($_POST['quantities'] as $pid => $qty) {
                $qty = (int)$qty;
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$pid]);
                } else {
                    $product = $productModel->getById($pid);
                    if ($product) {
                        $stock = (int)$product['stock'];
                        // Kiểm tra tồn kho khi cập nhật số lượng
                        if ($qty > $stock) {
                            $_SESSION['cart'][$pid] = $stock; // Ép về tối đa
                            $has_error = true;
                        } else {
                            $_SESSION['cart'][$pid] = $qty;
                        }
                    }
                }
            }
            if ($has_error) {
                $_SESSION['error_cart'] = "Một số sản phẩm vượt quá số lượng tồn kho nên đã được tự động điều chỉnh!";
            }
        }
        header("Location: ?page=cart");
        exit();

    case 'checkout':
        if (!isset($_SESSION['user_id'])) {
            header("Location: ?page=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $items = [];
            $total = 0;
            foreach ($_SESSION['cart'] as $pid => $qty) {
                $product = $productModel->getById($pid);
                if ($product) {
                    // Kiểm tra tồn kho lần cuối trước khi tạo đơn hàng (để tránh trường hợp người khác mua mất)
                    $stock = (int)$product['stock'];
                    if ($qty > $stock) {
                        $_SESSION['cart'][$pid] = $stock;
                        $_SESSION['error_cart'] = "Sản phẩm '{$product['name']}' vừa bị giảm tồn kho. Vui lòng kiểm tra lại giỏ hàng!";
                        header("Location: ?page=cart");
                        exit();
                    }

                    $items[] = ['product_id' => $pid, 'quantity' => $qty, 'price' => $product['price']];
                    $total += $product['price'] * $qty;
                }
            }
            if (!empty($items)) {
                $orderModel = new Ordermodel();
                $orderId = $orderModel->createOrder($_SESSION['user_id'], $items, $total, $_POST['address']);
                if ($orderId) {
                    $_SESSION['cart'] = [];
                    header("Location: ?page=cart&action=success&order_id=$orderId");
                    exit();
                }
            }
        }
        break;

    case 'success':
        $orderId = (int)($_GET['order_id'] ?? 0);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/cart/success.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
        exit();
}

// Hiển thị giỏ hàng (action=view, mặc định)
$cartItems = [];
$total = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    $product = $productModel->getById($pid);
    if ($product) {
        $cartItems[] = ['product' => $product, 'quantity' => $qty, 'subtotal' => $product['price'] * $qty];
        $total += $product['price'] * $qty;
    }
}

// Lấy thông báo lỗi để truyền ra view
$error_cart = $_SESSION['error_cart'] ?? null;
unset($_SESSION['error_cart']);

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/cart/cart.php';
require_once __DIR__ . '/../views/layouts/footer.php';
?>
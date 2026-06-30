<?php
require_once __DIR__ . '/../models/Productmodels.php';

$productModel = new Productmodel();

// Lấy ID sản phẩm từ URL, mặc định là 0 nếu không có
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = $productModel->getById($id);

// Xử lý nếu sản phẩm không tồn tại
if (!$product) {
    require_once __DIR__ . '/../views/layouts/header.php';
    echo "<div class='container mt-5'><h3 class='text-warning'><i class='bi bi-exclamation-triangle-fill'></i> Sản phẩm không tồn tại hoặc đã bị xóa!</h3></div>";
    require_once __DIR__ . '/../views/layouts/footer.php';
    exit;
}

$relatedProducts = $productModel->getSimilarProducts($product, 4);

// Include các thành phần giao diện
require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/products/detail.php';
require_once __DIR__ . '/../views/layouts/footer.php';
?>
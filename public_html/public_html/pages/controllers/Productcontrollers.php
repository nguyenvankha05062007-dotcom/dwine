<?php
require_once __DIR__ . '/../models/Productmodels.php';

$productModel = new Productmodel();

$categories = $productModel->getCategories();
$allOrigins = $productModel->getUniqueOrigins();
$allVolumes = $productModel->getUniqueVolumes();
$maxPriceDB = $productModel->getMaxPrice(); 

// Tiếp nhận mảng filters
$filters = [
    'category_id'    => isset($_GET['category']) ? (int)$_GET['category'] : null,
    'origins'        => isset($_GET['origins']) ? (array)$_GET['origins'] : [],
    'volumes'        => isset($_GET['volumes']) ? (array)$_GET['volumes'] : [],
    'alcohol_ranges' => isset($_GET['alcohol_ranges']) ? (array)$_GET['alcohol_ranges'] : [],
    'status'         => isset($_GET['status']) ? (array)$_GET['status'] : [],
    'min_price'      => isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0,
    'max_price'      => isset($_GET['max_price']) ? (float)$_GET['max_price'] : $maxPriceDB
];

$products = $productModel->getAdvancedFiltered($filters);

require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/products/product.php'; 
require_once __DIR__ . '/../views/layouts/footer.php';
?>
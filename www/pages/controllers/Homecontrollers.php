<?php
require_once __DIR__ . '/../models/Productmodels.php';
require_once __DIR__ . '/../models/Reviewmodels.php';

$productModel = new Productmodel();

$allProducts = $productModel->getAll();

$featuredProducts = array_slice($allProducts, 0, 4);              
$otherProducts = array_reverse(array_slice($allProducts, 4, 4)); 
$reviewModel = new Reviewmodel();
$reviews = $reviewModel -> getRecent(6);
require_once __DIR__ . '/../views/layouts/header.php';
require_once __DIR__ . '/../views/home/home.php';
require_once __DIR__ . '/../views/layouts/footer.php';
?>
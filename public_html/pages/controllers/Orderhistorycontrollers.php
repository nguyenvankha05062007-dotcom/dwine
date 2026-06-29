<?php
require_once ROOT_PATH . '/pages/models/Ordermodels.php';

$orderModel = new Ordermodel();
$orders = $orderModel->getByUser($_SESSION['user_id']);
require_once ROOT_PATH . '/pages/views/user/order_history.php';
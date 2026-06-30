<?php
// File: pages/controllers/Usercontrollers.php

// Nếu người dùng chưa đăng nhập, ta tải toàn bộ file giao diện modal popup vào hệ thống
if (!isset($_SESSION['username'])) {
    if (file_exists(__DIR__ . '/../views/user/login.php')) {
        require_once __DIR__ . '/../views/user/login.php';
    }
    if (file_exists(__DIR__ . '/../views/user/register.php')) {
        require_once __DIR__ . '/../views/user/register.php';
    }
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
include_once __DIR__ . '/pages/models/Usermodels.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ tài khoản và mật khẩu!"]);
        exit;
    }

    $userModel = new UserModel();
    $result = $userModel->login($username, $password);

    if ($result === true) {
    echo json_encode([
        "status"  => "success",
        "message" => "role=" . ($_SESSION['role'] ?? 'TRỐNG'),
        "role"    => $_SESSION['role'] ?? ''
    ]);
} else {
        echo json_encode(["status" => "error", "message" => $result]);
    }
}
?>
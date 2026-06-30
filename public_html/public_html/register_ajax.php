<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 1. Kiểm tra trống dữ liệu
    if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ tất cả các thông tin!"]);
        exit;
    }

    // 2. Kiểm tra cấu hình cú pháp email cơ bản
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Định dạng email không hợp lệ!"]);
        exit;
    }

    // 3. ÉP BUỘC BẮT LỖI: Chỉ chấp nhận đuôi @gmail.com
    // Nếu email không kết thúc bằng cụm từ '@gmail.com', chặn lại ngay!
    if (!str_ends_with(strtolower($email), '@gmail.com')) {
        echo json_encode(["status" => "error", "message" => "Hệ thống chỉ chấp nhận địa chỉ Email tên miền @gmail.com!"]);
        exit;
    }

    // 4. Kiểm tra khớp mật khẩu
    if ($password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Mật khẩu xác nhận không khớp!"]);
        exit;
    }

    // 5. MỌI THỨ HỢP LỆ RỒI MỚI GỌI ĐẾN DATABASE
    include_once __DIR__ . '/pages/models/Usermodels.php'; 

    $userModel = new UserModel();
    $result = $userModel->register($fullname, $email, $username, $password);

    if ($result === true) {
        echo json_encode(["status" => "success", "message" => "Đăng ký tài khoản Wine Shop thành công!"]);
    } else {
        echo json_encode(["status" => "error", "message" => $result]);
    }
}
<?php
if (ob_get_length()) {
    ob_clean();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thiết lập định dạng dữ liệu trả về là JSON sạch
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../models/Reviewmodels.php';    

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Kiểm tra xem User đã đăng nhập chưa
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "Phiên đăng nhập hết hạn hoặc chưa đăng nhập. Vui lòng đăng nhập lại!"]);
        exit;
    }

    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
    $comment = $_POST['comment'] ?? '';

    if ($productId <= 0 || empty(trim($comment))) {
        echo json_encode(["status" => "error", "message" => "Vui lòng điền nội dung đánh giá!"]);
        exit;
    }

    $userId = $_SESSION['user_id'];

    // 3. Khởi tạo đối tượng Model (Lưu ý: Tên class trong file của bạn là Reviewmodel hay Reviewmodels thì viết cho đúng nhé)
    // Nếu trong file Reviewmodels.php bạn đặt tên class là `class Reviewmodel` thì giữ nguyên dòng dưới:
    $reviewModel = new Reviewmodel();
    
    // Nếu bạn đặt tên class là `class Reviewmodels` thì đổi thành: $reviewModel = new Reviewmodels();
    
    $result = $reviewModel->insertReview($userId, $productId, $rating, $comment);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Gửi đánh giá thành công! Cảm ơn bạn. 🍷"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi CSDL: Hệ thống không thể ghi nhận đánh giá lúc này!"]);
    }
    exit;
}
?>
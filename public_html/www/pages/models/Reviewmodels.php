<?php
require_once __DIR__ . '/../config/database.php';

class Reviewmodel {

    // 1. GIỮ NGUYÊN: Lấy đánh giá, JOIN tên user, giới hạn số lượng cho trang chủ
    public function getRecent($limit = 6) {
        global $conn;
        $limit = (int)$limit;

        $sql = "SELECT reviews.id, reviews.rating, reviews.comment,
                       users.fullname,
                       products.name AS product_name
                FROM reviews
                JOIN users ON reviews.user_id = users.id
                LEFT JOIN products ON reviews.product_id = products.id
                ORDER BY reviews.created_at DESC
                LIMIT $limit";

        $result = mysqli_query($conn, $sql);
        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        return $reviews;
    }

    public function insertReview($userId, $productId, $rating, $comment) {
        global $conn; 

        // Làm sạch dữ liệu để bảo mật, chống hack SQL Injection
        $userId = (int)$userId;
        $productId = (int)$productId;
        $rating = (int)$rating;
        $comment = mysqli_real_escape_string($conn, trim($comment));

        // Câu lệnh insert dữ liệu vào bảng reviews trong database
        $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) 
                VALUES ($userId, $productId, $rating, '$comment', NOW())";
        
        // Thực thi câu lệnh, nếu thành công trả về true, thất bại trả về false
        if (mysqli_query($conn, $sql)) {
            return true;
        }
        return false;
    }

public function countAll() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM reviews");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

public function getAllWithDetails() {
    global $conn;
    $sql = "SELECT r.id, r.product_id, r.rating, r.comment, r.created_at,
                   u.username, u.fullname,
                   p.name AS product_name
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            LEFT JOIN products p ON p.id = r.product_id
            ORDER BY r.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

public function delete($id) {
    global $conn;
    $id = (int)$id;
    return mysqli_query($conn, "DELETE FROM reviews WHERE id=$id");
}
}
?>
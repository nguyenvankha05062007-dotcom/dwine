<?php
require_once __DIR__ . '/../config/database.php';

class Ordermodel {

    public function createOrder($user_id, $items, $total, $address) {
        global $conn;
        mysqli_begin_transaction($conn);

        try {
            $sql = "INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ids", $user_id, $total, $address);
            mysqli_stmt_execute($stmt);
            $order_id = mysqli_insert_id($conn);

            $sql2 = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($conn, $sql2);

            foreach ($items as $item) {
                mysqli_stmt_bind_param($stmt2, "iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                mysqli_stmt_execute($stmt2);

                $sql3 = "UPDATE products SET stock = stock - ? WHERE id = ?";
                $stmt3 = mysqli_prepare($conn, $sql3);
                mysqli_stmt_bind_param($stmt3, "ii", $item['quantity'], $item['product_id']);
                mysqli_stmt_execute($stmt3);
            }

            mysqli_commit($conn);
            return $order_id;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            return false;
        }
    }// Thêm vào bên trong class Ordermodel

public function countAll() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

public function totalRevenue() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) as rev FROM orders WHERE status='completed'");
    $row = mysqli_fetch_assoc($result);
    return $row['rev'];
}

public function getRecent($limit = 5) {
    global $conn;
    $limit = (int)$limit;
    $sql = "SELECT o.id, o.total_amount as total, o.status, u.username
            FROM orders o
            JOIN users u ON u.id = o.user_id
            ORDER BY o.id DESC LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

public function getAllWithUser() {
    global $conn;
    // Đã thêm LEFT JOIN tới order_details và products để lấy chi tiết sản phẩm
    // Dùng GROUP_CONCAT để gom tên và số lượng thành 1 chuỗi, cách nhau bằng dấu xuống dòng (\n)
    $sql = "SELECT o.id, o.total_amount as total, o.status,
                   o.shipping_address, o.created_at, u.username,
                   GROUP_CONCAT(CONCAT(p.name, ' (x', od.quantity, ')') SEPARATOR '\n') as order_details
            FROM orders o
            JOIN users u ON u.id = o.user_id
            LEFT JOIN order_details od ON od.order_id = o.id
            LEFT JOIN products p ON p.id = od.product_id
            GROUP BY o.id
            ORDER BY o.id DESC";
            
    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

public function updateStatus($id, $status) {
    global $conn;
    $id = (int)$id;
    
    $allowed = ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'];
    
    if (!in_array($status, $allowed)) return false;
    $status = mysqli_real_escape_string($conn, $status);
    return mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$id");
}
// Doanh thu 7 ngày gần nhất
public function getRevenueByDay($days = 7) {
    global $conn;
    $days = (int)$days; 

    $sql = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue 
            FROM orders 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
            AND status = 'completed'
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC";
            
    $result = mysqli_query($conn, $sql);
    $rows = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

// Đơn hàng theo trạng thái
public function countByStatus() {
    global $conn;
    $sql = "SELECT status, COUNT(*) as total FROM orders GROUP BY status";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[$row['status']] = $row['total'];
    return $rows;
}
public function getByUser($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    $sql = "SELECT o.*, 
                GROUP_CONCAT(p.name SEPARATOR ', ') as product_names,
                GROUP_CONCAT(od.quantity SEPARATOR ', ') as quantities
            FROM orders o
            LEFT JOIN order_details od ON od.order_id = o.id
            LEFT JOIN products p ON p.id = od.product_id
            WHERE o.user_id = $user_id
            GROUP BY o.id
            ORDER BY o.id DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}
}
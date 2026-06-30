<?php
header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    echo json_encode([]);
    exit;
}

try {
    // Kết nối Database 
    $db = new PDO("mysql:host=localhost;dbname=dwineidv_wineshop;charset=utf8", "dwineidv_wineshop", "Matkhau123@");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Truy vấn sản phẩm có tên chứa ký tự đang tìm, tối đa trả về 6 sản phẩm
    $sql = "SELECT id, name, price, image FROM products WHERE name LIKE :query LIMIT 6";
    $stmt = $db->prepare($sql);
    
    // Gắn thêm % 2 bên để tìm kiếm chuỗi bất kỳ chứa từ khóa
    $stmt->execute(['query' => "%$q%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Trả kết quả về dạng chuỗi JSON cho Javascript đọc
    echo json_encode($products);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>
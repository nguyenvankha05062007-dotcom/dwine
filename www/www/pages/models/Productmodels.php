<?php
// File: pages/models/Productmodels.php

// TỰ ĐỘNG QUÉT VÀ NẠP DATABASE CHÍNH XÁC DÙ DỰ ÁN ĐẶT Ở ĐÂU
if (defined('ROOT_PATH') && file_exists(ROOT_PATH . '/config/database.php')) {
    require_once ROOT_PATH . '/config/database.php';
} else {
    // Thử tìm theo cấu trúc thông thường (config nằm trong public_html)
    $path1 = dirname(__DIR__, 2) . '/config/database.php';
    // Thử tìm theo cấu trúc config nằm NGOÀI public_html (ngang hàng public_html)
    $path2 = dirname(__DIR__, 3) . '/config/database.php';
    // Thử tìm theo cấu trúc config nằm ngay trong thư mục pages
    $path3 = dirname(__DIR__) . '/config/database.php';

    if (file_exists($path1)) {
        require_once $path1;
    } elseif (file_exists($path2)) {
        require_once $path2;
    } elseif (file_exists($path3)) {
        require_once $path3;
    } else {
        // Nếu tất cả các hướng tìm kiếm tự động thất bại, dùng đường dẫn tuyệt đối trực tiếp của XAMPP để sửa cháy
        require_once 'C:/xampp/htdocs/CNTT2411039_PhamNguyenThanhDong/public_html/config/database.php'; 
    }
}

class Productmodel {

    // Hàm lấy danh sách sản phẩm có áp dụng bộ lọc thông minh nâng cao
    public function getAdvancedFiltered($filters = []) {
        global $conn;
        $conditions = [];

        // 1. Lọc theo Category (Danh mục đơn)
        if (!empty($filters['category_id'])) {
            $conditions[] = "category_id = " . (int)$filters['category_id'];
        }

        // 2. Lọc theo Xuất xứ (Chọn nhiều mục - Mảng)
        if (!empty($filters['origins']) && is_array($filters['origins'])) {
            $escaped_origins = array_map(function($origin) use ($conn) {
                return "'" . mysqli_real_escape_string($conn, $origin) . "'";
            }, $filters['origins']);
            $conditions[] = "origin IN (" . implode(",", $escaped_origins) . ")";
        }

        // 3. Lọc theo Thể tích (Chọn nhiều mục - Mảng)
        if (!empty($filters['volumes']) && is_array($filters['volumes'])) {
            $escaped_volumes = array_map('intval', $filters['volumes']);
            $conditions[] = "volume_ml IN (" . implode(",", $escaped_volumes) . ")";
        }

        // 4. Lọc theo Khoảng nồng độ cồn (Chọn nhiều mục - Mảng)
        if (!empty($filters['alcohol_ranges']) && is_array($filters['alcohol_ranges'])) {
            $alcohol_conds = [];
            foreach ($filters['alcohol_ranges'] as $range) {
                if ($range === 'low') {
                    $alcohol_conds[] = "(alcohol_percent > 5 AND alcohol_percent < 20)";
                } elseif ($range === 'medium') {
                    $alcohol_conds[] = "(alcohol_percent >= 20 AND alcohol_percent < 35)";
                } elseif ($range === 'high') {
                    $alcohol_conds[] = "(alcohol_percent >= 35)";
                }
            }
            if (!empty($alcohol_conds)) {
                $conditions[] = "(" . implode(" OR ", $alcohol_conds) . ")";
            }
        }

        // 5. Lọc theo Tình trạng kho hàng (Chọn nhiều mục - Mảng)
        if (!empty($filters['status']) && is_array($filters['status'])) {
            $status_conds = [];
            if (in_array('instock', $filters['status'])) {
                $status_conds[] = "stock > 0";
            }
            if (in_array('outofstock', $filters['status'])) {
                $status_conds[] = "stock = 0";
            }
            if (!empty($status_conds)) {
                $conditions[] = "(" . implode(" OR ", $status_conds) . ")";
            }
        }

        // 6. Lọc theo Khoảng giá (Min - Max)
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $min = (float)$filters['min_price'];
            $conditions[] = "price >= $min";
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $max = (float)$filters['max_price'];
            $conditions[] = "price <= $max";
        }

        // Xây dựng câu lệnh SQL tổng hợp
        $sql = "SELECT * FROM products";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY id DESC";

        $result = mysqli_query($conn, $sql);
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // Lấy danh sách xuất xứ hiện có trong DB
    public function getUniqueOrigins() {
        global $conn;
        $sql = "SELECT DISTINCT origin FROM products WHERE origin IS NOT NULL AND origin != '' ORDER BY origin ASC";
        $result = mysqli_query($conn, $sql);
        $origins = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $origins[] = $row['origin'];
        }
        return $origins;
    }

    // Lấy danh sách các mức thể tích hiện có trong DB
    public function getUniqueVolumes() {
        global $conn;
        $sql = "SELECT DISTINCT volume_ml FROM products WHERE volume_ml IS NOT NULL ORDER BY volume_ml ASC";
        $result = mysqli_query($conn, $sql);
        $volumes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $volumes[] = $row['volume_ml'];
        }
        return $volumes;
    }

    // Lấy vài sản phẩm nổi bật cho trang chủ (ví dụ lấy 4 sản phẩm mới nhất)
    public function getFeatured($limit = 4) {
        global $conn;
        $limit = (int)$limit;
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $limit";
        $result = mysqli_query($conn, $sql);
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    // Lấy tất cả category (dùng cho menu lọc sản phẩm)
    public function getCategories() {
        global $conn;
        $sql = "SELECT * FROM categories";
        $result = mysqli_query($conn, $sql);
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        return $categories;
    }

    public function getAll($category_id = null) {
        global $conn;
        if ($category_id) {
            $category_id = (int)$category_id;
            $sql = "SELECT * FROM products WHERE category_id = $category_id ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM products ORDER BY id DESC";
        }
        $result = mysqli_query($conn, $sql);
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }
    
    public function getById($id) {
        global $conn;
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Thêm hàm lấy sản phẩm cùng danh mục (loại trừ sản phẩm đang xem)
    public function getRelatedProducts($category_id, $current_product_id, $limit = 4) {
        global $conn;
        $category_id = (int)$category_id;
        $current_product_id = (int)$current_product_id;
        $limit = (int)$limit;

        $sql = "SELECT * FROM products 
                WHERE category_id = $category_id AND id != $current_product_id 
                ORDER BY id DESC LIMIT $limit";

        $result = mysqli_query($conn, $sql);
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }
        return $products;
    }

    // Hàm lấy sản phẩm tương tự (Khớp >= 2/4 tiêu chí)
    public function getSimilarProducts($product, $limit = 4) {
        global $conn;
        
        $current_id = (int)$product['id'];
        $category_id = (int)$product['category_id'];
        
        $origin = mysqli_real_escape_string($conn, $product['origin'] ?? '');
        $alcohol = (float)($product['alcohol_percent'] ?? 0);
        $volume = (int)($product['volume_ml'] ?? 0);
        $limit = (int)$limit;

        $sql = "SELECT * FROM products 
                WHERE id != $current_id 
                AND (
                    (category_id = $category_id) + 
                    (origin = '$origin') + 
                    (alcohol_percent = $alcohol) + 
                    (volume_ml = $volume)
                ) >= 2
                ORDER BY id DESC LIMIT $limit";

        $result = mysqli_query($conn, $sql);
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }
        return $products;
    }

    public function getMaxPrice() {
        global $conn;
        $sql = "SELECT MAX(price) as max_price FROM products";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['max_price'] ? (float)$row['max_price'] : 0;
    }
    // Thêm vào bên trong class Productmodel

public function countAll() {
    global $conn;
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

public function getTopSelling($limit = 5) {
    global $conn;
    $limit = (int)$limit;
    $sql = "SELECT p.name, COALESCE(SUM(od.quantity), 0) as sold
            FROM products p
            LEFT JOIN order_details od ON od.product_id = p.id
            GROUP BY p.id
            ORDER BY sold DESC
            LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) $rows[] = $row;
    return $rows;
}

public function add($data) {
    global $conn;
    $name     = mysqli_real_escape_string($conn, $data['name']);
    $price    = (float)$data['price'];
    $stock    = (int)$data['stock'];
    $cat_id   = (int)($data['category_id'] ?? 0);
    $origin   = mysqli_real_escape_string($conn, $data['origin'] ?? '');
    $alcohol  = (float)($data['alcohol_percent'] ?? 0);
    $volume   = (int)($data['volume_ml'] ?? 0);
    $image    = mysqli_real_escape_string($conn, $data['image'] ?? '');
    $desc     = mysqli_real_escape_string($conn, $data['description'] ?? '');

    $sql = "INSERT INTO products 
                (name, price, stock, category_id, origin, alcohol_percent, volume_ml, image, description)
            VALUES 
                ('$name', $price, $stock, $cat_id, '$origin', $alcohol, $volume, '$image', '$desc')";
    return mysqli_query($conn, $sql);
}

public function update($id, $data) {
    global $conn;
    $id    = (int)$id;
    $name  = mysqli_real_escape_string($conn, $data['name']);
    $price = (float)$data['price'];
    $stock = (int)$data['stock'];
    $cat_id = (int)($data['category_id'] ?? 0);
    $origin  = mysqli_real_escape_string($conn, $data['origin'] ?? '');
    $alcohol = (float)($data['alcohol_percent'] ?? 0);
    $volume  = (int)($data['volume_ml'] ?? 0);
    $desc    = mysqli_real_escape_string($conn, $data['description'] ?? '');

    $sql = "UPDATE products SET
                name='$name', price=$price, stock=$stock,
                category_id=$cat_id, origin='$origin',
                alcohol_percent=$alcohol, volume_ml=$volume,
                description='$desc'
            WHERE id=$id";
    return mysqli_query($conn, $sql);
}

public function updateImage($id, $image) {
    global $conn;
    $id    = (int)$id;
    $image = mysqli_real_escape_string($conn, $image);
    return mysqli_query($conn, "UPDATE products SET image='$image' WHERE id=$id");
}

public function delete($id) {
    global $conn;
    $id = (int)$id;
    return mysqli_query($conn, "DELETE FROM products WHERE id=$id");
}
}
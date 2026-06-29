<?php
class UserModel {
    private $db;

    public function __construct() {
        try {
            // ĐÃ SỬA: Đổi "wine_shop" thành "wineshop" viết liền cho đúng chuẩn tên DB của bạn
            $this->db = new PDO("mysql:host=localhost;dbname=dwineidv_wineshop;charset=utf8", "dwineidv_wineshop", "Matkhau123@");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // ĐÃ SỬA: In hẳn lỗi thật ra để biết lỗi do tên DB hay do chưa bật XAMPP
            echo json_encode(["status" => "error", "message" => "Lỗi kết nối CSDL thực tế: " . $e->getMessage()]);
            exit;
        }
    }

    // Hàm xử lý Đăng ký tài khoản
    public function register($fullname, $email, $username, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Định dạng email không hợp lệ!";
        }

        if (strlen($password) < 6) {
            return "Mật khẩu phải chứa ít nhất 6 ký tự!";
        }

        $checkStmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $checkStmt->execute([$username, $email]);
        if ($checkStmt->rowCount() > 0) {
            return "Tài khoản hoặc Email này đã tồn tại trên hệ thống!";
        }

        // Mã hóa mật khẩu chuẩn Bcrypt bảo mật cao
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, 'user')";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$fullname, $email, $username, $hashedPassword])) {
            return true; 
        }
        return "Đăng ký thất bại, vui lòng thử lại sau.";
    }

    
    // Hàm xử lý Đăng nhập tài khoản
    public function login($username, $password) {

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Tên đăng nhập không tồn tại!";
        }

        // KIỂM TRA TRẠNG THÁI TÀI KHOẢN
        if (isset($user['status']) && $user['status'] === 'disabled') {
            return "Tài khoản của bạn đã bị vô hiệu hóa bởi Admin!";
        }

        if (password_verify($password, $user['password'])) {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role']     = $user['role'];
            
            return true; 
        } else {
            return "Mật khẩu nhập vào không chính xác!";
        }
    }
    // Thêm vào bên trong class UserModel

public function countAll() {
    $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE username != 'deleted_user'");
    return $stmt->fetchColumn();
}

public function getAll() {
    $stmt = $this->db->query("SELECT id, fullname, username, email, role, status, created_at FROM users WHERE username != 'deleted_user' ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateRole($id, $role) {
    $allowed = ['user', 'admin'];
    if (!in_array($role, $allowed)) return false;
    $stmt = $this->db->prepare("UPDATE users SET role=? WHERE id=?");
    return $stmt->execute([$role, (int)$id]);
}

public function deleteUser($id) {
    try {
        $id = (int)$id;

        $stmtCheck = $this->db->prepare("SELECT id FROM users WHERE username = 'deleted_user'");
        $stmtCheck->execute();
        $systemUser = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$systemUser) {
            $stmtCreate = $this->db->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES ('Khách hàng đã xóa', 'deleted_user', 'deleted@dwine.vn', 'system_protected_123', 'user')");
            $stmtCreate->execute();
            $systemUserId = $this->db->lastInsertId();
        } else {
            $systemUserId = $systemUser['id'];
        }

        if ($id == $systemUserId) {
            return false;
        }

        $stmtUpdateOrders = $this->db->prepare("UPDATE orders SET user_id = ? WHERE user_id = ?");
        $stmtUpdateOrders->execute([$systemUserId, $id]);

        $stmtDeleteUser = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmtDeleteUser->execute([$id]);
        
    } catch (PDOException $e) {
        return false;
    }
}
public function updateUserAdmin($id, $role, $status, $password = '') {
    try {
        $id = (int)$id;
        $allowedRoles = ['user', 'admin'];
        $allowedStatus = ['active', 'disabled'];
        if (!in_array($role, $allowedRoles) || !in_array($status, $allowedStatus)) return false;

        // Nếu admin nhập mật khẩu mới
        if (!empty(trim($password))) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET role = ?, status = ?, password = ? WHERE id = ?");
            return $stmt->execute([$role, $status, $hashedPassword, $id]);
        } else {
            // Nếu để trống mật khẩu thì giữ nguyên mật khẩu cũ, chỉ đổi quyền và trạng thái
            $stmt = $this->db->prepare("UPDATE users SET role = ?, status = ? WHERE id = ?");
            return $stmt->execute([$role, $status, $id]);
        }
    } catch (PDOException $e) {
        return false;
    }
}
}
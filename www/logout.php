<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=home");
    exit;
}

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    session_unset();   
    session_destroy(); 
    header("Location: index.php?page=home");
    exit;
}

$cancel_link = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'index.php?page=admin' : 'index.php?page=home';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    
    <title>Đăng xuất</title>
    <link rel="stylesheet" href="../bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <style>
        body { background: #121212; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        
        .box {
            background: rgba(20,3,7,0.95);
            border: 1px solid #5a101d;
            border-radius: 16px;
            padding: 40px 36px;
            text-align: center;
            max-width: 420px; /* Tăng max-width mặc định một chút */
            width: 90%;
            box-shadow: 0 15px 40px rgba(200,16,46,0.2);
        }
        .icon { font-size: 52px; margin-bottom: 16px; }
        h5 { color: #fff; font-weight: 700; margin-bottom: 12px; font-size: 24px; }
        p { color: #aaa; font-size: 15px; margin-bottom: 32px; line-height: 1.5;}
        
        /* Cụm 2 nút bấm */
        .btn-group-custom {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn-yes {
            background: #c8102e; color: #fff; border: none;
            padding: 12px 28px; border-radius: 8px; font-weight: 600;
            text-decoration: none; transition: .2s;
            flex: 1;
        }
        .btn-yes:hover { background: #e01638; color: #fff; }
        
        .btn-no {
            background: #262626; color: #aaa; border: 1px solid #333;
            padding: 12px 28px; border-radius: 8px; font-weight: 600;
            text-decoration: none; transition: .2s;
            flex: 1;
        }
        .btn-no:hover { background: #333; color: #fff; }

        @media (max-width: 576px) {
            .box {
                width: 95%;
                padding: 45px 20px;
            }
            .icon { font-size: 60px; } 
            h5 { font-size: 26px; } 
            p { font-size: 16px; margin-bottom: 35px; } 
            
            .btn-group-custom {
                flex-direction: 
                gap: 12px;
            }
            .btn-yes, .btn-no {
                padding: 14px 20px; 
                font-size: 17px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon">🍷</div>
        <h5>Bạn muốn đăng xuất?</h5>
        <p>Phiên đăng nhập của bạn sẽ kết thúc.<br>Hẹn gặp lại tại D-WINE!</p>
        
        <div class="btn-group-custom">
            <a href="logout.php?confirm=yes" class="btn-yes">Đăng xuất</a>
            <a href="<?= $cancel_link ?>" class="btn-no">Hủy</a>
        </div>
    </div>

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload(); 
            }
        });
    </script>
</body>
</html>
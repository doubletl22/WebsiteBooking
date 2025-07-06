<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] == 'admin') {
        header("Location: index.php");
    } elseif ($_SESSION['user_role'] == 'doctor') {
        header("Location: doctor_dashboard.php");
    }
    exit();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../includes/db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = "Vui lòng nhập đầy đủ email và mật khẩu.";
    } else {
        $sql = "SELECT id, name, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                if ($user['role'] == 'admin' || $user['role'] == 'doctor') {
                     $_SESSION['user_id'] = $user['id'];
                     $_SESSION['user_name'] = $user['name'];
                     $_SESSION['user_role'] = $user['role'];

                     if ($user['role'] == 'admin') {
                        header("Location: index.php");
                    } elseif ($user['role'] == 'doctor') {
                        header("Location: doctor_dashboard.php");
                    }
                    exit();
                } else {
                    $error_message = "Tài khoản của bạn không có quyền truy cập trang này.";
                }
            } else {
                $error_message = "Email hoặc mật khẩu không chính xác.";
            }
        } else {
            $error_message = "Email hoặc mật khẩu không chính xác.";
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập trang Quản trị</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f5f5;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-signin {
            width: 100%;
            max-width: 380px;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .form-signin h1 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-control {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .alert-danger {
            padding: 12px;
            margin-bottom: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            text-align: center;
        }
        .copyright {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="form-signin">
        <form method="POST" action="login.php">
            <h1>Đăng nhập Nội Bộ</h1>

            <?php if (!empty($error_message)): ?>
                <div class="alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="example@email.com" required autofocus>
            </div>
            
            <div class="form-group">
                 <label for="inputPassword">Mật khẩu</label>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Mật khẩu" required>
            </div>
            
            <button class="btn" type="submit">Đăng nhập</button>
            <p class="copyright">&copy; 2025 - Phòng khám Nha Khoa</p>
        </form>
    </div>
</body>
</html>
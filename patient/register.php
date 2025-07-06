<?php
session_start();
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../includes/db.php';

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'patient';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Vui lòng điền đầy đủ các trường bắt buộc.";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        $sql_check = "SELECT id FROM users WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error = "Email đã được sử dụng. Vui lòng chọn email khác.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $phone, $hashed_password, $role);
            
            if ($stmt->execute()) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
            } else {
                $error = "Đã xảy ra lỗi. Vui lòng thử lại.";
            }
        }
    }
}

require '../includes/header_public.php';
?>

<title>Đăng ký - Phòng khám Nha Khoa</title>

<div class="page-section">
    <div class="login-container">
        <div class="login-card">
            <h2 class="section-title">Đăng ký tài khoản</h2>

            <?php if(!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="custom-form-group">
                    <label for="name">Họ và Tên (*)</label>
                    <input type="text" name="name" id="name" class="custom-form-control" required>
                </div>
                <div class="custom-form-group">
                    <label for="email">Email (*)</label>
                    <input type="email" name="email" id="email" class="custom-form-control" required>
                </div>
                <div class="custom-form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" class="custom-form-control">
                </div>
                <div class="custom-form-group">
                    <label for="password">Mật khẩu (*)</label>
                    <input type="password" name="password" id="password" class="custom-form-control" required>
                </div>
                <div class="custom-form-group">
                    <label for="confirm_password">Xác nhận Mật khẩu (*)</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="custom-form-control" required>
                </div>
                <button type="submit" class="custom-btn btn-block">Đăng ký</button>
            </form>

            <div class="login-footer">
                Đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a>
            </div>
        </div>
    </div>
</div>

<?php 
require '../includes/footer_public.php'; 
?>
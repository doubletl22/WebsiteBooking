<?php
session_start();

define('BASE_URL', '/WebsiteBooking/');

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'patient') {
    header("Location: " . BASE_URL . "patient/profile.php");
    exit();
}

$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../includes/db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = "Vui lòng nhập email và mật khẩu.";
    } else {
        $sql = "SELECT id, name, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                if ($user['role'] == 'patient') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    header("Location: " . BASE_URL . "index.php");
                    exit();
                } else {
                    $error_message = "Tài khoản này không phải là tài khoản bệnh nhân.";
                }
            } else {
                $error_message = "Email hoặc mật khẩu không chính xác.";
            }
        } else {
            $error_message = "Email hoặc mật khẩu không chính xác.";
        }
    }
}

require '../includes/header_public.php';
?>

<div class="page-section">
    <div class="login-container">
        <div class="login-card">
            <h2 class="section-title">Đăng nhập</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form action="<?php echo BASE_URL; ?>patient/login.php" method="POST">
                <div class="custom-form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="custom-form-control" required>
                </div>
                <div class="custom-form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" name="password" id="password" class="custom-form-control" required>
                </div>
                <button type="submit" class="custom-btn btn-block">Đăng nhập</button>
            </form>

            <div class="login-footer">
                Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>patient/register.php">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>

<?php
require '../includes/footer_public.php';
?>
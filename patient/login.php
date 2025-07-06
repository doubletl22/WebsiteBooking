<?php
session_start();

// 1. Định nghĩa BASE_URL trỏ về thư mục gốc của ứng dụng
define('BASE_URL', '/websitebooking/');

// Kiểm tra nếu người dùng đã đăng nhập và là bệnh nhân, chuyển hướng họ đến trang profile
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'patient') {
    // 2. Sử dụng BASE_URL để tạo đường dẫn tuyệt đối
    header("Location: " . BASE_URL . "patient/profile.php");
    exit();
}

$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Đường dẫn tới file db.php vẫn là tương đối vì chúng ở cùng cấp cấu trúc
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

                    // 3. Chuyển hướng đến trang index.php ở thư mục gốc sau khi đăng nhập thành công
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

// Nhúng header (đường dẫn tương đối vẫn ổn)
require '../includes/header_public.php';
?>
<title>Đăng nhập - Phòng khám Nha Khoa</title>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Đăng nhập</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>patient/login.php" method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>patient/register.php">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Nhúng footer (đường dẫn tương đối vẫn ổn)
require '../includes/footer_public.php';
?>
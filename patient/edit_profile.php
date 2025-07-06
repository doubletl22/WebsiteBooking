<?php
require '../includes/check_patient_auth.php';
require '../includes/db.php';

$patient_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email)) {
        $error = "Họ tên và email là bắt buộc.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Định dạng email không hợp lệ.";
    } else {
        $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt_check_email->bind_param("si", $email, $patient_id);
        $stmt_check_email->execute();
        if ($stmt_check_email->get_result()->num_rows > 0) {
            $error = "Email này đã được sử dụng bởi một tài khoản khác.";
        }
        $stmt_check_email->close();
    }

    if (!empty($password)) {
        if ($password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp.";
        } elseif (strlen($password) < 6) {
            $error = "Mật khẩu phải có ít nhất 6 ký tự.";
        }
    }

    if (empty($error)) {
        $stmt_update_user = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt_update_user->bind_param("sssi", $name, $email, $phone, $patient_id);
        $stmt_update_user->execute();
        $stmt_update_user->close();

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt_update_pass->bind_param("si", $hashed_password, $patient_id);
            $stmt_update_pass->execute();
            $stmt_update_pass->close();
        }
        
        $_SESSION['user_name'] = $name;
        $success = "Cập nhật thông tin thành công!";
    }
}

$stmt_get_user = $conn->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt_get_user->bind_param("i", $patient_id);
$stmt_get_user->execute();
$user = $stmt_get_user->get_result()->fetch_assoc();
$stmt_get_user->close();

require '../includes/header_public.php';
?>

<title>Cập nhật thông tin - Nha Khoa Hạnh Phúc</title>

<div class="page-section profile-page">
    <div class="custom-container">
        <div class="custom-row">
            <div class="custom-col-3">
                <div class="profile-sidebar">
                    <div class="profile-user-info">
                        <div class="avatar-icon"><i class="fas fa-user"></i></div>
                        <h5 class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h5>
                        <p class="user-role">Bệnh nhân</p>
                    </div>
                    <nav class="profile-nav">
                        <ul>
                            <li><a href="../booking.php"><i class="fas fa-calendar-check"></i> Đặt lịch hẹn mới</a></li>
                            <li><a href="profile.php"><i class="fas fa-user-circle"></i> Hồ sơ của tôi</a></li>
                            <li><a href="my_appointments.php"><i class="fas fa-history"></i> Lịch sử cuộc hẹn</a></li>
                            <li><a href="edit_profile.php" class="active"><i class="fas fa-user-edit"></i> Cập nhật thông tin</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="custom-col-9">
                <div class="profile-content">
                    <div class="appointments-card"> <h4>Cập nhật thông tin cá nhân</h4>

                        <?php if ($error): ?>
                            <div class="error-message"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="success-message"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form action="edit_profile.php" method="POST" class="profile-form">
                             <div class="custom-form-group">
                                <label for="name">Họ và Tên (*)</label>
                                <input type="text" id="name" name="name" class="custom-form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="custom-form-group">
                                <label for="email">Email (*)</label>
                                <input type="email" id="email" name="email" class="custom-form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="custom-form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" class="custom-form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>

                            <hr class="form-divider">
                            <p class="form-text-muted" style="margin-top:0; margin-bottom:1rem;">Để trống phần mật khẩu nếu bạn không muốn thay đổi.</p>

                            <div class="custom-form-group">
                                <label for="password">Mật khẩu mới</label>
                                <input type="password" id="password" name="password" class="custom-form-control">
                            </div>
                            <div class="custom-form-group">
                                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="custom-form-control">
                            </div>

                            <button type="submit" class="custom-btn" style="margin-top: 1rem;">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
require '../includes/footer_public.php';
?>
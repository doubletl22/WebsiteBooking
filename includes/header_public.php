<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/WebsiteBooking/');
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Nha Khoa Hạnh Phúc</title>
</head>
<body>
    <header class="main-header">
        <nav class="main-nav custom-container">
            <a class="nav-logo" href="<?php echo BASE_URL; ?>index.php"><strong>Nha Khoa Hạnh Phúc</strong></a>
            
            <ul class="nav-menu">
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>index.php#price-list">Dịch vụ</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>index.php#featured-doctors">Đội ngũ Bác sĩ</a></li>
            </ul>

            <ul class="nav-user">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'patient'): ?>
                    <li class="nav-item user-menu">
                        <span class="nav-link">Xin chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <div class="user-dropdown">
                            <a href="<?php echo BASE_URL; ?>patient/profile.php">Hồ sơ của tôi</a>
                            <a href="<?php echo BASE_URL; ?>patient/my_appointments.php">Lịch hẹn của tôi</a>
                            <a href="<?php echo BASE_URL; ?>patient/logout.php">Đăng xuất</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>patient/register.php">Đăng ký</a>
                    </li>
                    <li class="nav-item">
                        <a class="custom-btn" href="<?php echo BASE_URL; ?>patient/login.php">Đăng nhập</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>
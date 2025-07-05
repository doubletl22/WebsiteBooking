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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">

    </head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="text-blue" href="<?php echo BASE_URL; ?>index.php"><strong>Nha Khoa Hạnh Phúc</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php#services">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php#doctors-section">Đội ngũ Bác sĩ</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'patient'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Xin chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>patient/profile.php">Hồ sơ của tôi</a>
                                <a class="dropdown-item" href="#">Lịch hẹn của tôi</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>patient/logout.php">Đăng xuất</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item" style="margin-top: 1px;">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>patient/register.php"><b>Đăng ký</b></a>
                        </li>
                        <li class="nav-item" style="margin-top: 5px;">
                            <a class="btn btn-primary" href="<?php echo BASE_URL; ?>patient/login.php"><b>Đăng nhập</b></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
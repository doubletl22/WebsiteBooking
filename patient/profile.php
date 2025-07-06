<?php

require '../includes/check_patient_auth.php';
require '../includes/header_public.php';
?>

<div class="container my-5">
    <div class="row">

        <div class="col-lg-3">
            <div class="profile-sidebar">
                <div class="profile-user-info">
                    <div class="avatar-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h5>
                    <p class="user-role">Bệnh nhân</p>
                </div>
                
                <nav class="profile-nav">
                    <ul class="list-unstyled">
                        <li>
                            <a href="../booking.php" class="active"><i class="fas fa-calendar-check"></i> Đặt lịch hẹn mới</a>
                        </li>
                        <li>
                            <a href="#"><i class="fas fa-history"></i> Xem lịch sử cuộc hẹn</a>
                        </li>
                        <li>
                            <a href="#"><i class="fas fa-user-edit"></i> Cập nhật thông tin</a>
                        </li>
                        <li>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card welcome-card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Chào mừng bạn quay trở lại!</h4>
                    <p class="card-text">Tại đây bạn có thể quản lý các cuộc hẹn và thông tin cá nhân của mình một cách dễ dàng.</p>
                </div>
            </div>

            <div class="card contact-card">
                <div class="card-body">
                    <h5 class="card-title">Thông tin liên hệ khẩn cấp</h5>
                    <p class="card-text">Nếu cần hỗ trợ ngay, vui lòng gọi đến tổng đài của chúng tôi:</p>
                    <p class="hotline"><strong>1900.xxxx</strong></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
require '../includes/footer_public.php'; 
?>
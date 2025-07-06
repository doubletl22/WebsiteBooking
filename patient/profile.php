<?php
require '../includes/check_patient_auth.php';
require '../includes/header_public.php';
?>

<div class="page-section profile-page">
    <div class="custom-container">
        <div class="custom-row">
            <div class="custom-col-3">
                <div class="profile-sidebar">
                    <div class="profile-user-info">
                        <div class="avatar-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h5>
                        <p class="user-role">Bệnh nhân</p>
                    </div>

                    <nav class="profile-nav">
                        <ul>
                            <li>
                                <a href="../booking.php"><i class="fas fa-calendar-check"></i> Đặt lịch hẹn mới</a>
                            </li>
                            <li>
                                <a href="#" class="active"><i class="fas fa-user-circle"></i> Hồ sơ của tôi</a>
                            </li>
                            <li>
                                <a href="my_appointments.php"><i class="fas fa-history"></i> Xem lịch sử cuộc hẹn</a>
                            </li>
                            <li>
                                <a href="edit_profile.php"><i class="fas fa-user-edit"></i> Cập nhật thông tin</a>
                            </li>
                            <li>
                                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="custom-col-9">
                <div class="profile-content">
                    <div class="welcome-card">
                        <h4>Chào mừng bạn quay trở lại!</h4>
                        <p>Tại đây bạn có thể quản lý các cuộc hẹn và thông tin cá nhân của mình một cách dễ dàng.</p>
                    </div>

                    <div class="contact-card">
                        <h5>Thông tin liên hệ khẩn cấp</h5>
                        <p>Nếu cần hỗ trợ ngay, vui lòng gọi đến tổng đài của chúng tôi:</p>
                        <p class="hotline"><strong>1900.xxxx</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../includes/footer_public.php';
?>
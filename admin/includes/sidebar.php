<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/WebsiteBooking/admin/');
}
?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">

            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo BASE_URL; ?>index.php">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>appointments/appointments.php">
                        Quản lý Lịch hẹn
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>services/services.php">
                        Quản lý Dịch vụ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>doctors/doctor.php">
                        Quản lý Bác sĩ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>patients/patients.php">
                        Quản lý Bệnh nhân
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>doctor_off_list.php">
                        Danh sách ngày nghỉ bác sĩ
                    </a>
                </li>

            <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'doctor'): ?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo BASE_URL; ?>doctor_dashboard.php">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>appointments/appointments.php">
                        Lịch hẹn của tôi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>doctor_off.php">
                        Báo ngày nghỉ
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</nav>
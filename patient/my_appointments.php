<?php
require '../includes/check_patient_auth.php';
require '../includes/header_public.php';
require '../includes/db.php';

$patient_id = $_SESSION['user_id'];

$sql = "SELECT 
            a.appointment_time, 
            a.status,
            a.notes,
            s.name AS service_name,
            u.name AS doctor_name
        FROM appointments a
        LEFT JOIN services s ON a.service_id = s.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        LEFT JOIN users u ON d.user_id = u.id
        WHERE a.patient_id = ?
        ORDER BY a.appointment_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$appointments_result = $stmt->get_result();

// Hàm để hiển thị trạng thái với màu sắc
function get_status_badge($status) {
    switch ($status) {
        case 'pending':
            return '<span class="status-badge status-pending">Chờ xác nhận</span>';
        case 'confirmed':
            return '<span class="status-badge status-confirmed">Đã xác nhận</span>';
        case 'completed':
            return '<span class="status-badge status-completed">Đã hoàn thành</span>';
        case 'cancelled':
            return '<span class="status-badge status-cancelled">Đã hủy</span>';
        default:
            return '<span class="status-badge">' . htmlspecialchars($status) . '</span>';
    }
}
?>

<title>Lịch hẹn của tôi - Nha Khoa Hạnh Phúc</title>

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
                            <li><a href="my_appointments.php" class="active"><i class="fas fa-history"></i> Lịch sử cuộc hẹn</a></li>
                            <li><a href="edit_profile.php"><i class="fas fa-user-edit"></i> Cập nhật thông tin</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="custom-col-9">
                <div class="profile-content">
                    <div class="appointments-card">
                        <h4>Lịch sử cuộc hẹn của bạn</h4>
                        <div class="appointments-table-container">
                            <?php if ($appointments_result->num_rows > 0): ?>
                                <table class="appointments-table">
                                    <thead>
                                        <tr>
                                            <th>Dịch vụ</th>
                                            <th>Bác sĩ</th>
                                            <th>Thời gian hẹn</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($app = $appointments_result->fetch_assoc()): ?>
                                            <tr>
                                                <td data-label="Dịch vụ"><?php echo htmlspecialchars($app['service_name'] ?? 'N/A'); ?></td>
                                                <td data-label="Bác sĩ"><?php echo htmlspecialchars($app['doctor_name'] ?? 'N/A'); ?></td>
                                                <td data-label="Thời gian hẹn"><?php echo date('H:i, d/m/Y', strtotime($app['appointment_time'])); ?></td>
                                                <td data-label="Trạng thái"><?php echo get_status_badge($app['status']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>Bạn chưa có cuộc hẹn nào.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require '../includes/footer_public.php';
?>
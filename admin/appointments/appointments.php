<?php
require '../includes/check_auth.php';
require '../../includes/db.php';

$base_sql = "SELECT 
            a.id, 
            a.appointment_time, 
            a.status,
            p.name AS patient_name,
            d_user.name AS doctor_name,
            s.name AS service_name
        FROM appointments a
        LEFT JOIN users p ON a.patient_id = p.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        LEFT JOIN users AS d_user ON d.user_id = d_user.id
        LEFT JOIN services s ON a.service_id = s.id";

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'doctor') {
    $sql = $base_sql . " WHERE d.user_id = ? ORDER BY a.appointment_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
} else {
    $sql = $base_sql . " ORDER BY a.appointment_time DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

require '../includes/header.php';
require '../includes/sidebar.php';

function getStatusBadge($status) {
    switch ($status) {
        case 'pending': return '<span class="badge badge-warning">Chờ xác nhận</span>';
        case 'confirmed': return '<span class="badge badge-primary">Đã xác nhận</span>';
        case 'completed': return '<span class="badge badge-success">Đã hoàn thành</span>';
        case 'cancelled': return '<span class="badge badge-danger">Đã hủy</span>';
        default: return '<span class="badge badge-secondary">' . htmlspecialchars($status) . '</span>';
    }
}
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?php echo is_admin() ? 'Quản lý Lịch hẹn' : 'Lịch hẹn của tôi'; ?></h1>
        
        <?php if (is_admin()): // Chỉ admin mới có quyền tạo lịch hẹn mới từ đây ?>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="create.php" class="btn btn-sm btn-outline-success">
                Tạo Lịch hẹn mới
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php
        if (isset($_GET['success'])) echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
        if (isset($_GET['error'])) echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bệnh nhân</th>
                    <?php if (is_admin()): ?>
                        <th>Bác sĩ</th>
                    <?php endif; ?>
                    <th>Dịch vụ</th>
                    <th>Thời gian hẹn</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['patient_name'] ?? 'N/A'); ?></td>
                        
                        <?php if (is_admin()): ?>
                            <td><?php echo htmlspecialchars($row['doctor_name'] ?? 'N/A'); ?></td>
                        <?php endif; ?>
                        
                        <td><?php echo htmlspecialchars($row['service_name'] ?? 'N/A'); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['appointment_time'])); ?></td>
                        <td><?php echo getStatusBadge($row['status']); ?></td>
                        <td>
                            <?php if (is_admin()): ?>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">Sửa</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này không?');">Xóa</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled>Xem</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Chưa có lịch hẹn nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
$stmt->close();
$conn->close();
require '../includes/footer.php';
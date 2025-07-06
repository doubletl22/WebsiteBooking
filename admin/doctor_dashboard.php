<?php
require 'includes/check_auth.php';
require 'includes/header.php';
require 'includes/sidebar.php';
require '../includes/db.php';

if ($_SESSION['user_role'] !== 'doctor') {
    header("Location: index.php");
    exit();
}

$doctor_user_id = $_SESSION['user_id'];

$upcoming_sql = "
    SELECT a.id, a.appointment_time, p_user.name as patient_name, s.name as service_name, a.status
    FROM appointments a
    JOIN users p_user ON a.patient_id = p_user.id
    JOIN doctors d ON a.doctor_id = d.id
    JOIN services s ON a.service_id = s.id
    WHERE d.user_id = ? AND a.appointment_time >= NOW()
    ORDER BY a.appointment_time ASC
    LIMIT 10
";
$stmt_upcoming = $conn->prepare($upcoming_sql);
$stmt_upcoming->bind_param("i", $doctor_user_id);
$stmt_upcoming->execute();
$upcoming_result = $stmt_upcoming->get_result();

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
         <h1 class="h2">Chào mừng Bác sĩ, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Lịch hẹn sắp tới của bạn</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bệnh nhân</th>
                            <th>Dịch vụ</th>
                            <th>Ngày hẹn</th>
                            <th>Giờ hẹn</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($upcoming_result && $upcoming_result->num_rows > 0): ?>
                            <?php while($row = $upcoming_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                    <td><?php echo date("d-m-Y", strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo date("H:i", strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo getStatusBadge($row['status']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Bạn không có lịch hẹn nào sắp tới.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php
$stmt_upcoming->close();
$conn->close();
require 'includes/footer.php';
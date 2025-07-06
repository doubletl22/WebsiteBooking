<?php
require 'includes/check_auth.php';

if (!is_admin()) {
    die("Bạn không có quyền truy cập trang này.");
}

require '../includes/db.php';
require 'includes/header.php';
require 'includes/sidebar.php';

$sql = "SELECT 
            t.id, 
            t.start_time, 
            t.end_time, 
            t.reason,
            u.name as doctor_name
        FROM time_offs t
        JOIN doctors d ON t.doctor_id = d.id
        JOIN users u ON d.user_id = u.id
        ORDER BY t.start_time DESC";

$result = $conn->query($sql);

?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Danh sách ngày nghỉ của Bác sĩ</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>STT</th>
                    <th>Tên Bác sĩ</th>
                    <th>Bắt đầu nghỉ</th>
                    <th>Kết thúc nghỉ</th>
                    <th>Lý do</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php $i = 1; // Biến đếm số thứ tự ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo date('H:i - d/m/Y', strtotime($row['start_time'])); ?></td>
                        <td><?php echo date('H:i - d/m/Y', strtotime($row['end_time'])); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Chưa có bác sĩ nào đăng ký ngày nghỉ.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
$conn->close();
require 'includes/footer.php';
?>
<?php
session_start();
require 'includes/header.php';
require 'includes/sidebar.php';
require '../includes/db.php';

if ($_SESSION['user_role'] !== 'doctor') {
    header("Location: index.php");
    exit();
}

$doctor_user_id = $_SESSION['user_id'];
$doctor_sql = "SELECT id FROM doctors WHERE user_id = ?";
$stmt_doctor = $conn->prepare($doctor_sql);
$stmt_doctor->bind_param("i", $doctor_user_id);
$stmt_doctor->execute();
$doctor_result = $stmt_doctor->get_result();
$doctor = $doctor_result->fetch_assoc();
$doctor_id = $doctor['id'] ?? 0;

$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO time_offs (doctor_id, start_time, end_time, reason) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $doctor_id, $start, $end, $reason);
    if ($stmt->execute()) {
        $msg = "<span style='color: green;'>Đăng ký ngày nghỉ thành công!</span>";
    } else {
        $msg = "<span style='color: red;'>Có lỗi xảy ra: " . $conn->error . "</span>";
    }
    $stmt->close();
}
?>

<style>
.main-content {
    padding-left: 250px; 
    min-height: 90vh;
    background: #fff;
}
@media (max-width: 991px) {
    .main-content {
        padding-left: 0;
    }
}
</style>
<style>
    .off-form-container {
        padding: 40px 0;
        width: 100%;
    }
    .off-form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 40px 48px 32px 48px;
        margin: 0 auto;
        max-width: 900px;
        width: 100%;
        min-width: 320px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .off-form-title {
        font-size: 2.2rem;
        font-weight: bold;
        color: #1a237e;
        margin-bottom: 28px;
        text-align: center;
        letter-spacing: 1px;
    }
    .form-group label {
        font-weight: 500;
        color: #333;
        margin-bottom: 6px;
    }
    .btn-off {
        background: #1565c0;
        border: none;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 8px;
        padding: 12px 0;
        width: 100%;
        transition: background 0.2s;
        margin-top: 16px;
    }
    .btn-off:hover {
        background: #0d47a1;
        color: #fff;
    }
    @media (max-width: 991px) {
        .off-form-card {
            padding: 30px 15px 24px 15px;
        }
    }
</style>

<div class="main-content">
    <div class="off-form-container">
        <div class="off-form-card">
            <div class="off-form-title">
                <i class="fa fa-calendar-times-o" style="margin-right:8px;color:#3949ab"></i>
                Báo ngày nghỉ
            </div>
            <?php if (isset($msg)) echo '<div class="mb-3 text-center">' . $msg . '</div>'; ?>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Thời gian bắt đầu</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Thời gian kết thúc</label>
                        <input type="datetime-local" name="end_time" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Lý do</label>
                    <textarea name="reason" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-off">Xác nhận ngày nghỉ</button>
            </form>
            <hr class="my-4">
<h5 class="text-center mb-3">Lịch sử ngày nghỉ của bạn</h5>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Lý do</th>
                <th>Ngày đăng ký</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $off_sql = "SELECT * FROM time_offs WHERE doctor_id = ? ORDER BY start_time DESC";
        $stmt_off = $conn->prepare($off_sql);
        $stmt_off->bind_param("i", $doctor_id);
        $stmt_off->execute();
        $result_off = $stmt_off->get_result();
        $i = 1;
        if ($result_off->num_rows > 0) {
            while($off = $result_off->fetch_assoc()) {
                echo "<tr>
                    <td>" . $i++ . "</td>
                    <td>" . date("d/m/Y H:i", strtotime($off['start_time'])) . "</td>
                    <td>" . date("d/m/Y H:i", strtotime($off['end_time'])) . "</td>
                    <td>" . htmlspecialchars($off['reason']) . "</td>
                    <td>" . date("d/m/Y H:i", strtotime($off['created_at'] ?? $off['start_time'])) . "</td>
                </tr>";
            }
        } else {
            echo '<tr><td colspan="5" class="text-center">Chưa có ngày off nào.</td></tr>';
        }
        $stmt_off->close();
        ?>
        </tbody>
    </table>
</div>

        </div>
    </div>
</div>


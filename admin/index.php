<?php

require 'includes/check_auth.php';
require 'includes/header.php';
require 'includes/sidebar.php';
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <div class="alert alert-success">
        Chào mừng <strong><?php echo $_SESSION['user_name']; ?></strong> đã trở lại!
    </div>

    <?php
    require_once '../includes/db.php';

    $doctors = mysqli_query($conn, "
        SELECT d.id, u.name, u.email, u.phone, d.specialty
        FROM doctors d
        JOIN users u ON d.user_id = u.id
    ");

    $patients = mysqli_query($conn, "
        SELECT id, name, email, phone
        FROM users
        WHERE role = 'patient'
    ");

    $services = mysqli_query($conn, "
        SELECT id, name, price
        FROM services
    ");

    $appointments = mysqli_query($conn, "
        SELECT a.id, u1.name AS patient_name, u2.name AS doctor_name, s.name AS service_name, a.appointment_time, a.status
        FROM appointments a
        LEFT JOIN users u1 ON a.patient_id = u1.id
        LEFT JOIN doctors d ON a.doctor_id = d.id
        LEFT JOIN users u2 ON d.user_id = u2.id
        LEFT JOIN services s ON a.service_id = s.id
        ORDER BY a.appointment_time DESC
        LIMIT 10
    ");

    $doctor_offs = mysqli_query($conn, "
    SELECT t.id, d.id as doctor_id, u.name as doctor_name, u.email as doctor_email, t.start_time, t.end_time, t.reason
    FROM time_offs t
    JOIN doctors d ON t.doctor_id = d.id
    JOIN users u ON d.user_id = u.id
    ORDER BY t.start_time DESC
");

    ?>
    <h2>Danh sách Bác sĩ</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th><th>Tên</th><th>Email</th><th>Điện thoại</th><th>Chuyên khoa</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($doctors)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['specialty'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Danh sách Bệnh nhân</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th><th>Tên</th><th>Email</th><th>Điện thoại</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($patients)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['phone'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Danh sách Dịch vụ</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th><th>Tên dịch vụ</th><th>Giá (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($services)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= number_format($row['price'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Danh sách Lịch hẹn gần đây</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th><th>Bệnh nhân</th><th>Bác sĩ</th><th>Dịch vụ</th><th>Thời gian</th><th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['patient_name'] ?></td>
                <td><?= $row['doctor_name'] ?></td>
                <td><?= $row['service_name'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['appointment_time'])) ?></td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <h2>Danh sách ngày nghỉ của Bác sĩ</h2>
    <?php
    if (mysqli_num_rows($doctor_offs) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Bác sĩ</th>
                    <th>Email</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Lý do</th>
                    <th>Ngày đăng ký</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($doctor_offs)): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_email']) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($row['start_time'])) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($row['end_time'])) ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($row['start_time'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info">Hiện chưa có bác sĩ nào đăng ký ngày nghỉ.</div>
    <?php endif; ?>


</main>

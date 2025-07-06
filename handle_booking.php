<?php
session_start();
require 'includes/db.php';
require 'includes/check_patient_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$service_id = $_POST['service_id'] ?? null;
$doctor_id = $_POST['doctor_id'] ?? null;
$appointment_date = $_POST['appointment_date'] ?? null;
$appointment_time = $_POST['appointment_time'] ?? null;
$notes = $_POST['notes'] ?? '';

if (empty($service_id) || empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
    $_SESSION['error_message'] = "Vui lòng điền đầy đủ thông tin bắt buộc.";
    header("Location: booking.php");
    exit();
}

try {
    $appointment_dt = new DateTime($appointment_date . ' ' . $appointment_time);
    if ($appointment_dt < new DateTime()) {
        $_SESSION['error_message'] = "Không thể đặt lịch hẹn trong quá khứ. Vui lòng chọn lại.";
        header("Location: booking.php");
        exit();
    }
    $appointment_sql_format = $appointment_dt->format('Y-m-d H:i:s');
} catch (Exception $e) {
    $_SESSION['error_message'] = "Định dạng ngày hoặc giờ không hợp lệ.";
    header("Location: booking.php");
    exit();
}

$conn->begin_transaction();

try {

    $sql_check = "SELECT id FROM appointments WHERE doctor_id = ? AND appointment_time = ? AND status != 'cancelled' FOR UPDATE";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $doctor_id, $appointment_sql_format);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        throw new Exception("Rất tiếc, khung giờ này vừa có người khác đặt. Vui lòng chọn lại.", 1062);
    }
    $stmt_check->close();

    $sql_insert = "INSERT INTO appointments (patient_id, doctor_id, service_id, appointment_time, notes, status) VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iiiss", $patient_id, $doctor_id, $service_id, $appointment_sql_format, $notes);
    $stmt_insert->execute();
    $stmt_insert->close();
    
    $conn->commit();

    $_SESSION['success_message'] = "Bạn đã đặt lịch hẹn thành công! Chúng tôi sẽ sớm liên hệ để xác nhận.";
    header("Location: index.php"); 
    exit();

} catch (Exception $e) {
    $conn->rollback();
    if ($e->getCode() == 1062) {
        $_SESSION['error_message'] = "Rất tiếc, khung giờ này vừa có người khác đặt. Vui lòng chọn lại.";
    } else {
        // Các lỗi khác
        $_SESSION['error_message'] = "Đã xảy ra lỗi không mong muốn. Vui lòng thử lại. Lỗi: " . $e->getMessage();
    }
    
    header("Location: booking.php");
    exit();
}

$conn->close();
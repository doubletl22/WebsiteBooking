<?php
header('Content-Type: application/json');
require 'includes/db.php';

$doctorId = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

if (!$doctorId || !$serviceId) {
    echo json_encode(['error' => 'Vui lòng chọn bác sĩ và dịch vụ.']);
    exit;
}

$stmt_service = $conn->prepare("SELECT duration_minutes FROM services WHERE id = ?");
$stmt_service->bind_param("i", $serviceId);
$stmt_service->execute();
$result_service = $stmt_service->get_result();
if ($result_service->num_rows === 0) {
    echo json_encode(['error' => 'Không tìm thấy dịch vụ.']);
    exit;
}
$service_duration = $result_service->fetch_assoc()['duration_minutes'];
$stmt_service->close();

$schedules = $conn->query("SELECT day_of_week, start_time, end_time FROM schedules WHERE doctor_id = $doctorId")->fetch_all(MYSQLI_ASSOC);
$time_offs = $conn->query("SELECT start_time, end_time FROM time_offs WHERE doctor_id = $doctorId AND end_time > NOW()")->fetch_all(MYSQLI_ASSOC);
$appointments = $conn->query("SELECT appointment_time FROM appointments WHERE doctor_id = $doctorId AND status != 'cancelled' AND appointment_time > NOW()")->fetch_all(MYSQLI_ASSOC);

$booked_slots = [];
foreach ($appointments as $app) {
    $booked_slots[] = new DateTime($app['appointment_time']);
}

$available_slots = [];
$interval = new DateInterval('P1D');
$period = new DatePeriod(new DateTime(), $interval, 30); 

foreach ($period as $day) {
    $day_of_week = $day->format('N'); 
    foreach ($schedules as $schedule) {
        if ($schedule['day_of_week'] == $day_of_week) {
            $start = new DateTime($day->format('Y-m-d') . ' ' . $schedule['start_time']);
            $end = new DateTime($day->format('Y-m-d') . ' ' . $schedule['end_time']);
            
            $slot_interval = new DateInterval('PT' . $service_duration . 'M');
            $time_slots_for_day = new DatePeriod($start, $slot_interval, $end);

            foreach ($time_slots_for_day as $slot) {
                $slot_end = (clone $slot)->add(new DateInterval('PT' . $service_duration . 'M'));
                
                if ($slot_end > $end) continue;

                if ($slot < new DateTime()) continue;

                $is_available = true;

                foreach ($time_offs as $off) {
                    $off_start = new DateTime($off['start_time']);
                    $off_end = new DateTime($off['end_time']);
                    if ($slot < $off_end && $slot_end > $off_start) {
                        $is_available = false;
                        break;
                    }
                }
                if (!$is_available) continue;

                foreach ($booked_slots as $booked) {
                    $booked_end = (clone $booked)->add(new DateInterval('PT' . $service_duration . 'M'));
                    if ($slot < $booked_end && $slot_end > $booked) {
                         $is_available = false;
                         break;
                    }
                }
                if (!$is_available) continue;

                $date_key = $slot->format('Y-m-d');
                $time_key = $slot->format('H:i');
                if (!isset($available_slots[$date_key])) {
                    $available_slots[$date_key] = [];
                }
                $available_slots[$date_key][] = $time_key;
            }
        }
    }
}

echo json_encode($available_slots);
$conn->close();
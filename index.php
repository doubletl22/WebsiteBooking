<?php 

require 'includes/header_public.php';
require 'includes/db.php';

$services_result = $conn->query("SELECT name, description, price FROM services LIMIT 6");
?>

<title>Trang chủ - Phòng khám Nha Khoa Hạnh Phúc</title>

<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container text-center hero-content">
        <h1 class="text-white display-4 font-weight-bold">Chào mừng đến với Nha Khoa Hạnh Phúc</h1>
        <p class="text-white lead my-3">Nơi mang lại nụ cười rạng rỡ và sức khỏe răng miệng toàn diện cho bạn.</p>
        <a class="btn btn-primary btn-lg mt-3" href="booking.php" role="button">Đặt lịch hẹn ngay</a>
    </div>
</section>

<div class="container">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" role="alert">
            <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>
    <section id="services" class="my-5">
        <h2 class="tieu-de-khu-vuc">về chúng tôi</h2>
        <p class="tieu-de-khu-vuc">
  Nha Khoa Hạnh Phúc tự hào là địa chỉ uy tín với hơn 10 năm kinh nghiệm 
  trong lĩnh vực nha khoa. Chúng tôi cam kết mang đến dịch vụ chăm sóc răng miệng chất
   lượng cao với đội ngũ bác sĩ giàu kinh nghiệm và trang thiết bị hiện đại.
</p>


        <h2 class="text-center mb-4">Dịch vụ của chúng tôi</h2>
        <div class="row">
            <?php if ($services_result && $services_result->num_rows > 0): ?>
                <?php while($service = $services_result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                                <p class="card-text"><strong>Giá: <?php echo number_format($service['price']); ?> VND</strong></p>
                            </div>
                            <div class="card-footer">
                                <a href="#" class="btn btn-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Chưa có dịch vụ nào để hiển thị.</p>
            <?php endif; ?>
        </div>
    </section>
    <section id="doctors-section" class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8 mx-auto text-center">
                    <h2 class="font-weight-bold">Đội ngũ Bác sĩ Tận tâm</h2>
                    <p class="lead text-muted">Những chuyên gia hàng đầu, luôn sẵn sàng chăm sóc nụ cười của bạn.</p>
                </div>
            </div>

            <?php
            $sql_doctors = "SELECT u.name, d.specialty, d.avatar 
                            FROM doctors d 
                            JOIN users u ON d.user_id = u.id 
                            WHERE u.role = 'doctor'
                            LIMIT 4";
            $doctors_result = $conn->query($sql_doctors);
            ?>

            <div class="row">
                <?php if ($doctors_result && $doctors_result->num_rows > 0): ?>
                    <?php while($doctor = $doctors_result->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="doctor-card text-center">
                                <div class="avatar-container mb-3">
                                    <img src="<?php echo htmlspecialchars($doctor['avatar']); ?>" 
                                        alt="Bác sĩ <?php echo htmlspecialchars($doctor['name']); ?>" 
                                        class="doctor-avatar">
                                </div>
                                <h5 class="doctor-name mb-1"><?php echo htmlspecialchars($doctor['name']); ?></h5>
                                <p class="doctor-specialty text-muted"><?php echo htmlspecialchars($doctor['specialty']); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center">Chưa có thông tin bác sĩ để hiển thị.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php 
$conn->close();
require 'includes/footer_public.php'; 
?>

//test push ay
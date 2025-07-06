<?php 
require 'includes/header_public.php';
require 'includes/db.php';

$all_services_result = $conn->query("SELECT id, name, description, price FROM services ORDER BY name");
$all_services = [];
while($row = $all_services_result->fetch_assoc()) {
    $all_services[] = $row;
}

$doctors_result = $conn->query("SELECT d.id, u.name FROM doctors d JOIN users u ON d.user_id = u.id WHERE u.role = 'doctor' ORDER BY u.name");

$service_categories = [
    'NHA KHOA TỔNG QUÁT' => [
        'Khám tư vấn, kiểm tra tổng quát',
        'Chụp phim X-quang răng',
        'Cạo vôi răng + Đánh bóng 2 hàm',
        'Trám răng thẩm mỹ (sâu răng)',
        'Trám răng mòn cổ'
    ],
    'ĐIỀU TRỊ BỆNH LÝ' => [
        'Điều trị tủy răng vĩnh viễn (1 ống tủy)',
        'Điều trị tủy răng vĩnh viễn (2 ống tủy)',
        'Điều trị tủy răng vĩnh viễn (3+ ống tủy)',
        'Điều trị viêm nướu/nha chu'
    ],
    'NHỔ RĂNG' => [
        'Nhổ răng thường',
        'Nhổ răng khôn hàm trên',
        'Nhổ răng khôn hàm dưới'
    ],
    'PHỤC HÌNH RĂNG SỨ' => [
        'Răng sứ kim loại Cr-Co (Germany)',
        'Răng toàn sứ Zirconia (Germany)',
        'Răng sứ Lava Plus - 3M ESPE (USA)',
        'Mặt dán sứ Veneer Emax (Switzerland)',
        'Cầu răng sứ (3 đơn vị)'
    ],
    'CẤY GHÉP IMPLANT' => [
        'Implant Dentium (Korea)',
        'Implant Straumann (Switzerland)'
    ],
    'CHỈNH NHA - NIỀNG RĂNG' => [
        'Niềng răng mắc cài kim loại (Standard)',
        'Niềng răng mắc cài sứ',
        'Niềng răng trong suốt Invisalign'
    ],
    'NHA KHOA TRẺ EM' => [
        'Khám và tư vấn tổng quát', // Dịch vụ này có thể thuộc cả 2 nhóm
        'Nhổ răng sữa',
        'Trám răng sữa',
        'Chữa tủy răng sữa',
        'Bôi Flour chống sâu răng',
        'Trám bít hố rãnh phòng ngừa sâu răng'
    ]
];

$sql_doctors = "SELECT u.name, d.specialty, d.bio, d.avatar 
                FROM doctors d 
                JOIN users u ON d.user_id = u.id 
                WHERE u.role = 'doctor'
                LIMIT 3";
$featured_doctors_result = $conn->query($sql_doctors);
?>

<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Nụ cười khỏe mạnh, cuộc sống hạnh phúc</h1>
        <p>Nha khoa Hạnh Phúc - Đồng hành cùng bạn trên hành trình tìm lại nụ cười tự tin và rạng rỡ.</p>
        <a class="custom-btn" href="booking.php" role="button">Đặt lịch ngay</a>
    </div>
</section>

<div class="custom-container">
    <div class="custom-container">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message page-notification">
            <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']); 
            ?>
        </div>
    <?php endif; ?>
    <section id="featured-services" class="page-section">
        <h2 class="section-title">Dịch vụ nổi bật</h2>
        <div class="custom-row">
            <div class="custom-col-3">
                <div class="feature-item">
                    <div class="feature-icon">🦷</div>
                    <h6>Chăm sóc chuyên nghiệp</h6>
                </div>
            </div>
            <div class="custom-col-3">
                <div class="feature-item">
                    <div class="feature-icon">💎</div>
                    <h6>Vật liệu chất lượng</h6>
                </div>
            </div>
            <div class="custom-col-3">
                <div class="feature-item">
                    <div class="feature-icon">😊</div>
                    <h6>Hỗ trợ tận tình</h6>
                </div>
            </div>
            <div class="custom-col-3">
                <div class="feature-item">
                    <div class="feature-icon">💲</div>
                    <h6>Giá cả hợp lý</h6>
                </div>
            </div>
        </div>
    </section>

    <section id="price-list" class="page-section">
        <h2 class="section-title">Bảng giá dịch vụ nha khoa</h2>
        <div class="price-accordion">
            <?php foreach ($service_categories as $category => $services_in_category): ?>
                <details open>
                    <summary><?php echo $category; ?></summary>
                    <div class="price-table-container">
                        <table class="price-table">
                            <thead>
                                <tr>
                                    <th>Dịch vụ</th>
                                    <th class="price">Chi phí (VND)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_services as $service): ?>
                                    <?php if (in_array($service['name'], $services_in_category)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($service['name']); ?></td>
                                            <td class="price"><?php echo number_format($service['price'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </section>
    
    <section id="featured-doctors" class="page-section" style="background-color: #fdf6f5;">
        <div class="custom-container">
            <h2 class="section-title">Đội ngũ bác sĩ nổi bật</h2>
            <div class="custom-row" style="justify-content: center;">
                <?php if ($featured_doctors_result && $featured_doctors_result->num_rows > 0): ?>
                    <?php while($doctor = $featured_doctors_result->fetch_assoc()): ?>
                        <div class="custom-col-4">
                            <div class="doctor-card">
                                <?php
                                    $avatar_url = !empty($doctor['avatar']) ? $doctor['avatar'] : 'images/default-avatar.jpg';
                                ?>
                                <img src="<?php echo BASE_URL . htmlspecialchars($avatar_url); ?>" 
                                    alt="Bác sĩ <?php echo htmlspecialchars($doctor['name']); ?>" 
                                    class="doctor-card-img">

                                <h5 class="doctor-name"><?php echo htmlspecialchars($doctor['name']); ?></h5>
                                <p class="doctor-specialty"><?php echo htmlspecialchars($doctor['specialty']); ?></p>
                                
                                <?php
                                    // Tách bio thành các dòng để làm danh sách
                                    $bio_lines = array_filter(explode("\n", trim($doctor['bio'])));
                                ?>
                                
                                <p class="doctor-bio-description">
                                    <?php 
                                        // Hiển thị dòng đầu tiên như mô tả ngắn
                                        if (!empty($bio_lines)) echo htmlspecialchars(array_shift($bio_lines));
                                    ?>
                                </p>
                                
                                <ul class="doctor-qualifications">
                                    <?php foreach ($bio_lines as $line): ?>
                                        <li><?php echo htmlspecialchars(trim($line)); ?></li>
                                    <?php endforeach; ?>
                                </ul>
            
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align:center;">Chưa có thông tin bác sĩ nổi bật để hiển thị.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="booking-process" class="page-section">
        <h2 class="section-title">Quy trình đặt lịch</h2>
        <div class="custom-row">
            <div class="custom-col-3">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h6>Chọn thông tin</h6>
                    <p>Điền thông tin cá nhân và chọn dịch vụ mong muốn.</p>
                </div>
            </div>
            <div class="custom-col-3">
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h6>Chờ xác nhận</h6>
                    <p>Nhân viên của chúng tôi sẽ gọi điện xác nhận lịch hẹn.</p>
                </div>
            </div>
            <div class="custom-col-3">
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h6>Đến phòng khám</h6>
                    <p>Tới phòng khám đúng giờ đã hẹn để được phục vụ.</p>
                </div>
            </div>
            <div class="custom-col-3">
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h6>Thanh toán</h6>
                    <p>Thanh toán chi phí và nhận tư vấn sau điều trị.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<section id="booking-form-section">
    <div class="custom-container">
        <a class="custom-btn" href="booking.php" role="button">Đặt lịch ngay</a>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageSources = [
        '<?php echo BASE_URL; ?>images/anhnhakhoa1.jpg',
        '<?php echo BASE_URL; ?>images/anhnhakhoa2.jpg',
        '<?php echo BASE_URL; ?>images/anhnhakhoa3.jpg'
    ];

    const heroSection = document.querySelector('.hero-section');
    let currentImageIndex = 0;

    imageSources.forEach(src => {
        const img = new Image();
        img.src = src;
    });

    setInterval(() => {
        currentImageIndex = (currentImageIndex + 1) % imageSources.length;
        heroSection.style.backgroundImage = `url('${imageSources[currentImageIndex]}')`;

    }, 5000); 
});
</script>

<?php 
$conn->close();
require 'includes/footer_public.php'; 
?>
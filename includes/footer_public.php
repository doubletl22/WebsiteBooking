<?php
?>
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-heading">Nha Khoa Hạnh Phúc</h5>
                <p class="footer-text">Với đội ngũ y bác sĩ chuyên nghiệp và trang thiết bị hiện đại, chúng tôi cam kết mang đến cho bạn nụ cười tự tin và sức khỏe răng miệng toàn diện.</p>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-heading">Dịch vụ</h5>
                <ul class="list-unstyled footer-links">
                    <?php
                    include 'db.php'; 
                    if ($conn) {
                        $footer_services_result = $conn->query("SELECT name FROM services LIMIT 5");
                        if ($footer_services_result && $footer_services_result->num_rows > 0) {
                            while($service = $footer_services_result->fetch_assoc()) {
                                echo '<li><a href="#">' . htmlspecialchars($service['name']) . '</a></li>';
                            }
                        }
                        $conn->close(); 
                    }
                    ?>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-heading">Liên hệ</h5>
                <ul class="list-unstyled footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</li>
                    <li><i class="fas fa-phone"></i> (028) 3812 3456</li>
                    <li><i class="fas fa-envelope"></i> contact@nhakhoahanhphuc.com</li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                 <h5 class="footer-heading">Giờ làm việc</h5>
                 <ul class="list-unstyled footer-contact">
                    <li>Thứ 2 - Thứ 7: 08:00 - 20:00</li>
                    <li>Chủ nhật: 08:00 - 17:00</li>
                 </ul>
            </div>

<<<<<<< HEAD
    </div> <footer class="site-footer">
    <div class="container">
        <div class="row">

            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="footer-logo">Nha Khoa Hạnh Phúc</h5>
                <p>Nơi mang lại nụ cười rạng rỡ và sức khỏe răng miệng toàn diện cho bạn.</p>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Liên kết nhanh</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Trang chủ</a></li>
                    <li><a href="#">Dịch vụ</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php#doctors-section">Đội ngũ Bác sĩ</a></li>
                    <li><a href="<?php echo BASE_URL; ?>doctor_login.php">Đăng nhập Bác sĩ</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Dịch vụ</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Cạo vôi răng</a></li>
                    <li><a href="#">Trám răng thẩm mỹ</a></li>
                    <li><a href="#">Tẩy trắng răng</a></li>
                    <li><a href="#">Nhổ răng khôn</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Liên hệ</h5>
                <ul class="list-unstyled contact-info">
                    <li><strong>Địa chỉ:</strong> 123 Nguyễn Văn Linh, Q.7, TP.HCM</li>
                    <li><strong>SĐT:</strong> 028 1234 5678</li>
                    <li><strong>Email:</strong> info@nhakhoahanhphuc.com</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Bản quyền: <a href="index.php">NhaKhoaHanhPhuc.com</a></p>
        </div>
    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

=======
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container text-center">
            <p class="mb-0">Bản quyền &copy; <?php echo date('Y'); ?> Nha Khoa Hạnh Phúc. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
>>>>>>> 20ec5104143041ab58fd2becc54d8d41cd895886

</body>
</html>
<?php
require 'includes/header_public.php';
require 'includes/db.php';
require 'includes/check_patient_auth.php';

$services_result = $conn->query("SELECT id, name FROM services ORDER BY name");
$doctors_result = $conn->query("SELECT d.id, u.name FROM doctors d JOIN users u ON d.user_id = u.id WHERE u.role = 'doctor' ORDER BY u.name");
?>

<title>Đặt Lịch Hẹn - Nha Khoa Hạnh Phúc</title>

<div class="page-section">
    <div class="login-container">
        <div class="login-card">
            <h2 class="section-title">Tạo Lịch Hẹn Mới</h2>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>

            <form action="handle_booking.php" method="POST" id="booking-form">
                <div class="custom-form-group">
                    <label for="service_id">1. Chọn dịch vụ (*)</label>
                    <select class="custom-form-control" id="service_id" name="service_id" required>
                        <option value="">-- Vui lòng chọn --</option>
                        <?php while($service = $services_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($service['id']); ?>"><?php echo htmlspecialchars($service['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="custom-form-group">
                    <label for="doctor_id">2. Chọn bác sĩ (*)</label>
                    <select class="custom-form-control" id="doctor_id" name="doctor_id" required>
                        <option value="">-- Vui lòng chọn --</option>
                        <?php while($doctor = $doctors_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($doctor['id']); ?>"><?php echo htmlspecialchars($doctor['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <hr class="form-divider">
                
                <div class="custom-form-group">
                     <label>3. Chọn khung giờ có sẵn</label>
                    <div id="time-slots-container">
                        <p class="form-text-muted">Vui lòng chọn dịch vụ và bác sĩ ở trên.</p>
                    </div>
                </div>

                <input type="hidden" id="appointment_date" name="appointment_date">
                <input type="hidden" id="appointment_time" name="appointment_time">

                <div class="custom-form-group">
                    <label for="notes">Ghi chú (nếu có)</label>
                    <textarea class="custom-form-control" id="notes" name="notes" rows="3"></textarea>
                </div>

                <button type="submit" id="submit-btn" class="custom-btn btn-block" disabled>Chọn khung giờ để đặt lịch</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const doctorSelect = document.getElementById('doctor_id');
    const slotsContainer = document.getElementById('time-slots-container');
    const hiddenDate = document.getElementById('appointment_date');
    const hiddenTime = document.getElementById('appointment_time');
    const submitBtn = document.getElementById('submit-btn');
    const bookingForm = document.getElementById('booking-form');

    function fetchSlots() {
        const serviceId = serviceSelect.value;
        const doctorId = doctorSelect.value;

        slotsContainer.innerHTML = '<p class="form-text-muted">Vui lòng chọn dịch vụ và bác sĩ ở trên.</p>';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Chọn khung giờ để đặt lịch';
        hiddenDate.value = '';
        hiddenTime.value = '';

        if (!serviceId || !doctorId) return;

        slotsContainer.innerHTML = '<p class="form-text-muted">Đang tải lịch hẹn, vui lòng chờ...</p>';

        fetch(`get_available_slots.php?service_id=${serviceId}&doctor_id=${doctorId}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                slotsContainer.innerHTML = ''; 
                if (data.error) {
                    slotsContainer.innerHTML = `<p class="error-message">${data.error}</p>`;
                    return;
                }
                
                const dates = Object.keys(data);
                if (dates.length === 0) {
                    slotsContainer.innerHTML = '<p class="form-text-muted">Bác sĩ không có lịch trống trong 30 ngày tới. Vui lòng chọn bác sĩ khác.</p>';
                    return;
                }

                const weekdays = ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];

                dates.forEach(date => {
                    const dateObj = new Date(date + 'T00:00:00');
                    const dayName = weekdays[dateObj.getDay()];
                    const formattedDate = `${dayName}, Ngày ${dateObj.getDate()}/${dateObj.getMonth() + 1}`;
                    
                    const accordionItem = document.createElement('div');
                    accordionItem.className = 'slot-day-accordion';

                    accordionItem.innerHTML = `
                        <button type="button" class="slot-day-header">
                            <span>${formattedDate}</span>
                            <span class="slot-day-arrow">▼</span>
                        </button>
                        <div class="slot-day-content">
                            <div class="slot-day-content-inner"></div>
                        </div>
                    `;
                    
                    const contentInner = accordionItem.querySelector('.slot-day-content-inner');

                    const periods = { Sáng: [], Chiều: [], Tối: [] };
                    data[date].forEach(time => {
                        const hour = parseInt(time.split(':')[0], 10);
                        if (hour < 12) periods.Sáng.push(time);
                        else if (hour < 18) periods.Chiều.push(time);
                        else periods.Tối.push(time);
                    });

                    for (const periodName in periods) {
                        if (periods[periodName].length > 0) {
                            const periodGroup = document.createElement('div');
                            periodGroup.className = 'time-period-group';
                            periodGroup.innerHTML = `<div class="time-period-title">${periodName}</div>`;

                            const timeGrid = document.createElement('div');
                            timeGrid.className = 'slot-time-grid';

                            periods[periodName].forEach(time => {
                                const timeBtn = document.createElement('button');
                                timeBtn.type = 'button';
                                timeBtn.className = 'slot-btn';
                                timeBtn.textContent = time;
                                timeBtn.dataset.date = date;
                                timeBtn.dataset.time = time;
                                timeGrid.appendChild(timeBtn);
                            });
                            
                            periodGroup.appendChild(timeGrid);
                            contentInner.appendChild(periodGroup);
                        }
                    }

                    slotsContainer.appendChild(accordionItem);
                });
            })
            .catch(error => {
                slotsContainer.innerHTML = '<p class="error-message">Có lỗi xảy ra khi tải lịch hẹn.</p>';
                console.error('Fetch error:', error);
            });
    }

    slotsContainer.addEventListener('click', function(e) {
        const header = e.target.closest('.slot-day-header');
        if (header) {
            const content = header.nextElementSibling;
            
            document.querySelectorAll('.slot-day-content').forEach(c => {
                if (c !== content) {
                    c.style.maxHeight = null;
                    c.previousElementSibling.classList.remove('active');
                }
            });
            
            header.classList.toggle('active');
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        }
        
        if (e.target.classList.contains('slot-btn')) {
            document.querySelectorAll('.slot-btn.active').forEach(btn => btn.classList.remove('active'));
            
            const selectedBtn = e.target;
            selectedBtn.classList.add('active');

            hiddenDate.value = selectedBtn.dataset.date;
            hiddenTime.value = selectedBtn.dataset.time;

            submitBtn.disabled = false;
            submitBtn.textContent = 'Xác nhận Đặt lịch';
        }
    });

    serviceSelect.addEventListener('change', fetchSlots);
    doctorSelect.addEventListener('change', fetchSlots);

    bookingForm.addEventListener('submit', function(e) {
        if (!hiddenDate.value || !hiddenTime.value) {
            e.preventDefault();
            alert('Vui lòng chọn một khung giờ hẹn cụ thể.');
        }
    });
});
</script>

<?php 
$conn->close();
require 'includes/footer_public.php'; 
?>
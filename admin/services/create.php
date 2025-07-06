<?php
require '../includes/check_auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../../includes/db.php';

    $name = $_POST['name'];
    $description = $_POST['description'];
    $duration_minutes = $_POST['duration_minutes'];
    $price = $_POST['price'];

    if (!empty($name) && is_numeric($duration_minutes) && is_numeric($price)) {
        $sql = "INSERT INTO services (name, description, duration_minutes, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $name, $description, $duration_minutes, $price);

        if ($stmt->execute()) {
            header("Location: services.php?success=Thêm dịch vụ thành công!");
            exit();
        } else {
            $error = "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Vui lòng điền đầy đủ và đúng định dạng các trường bắt buộc.";
    }
    $conn->close();
}

require '../includes/header.php';
require '../includes/sidebar.php';
?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Thêm Dịch vụ mới</h1>
    </div>

    <?php
    if (isset($error)) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
    }
    ?>

    <form action="create.php" method="POST">
        <div class="form-group">
            <label for="name">Tên Dịch vụ (*)</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="duration_minutes">Thời lượng (phút) (*)</label>
            <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" required>
        </div>
        <div class="form-group">
            <label for="price">Giá (VND) (*)</label>
            <input type="number" class="form-control" id="price" name="price" step="1000" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="services.php" class="btn btn-secondary">Hủy</a>
    </form>
</main>

<?php
require '../includes/footer.php';
?>
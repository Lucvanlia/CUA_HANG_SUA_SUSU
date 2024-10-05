<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $customer_id = $_POST['dob'];
    $facebook_id = $_POST['facebook_id'];

    // Kiểm tra xem số điện thoại đã tồn tại chưa
    $sql = "SELECT * FROM khachhang WHERE sdt_kh = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Số điện thoại đã tồn tại
        echo "Số điện thoại đã tồn tại!";
    } else {
        // Xử lý lưu thông tin và file
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileType = $_FILES['photo']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Tạo chuỗi ngẫu nhiên 150 ký tự
            function generateRandomString($length = 150) {
                return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
            }

            // Tạo tên file mới
            $newFileName = $customer_id . '_' . $facebook_id . '_' . generateRandomString(150) . '.' . $fileExtension;

            // Thư mục lưu ảnh
            $uploadFileDir = './uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);  // Tạo thư mục nếu chưa tồn tại
            }

            $dest_path = $uploadFileDir . $newFileName;

            // Di chuyển file vào thư mục đích và lưu thông tin vào cơ sở dữ liệu
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $sql = "INSERT INTO users (name, dob, phone, customer_id, facebook_id, photo) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $name, $dob, $phone, $customer_id, $facebook_id, $newFileName);
                $stmt->execute();

                echo "Đăng ký thành công!";
            } else {
                echo "Có lỗi khi tải ảnh lên.";
            }
        } else {
            echo "Có lỗi khi tải file.";
        }
    }
} else {
    echo "Invalid request.";
}

$conn->close();

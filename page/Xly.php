<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-kh'])) {
    // Lấy thông tin từ form
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = strtotime($_POST['dob']); // Đổi sang timestamp
    $id_session = $_POST['id_session'];

    // Xử lý ảnh nếu có tải lên
    if (isset($_FILES['uploadImage']) && $_FILES['uploadImage']['error'] == 0) {
        $imageName = basename($_FILES['uploadImage']['name']);
        $uploadDir = "../uploads/";
        $uploadFile = $uploadDir . $imageName;

        // Di chuyển ảnh đã tải lên vào thư mục lưu trữ
        if (move_uploaded_file($_FILES['uploadImage']['tmp_name'], $uploadFile)) {
            $profile_pic = $uploadFile;
        } else {
            echo "Lỗi khi tải lên ảnh.";
            exit;
        }
    } else {
        $profile_pic = null; // Hoặc giữ ảnh hiện tại
    }

    // Cập nhật thông tin người dùng vào database
    $query = "UPDATE khachhang SET 
              Ten_KH = '$fullname', 
              email_kh = '$email', 
              sdt_kh = '$phone', 
              namsinh_kh = '$dob', 
              profile_pic = IFNULL('$profile_pic', profile_pic),
              updated_at = " . time() . "
              WHERE id_kh = '$id_session'";

    if (mysqli_query($link, $query)) {
        echo "Cập nhật thành công!";
    } else {
        echo "Lỗi: " . mysqli_error($link);
    }
}
    mysqli_close($link);
?>
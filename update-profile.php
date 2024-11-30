<?php 
// Kết nối cơ sở dữ liệu
session_start();
include"admin_test/ketnoi/conndb.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION["id_user"])) {
    echo "Bạn chưa đăng nhập.";
    exit;
}
if (isset($_POST['newPassword'])) {
    // Nhận dữ liệu từ form
    $newPassword = $_POST['newPassword'];

    // Nếu mật khẩu mới trống, trả về lỗi
    if (empty($newPassword)) {
        echo "empty";
        exit;
    }

    // Mã hóa mật khẩu mới
    $newPassword = hash('sha256', $_POST['newPassword']); // Mã hóa mật khẩu

    // Giả sử người dùng đã đăng nhập và user_id được lưu trong session
    $userId = $_SESSION['id_user'];

    // Cập nhật mật khẩu mới vào database
    $sql = "UPDATE Khachhang SET Mk_kh = '$newPassword ' WHERE id_kh = '$userId'";

    if ($link->query($sql) === TRUE) {
        echo "success";
        unset($_SESSION['otp']);
    } else {
        echo "Error: Không thể thực thi" . $link->error;
    }

    // Đóng kết nối
    exit();
}
if (isset($_POST['status_profile']) ||isset($_FILES['Hinh_kh']))  {
    // Kiểm tra và xử lý ảnh đại diện
    if (isset($_FILES['Hinh_kh'])) {
        $file = $_FILES['Hinh_kh'];
        $targetDir = "admin_test/modul/uploads/Hinh_kh/"; // Đường dẫn tuyệt đối đến thư mục uploads
        $fileName = basename($file["name"]);
        $targetFilePath =  $fileName;

        // Di chuyển file vào thư mục
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            // Cập nhật đường dẫn ảnh vào cơ sở dữ liệu
            $sql = "UPDATE Khachhang SET Hinh_kh = '$targetFilePath' WHERE id_kh = ".$_SESSION['id_user'];
            if (mysqli_query($link, $sql)) {
                echo "success";
            } else {
                echo "false";
            }
        } else {
            echo "Có lỗi khi di chuyển file.";
        }
        exit;
    }

    // Kiểm tra và xử lý các thông tin khác
    if (isset($_POST["fullname"]) || isset($_POST["email"], $_POST["phone"],$_POST["dob"])) {
        $fullname = mysqli_real_escape_string($link, $_POST["fullname"]);
        $email = mysqli_real_escape_string($link, $_POST["email"]);
        $phone = mysqli_real_escape_string($link, $_POST["phone"]);
        $dob = mysqli_real_escape_string($link, $_POST["dob"]);
        $address = mysqli_real_escape_string($link, $_POST["address"]);

        $sql = "UPDATE Khachhang SET Ten_KH = '$fullname', Email_kh = '$email', SDT_kh = '$phone', NgaySinh_kh = '$dob' ,Dchi_kh='$address' WHERE id_kh = ".$_SESSION['id_user'];
        
        if (mysqli_query($link, $sql)) {
            echo "success";
        } else {
            echo "Lỗi khi cập nhật thông tin.";
        }
    } else {
        echo "Thiếu dữ liệu.";
    }
}

?>

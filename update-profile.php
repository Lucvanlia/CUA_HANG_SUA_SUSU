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
    $hashedPassword = md5($newPassword);

    // Giả sử người dùng đã đăng nhập và user_id được lưu trong session
    $userId = $_SESSION['id_user'];

    // Cập nhật mật khẩu mới vào database
    $sql = "UPDATE khachhang SET mk_kh='$hashedPassword' WHERE id_kh='$_SESSION[id_user]'";

    if ($link->query($sql) === TRUE) {
        echo "success";
        unset($_SESSION['otp']);
        header("location: https://banhangviet-tmi.net/doan_php/index.php?action=profile&query=profile");
    } else {
        echo "Error: " . $link->error;
    }

    // Đóng kết nối
    $link->close();
}
if (isset($_POST['status_profile']) ||isset($_FILES['profile_pic']))  {
    // Kiểm tra và xử lý ảnh đại diện
    if (isset($_FILES['profile_pic'])) {
        $file = $_FILES['profile_pic'];
        $targetDir = "admin_test/modul/uploads/"; // Đường dẫn tuyệt đối đến thư mục uploads
        $fileName = basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Di chuyển file vào thư mục
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            // Cập nhật đường dẫn ảnh vào cơ sở dữ liệu
            $sql = "UPDATE khachhang SET profile_pic = '$targetFilePath' WHERE id_kh = ".$_SESSION['id_user'];
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

        $sql = "UPDATE khachhang SET Ten_KH = '$fullname', email_kh = '$email', sdt_kh = '$phone', namsinh_kh = '$dob' ,DChi_kh='$address' WHERE id_kh = ".$_SESSION['id_user'];
        
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

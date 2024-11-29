<?php
session_start();
header('Content-Type: application/json; charset=utf-8'); // Thêm header JSON
include "admin_test/ketnoi/conndb.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'register') {
    $name = $_POST['register-name'];
    $email = filter_var($_POST['register-email'], FILTER_SANITIZE_EMAIL);
    $phone = $_POST['register-phone'];
    $dob = $_POST['register-dob'];
    $password = hash('sha256', $_POST['password']); // Mã hóa mật khẩu
    $hoatdong = 0;

    if ($link) {
        $checkEmailQuery = "SELECT * FROM khachhang WHERE Email_kh = ?";
        $stmt = $link->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $token = bin2hex(random_bytes(50)); // Tạo token

        if ($result->num_rows > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email đã được sử dụng'
            ]);
            exit;
        } else {
            $insertQuery = "INSERT INTO khachhang (Ten_kh, Email_kh, SDT_kh, Mk_kh, NgaySinh_kh, HoatDong, Token_kh) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $link->prepare($insertQuery);
            $stmt->bind_param("sssssss", $name, $email, $phone, $password, $dob, $hoatdong, $token);

            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Lỗi thực thi cơ sở dữ liệu'
                ]);
            }
            $stmt->close();
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Không thể kết nối cơ sở dữ liệu'
        ]);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login') {
    $email = $_POST['login-email'];
    // $password = $_POST['login-password'];
    $password = hash('sha256', $_POST['login-password']); // Mã hóa mật khẩu


    if ($link) {
        // Chuẩn bị câu lệnh SQL
        $sql = "SELECT Mk_kh FROM Khachhang WHERE Email_kh = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        // Kiểm tra xem email có tồn tại không
        if ($stmt->num_rows > 0) {
            // Lấy mật khẩu đã hash từ database
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            // echo 'Mật khẩu nhận '. $_POST['login-password'] .'</br>';
            // echo 'Mật khẩu  lấy  '.  $password.'</br>';
            // echo 'Mật khẩu  giải mã '. $hashed_password.'</br>';
            if ($password === $hashed_password) {
                // Mật khẩu khớp
                $sql = "SELECT id_kh FROM khachhang WHERE Email_kh = ?";
                $stmt = $link->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($user_id);
                $stmt->fetch();
                // $message = "Đăng nhập thành công!";
                $_SESSION['id_user'] = $user_id;
                $_SESSION['login_success'] = "Đăng nhập thành công! Chào mừng bạn trở lại.";
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Đăng nhập thành công'
                ]);
                exit();
                exit;
                // $message = "Đăng nhập thành công";
                // header("Location: http://banhangviet-tmi.net/doan_php/");
            }
            // Nếu mật khẩu không khớp, thử kiểm tra hash bằng sha256 (trường hợp mật khẩu cũ)
            else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Mật khẩu sai' . $password . '</br>' . $hashed_password
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tài khoản không tồn tại'
            ]);
            exit;
        }

        // Đóng statement
        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi cơ sở dữ liệu'
        ]);
        exit;
    }
}

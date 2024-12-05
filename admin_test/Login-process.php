<?php
session_start();
header('Content-Type: application/json; charset=utf-8'); // Thêm header JSON
include "ketnoi/conndb.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login') {
    $email = $_POST['login-email'];
    // $password = $_POST['login-password'];
    $password = hash('sha256', $_POST['login-password']); // Mã hóa mật khẩu


    if ($link) {
        // Chuẩn bị câu lệnh SQL
        $sql = "SELECT Mk_nv FROM NhanVien WHERE Email_nv = ?";
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
                $sql = "SELECT id_nv FROM NhanVien WHERE Email_nv = ?";
                $stmt = $link->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($user_id);
                $stmt->fetch();
                // $message = "Đăng nhập thành công!";
                $_SESSION['id_login'] = $user_id;
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

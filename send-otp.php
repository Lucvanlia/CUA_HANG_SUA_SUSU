<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Nếu dùng Composer

// Hàm tạo mã OTP ngẫu nhiên
function generateOTP($length = 6) {
    return rand(100000, 999999);
}

// Kiểm tra email được gửi từ AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];
    $otp = generateOTP();

    // Lưu OTP vào session hoặc cơ sở dữ liệu để kiểm tra sau
    session_start();
    $_SESSION['otp'] = $otp;

    // Cấu hình SMTP
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8'; // Thiết lập mã hóa UTF-8 cho email
        // Cấu hình máy chủ SMTP của Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'lethanhphata6@gmail.com'; // Email của bạn
        $mail->Password = 'wsmjcbavrqydrjdb'; // Mật khẩu ứng dụng (App Password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Cấu hình thông tin người gửi và người nhận
        $mail->setFrom('lethanhphata6@gmail.com', 'Bán Hàng Việt');
        $mail->addAddress($email);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'OTP xác nhận khôi phục mật khẩu';
        $mail->Body    = '<p>Mã OTP của bạn là: <strong>' . $otp . '</strong></p>';

        // Gửi email
        $mail->send();
        echo 'OTP sent';
    } catch (Exception $e) {
        echo "Có lỗi khi gửi OTP: {$mail->ErrorInfo}";
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha_secret = '6LdnOVMqAAAAAARTHP10d86nJzBmqWKDr2C37j0z'; // Thay YOUR_SECRET_KEY bằng secret key của bạn
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Gửi yêu cầu xác minh tới Google
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    $captcha_success = json_decode($verify);

    if ($captcha_success->success) {
        // Captcha hợp lệ, xử lý dữ liệu form
        echo "CAPTCHA hợp lệ!";
    } else {
        // Captcha không hợp lệ
        echo "CAPTCHA không hợp lệ!";
    }
}
?>

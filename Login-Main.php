
<?php 

include_once"config.php";
require_once __DIR__ ."/vendor/autoload.php";

$client = new Google\Client;
$client->setClientId("134714873321-su3v5rb3icl2b2ean2ap4kn6a5q7mluv.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("https://localhost/doan_php/redirect.php");

$client->addScope("email");
$client->addScope("profile");

$url = $client->createAuthUrl();
htmlspecialchars($url);


$permissions = ['email']; // Quyền mà bạn yêu cầu từ người dùng
$loginUrl = $helper->getLoginUrl('http://localhost/doan_php/facebook-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
include"page/login.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login, Register & Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Tùy chỉnh CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f7ea; /* Màu nền xanh lá nhạt */
        }
        .container {
            max-width: 700px;
            margin-top: 50px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .form-container {
            position: relative;
            width: 300%; /* Bởi vì có 3 form */
            display: flex;
            transition: transform 0.6s ease-in-out;
        }
        .form {
            width: 33.3333%; /* Mỗi form chiếm 1/3 */
            padding: 40px;
        }
        .btn {
            background-color: #4CAF50; /* Màu nút xanh cây */
            color: white;
        }
        .toggle-btn {
            cursor: pointer;
            color: #4CAF50;
            font-weight: bold;
        }
        .form-header {
            text-align: center;
            color: #4CAF50;
        }
        .form-header h2 {
            margin-bottom: 20px;
        }
        .social-login {
            display: flex;
            justify-content: space-between;
        }
        .social-login .btn {
            width: 48%;
            padding: 10px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .social-login .btn i {
            margin-right: 8px;
        }
        .btn-facebook {
            background-color: #3b5998;
            color: white;
        }
        .btn-google {
            background-color: #db4437;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="form-container" id="form-container">
                <!-- Form đăng nhập -->
                <div class="form" id="login-form">
                    <div class="form-header">
                        <h2>Đăng nhập</h2>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="login-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="login-password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                        <p class="text-center mt-3">
                            Chưa có tài khoản? <span class="toggle-btn" onclick="toggleForms('register')">Đăng ký</span> | 
                            <span class="toggle-btn" onclick="toggleForms('reset')">Quên mật khẩu?</span>
                        </p>
                        <!-- Đăng nhập bằng Facebook và Google -->
                        <div class="social-login mt-3">
                            
                          <a href="<?php echo $loginUrl;?>" class="btn btn-facebook"><i class="fab fa-facebook-f"></i> Facebook </a>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <a href="<?php echo $url; ?>"class="btn btn-google"><i class="fab fa-google"></i>Đăng nhập băng google</a>
                        </div>
                    </form>
                </div>

                <!-- Form đăng ký -->
                <div class="form" id="register-form">
                    <div class="form-header">
                        <h2>Đăng ký</h2>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label for="register-name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="register-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="register-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="register-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="register-phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="register-phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="register-dob" class="form-label">Ngày tháng năm sinh</label>
                            <input type="date" class="form-control" id="register-dob" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                        <p class="text-center mt-3">
                            Đã có tài khoản? <span class="toggle-btn" onclick="toggleForms('login')">Đăng nhập</span>
                        </p>
                    </form>
                </div>

                <!-- Form quên mật khẩu -->
                <div class="form" id="reset-form">
                    <div class="form-header">
                        <h2>Quên mật khẩu</h2>
                    </div>
                    <form>
                        <div class="mb-3">
                            <label for="reset-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="reset-email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Gửi yêu cầu</button>
                        <p class="text-center mt-3">
                            Nhớ mật khẩu? <span class="toggle-btn" onclick="toggleForms('login')">Đăng nhập</span>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleForms(form) {
        const formContainer = document.getElementById('form-container');
        if (form === 'login') {
            formContainer.style.transform = 'translateX(0)';
        } else if (form === 'register') {
            formContainer.style.transform = 'translateX(-33.3333%)';
        } else if (form === 'reset') {
            formContainer.style.transform = 'translateX(-66.6666%)';
        }
    }
</script>

</body>
</html>


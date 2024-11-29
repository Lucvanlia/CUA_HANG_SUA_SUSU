<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
require_once "admin_test/ketnoi/conndb.php";
include_once "config.php";
if (!isset($_SESSION["login-google"]) || !isset($_SESSION["login-facebook"]) || !isset($_SESSION['id_user'])) {

    require_once __DIR__ . "/vendor/autoload.php";

    // Initialize Google Client
    $client = new Google\Client;
    $client->setClientId("134714873321-su3v5rb3icl2b2ean2ap4kn6a5q7mluv.apps.googleusercontent.com");
    $client->setClientSecret("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");
    $client->setRedirectUri("https://banhangviet-tmi.net/doan_php/redirect.php");
    $client->addScope("email");
    $client->addScope("profile");
    $url = $client->createAuthUrl();
    htmlspecialchars($url);

    // Facebook Login URL
    $permissions = ['email'];
    $loginUrl = $helper->getLoginUrl('https://banhangviet-tmi.net/doan_php/facebook-callback.php', $permissions);

    $message = "";


    // Kết nối cơ sở dữ liệu
    // ... (đoạn mã kết nối cơ sở dữ liệu của bạn)



?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng nhập / đăng kí</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            /* Tùy chỉnh CSS */
            body {
                font-family: Arial, sans-serif;
                background-color: #e9f7ea;
                /* Màu nền xanh lá nhạt */
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
                width: 300%;
                /* Bởi vì có 3 form */
                display: flex;
                transition: transform 0.6s ease-in-out;
            }

            .form {
                width: 33.3333%;
                /* Mỗi form chiếm 1/3 */
                padding: 40px;
            }

            .btn {
                background-color: #4CAF50;
                /* Màu nút xanh cây */
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

            /* màu đấu tích và dấu x */
            .valid {
                color: green;
            }

            .invalid {
                color: red;
            }
        </style>
    </head>

    <body>
        <script>
            <?php if (isset($message) && !empty($message)) echo 'aler(' . $message . ')'; ?>
        </script>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-container" id="form-container">
                        <!-- Form đăng nhập -->
                        <div class="form" id="login-form">
                            <div class="form-header">
                                <h2>Đăng nhập</h2>

                            </div>
                            <form method="POST" action="" id="FormDangNhap">
                                <input type="hidden" name="action" value="login">
                                <div class="mb-3">
                                    <label for="login-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="login-email" id="login-email" required
                                        placeholder="Nhập email của bạn">
                                </div>
                                <div class="mb-3">
                                    <label for="login-password" class="form-label">Mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="login-password"
                                            id="login-password" required placeholder="Hãy nhập mật khẩu của bạn">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id ="btnDangNhap">Đăng nhập</button>
                                <p class="text-center mt-3">
                                    Chưa có tài khoản? <span class="toggle-btn" onclick="toggleForms('register')">Đăng
                                        ký</span> |
                                    <span class="toggle-btn" onclick="toggleForms('reset')">Quên mật khẩu?</span>
                                </p>


                            </form>
                            <!-- Đăng nhập bằng Facebook và Google -->
                            <div class="social-login mt-3">
                                <a href="<?php echo $loginUrl; ?>" class="btn btn-facebook"><i class="fab fa-facebook-f"></i>
                                    Facebook </a>
                                <a href="<?php echo $url; ?>" class="btn btn-google"><i class="fab fa-google"></i>Đăng nhập
                                    băng google</a>
                            </div>
                        </div>

                        <!-- Form đăng ký -->
                        <div class="form" id="register-form">
                            <div class="form-header">
                                <h2>Đăng ký</h2>
                            </div>
                            <form method="POST" action="" id="formDangKy">
                                <input type="hidden" name="action" value="register">
                                <div class="mb-3">
                                    <label for="register-name" class="form-label">Họ và tên</label><span> </span><span
                                        id="nameCheck"></span>
                                    <input type="text" class="form-control" name="register-name" id="register-name" required
                                        placeholder="Nhập họ tên của bạn muốn đăng kí">
                                    <span id="nameError" class="error-message" style="color: red; display: none;">Vui lòng
                                        nhập tên hợp lệ.</span><br>
                                </div>
                                <div class="mb-3">
                                    <label for="register-email" class="form-label">Email</label><span></span><span
                                        id="emailCheck"></span>
                                    <input type="email" class="form-control" name="register-email" id="register-email"
                                        required placeholder="Nhập email của bạn muốn đăng kí">
                                    <span id="emailError" class="error-message" style="color: red; display: none;">Vui lòng
                                        nhập email hợp lệ.</span><span><? echo "$message"; ?></span><br>
                                </div>
                                <div class="mb-3">
                                    <label for="register-phone" class="form-label">Số điện thoại</label><span> </span><span
                                        id="phoneCheck"></span>
                                    <input type="text" class="form-control" name="register-phone" id="register-phone"
                                        required placeholder="Nhập số điện thoại của bạn muốn đăng kí">
                                    <span id="phoneError" class="error-message" style="color: red; display: none;">Vui lòng
                                        nhập số điện thoại hợp lệ.</span><br>
                                </div>
                                <div class="mb-3">
                                    <label for="register-dob" class="form-label">Ngày tháng năm sinh</label><span>
                                    </span><span id="birthdateCheck"></span>
                                    <input type="date" class="form-control" name="register-dob" id="register-dob"
                                        required><span id="nameError" class="error-message"
                                        style="color: red; display: none;">Vui lòng nhập tên hợp lệ.</span><br>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu</label><span>&nbsp; </span><span id="passwordCheck" style="color: red;"></span>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="password" required
                                            placeholder="Hãy nhập mật khẩu bạn muốn đăng kí">
                                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                            <i class="fa fa-eye" id="eyeIcon"></i>
                                        </span>
                                    </div>
                                    <!-- <div>
                                        <span id="strengthIndicator"></span>
                                    </div> -->
                                </div>
                                <button type="submit" class="btn btn-primary w-100" id="btnDangKy">Đăng ký</button>
                                <p class="text-center mt-3">
                                    Đã có tài khoản? <span class="toggle-btn" onclick="toggleForms('login')">Đăng
                                        nhập</span>
                                </p>
                            </form>
                        </div>

                        <!-- Form quên mật khẩu -->
                        <div class="form" id="reset-form">
                            <div class="form-header">
                                <h2>Quên mật khẩu</h2>
                            </div>
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="reset">
                                <div class="mb-3">
                                    <label for="reset-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="reset-email" id="reset-email" required
                                        placeholder="Nhập email của bạn">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Khôi phục mật khẩu</button>
                                <p class="text-center mt-3">
                                    <span class="toggle-btn" onclick="toggleForms('login')">Trở về Đăng nhập</span>
                                </p>
                            </form>
                        </div>
                    </div>
                    <div class="text-center">

                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <!-- -->
        <script>
            function toggleForms(formType) {
                const container = document.getElementById("form-container");
                switch (formType) {
                    case 'login':
                        container.style.transform = "translateX(0)";
                        break;
                    case 'register':
                        container.style.transform = "translateX(-33.3333%)";
                        break;
                    case 'reset':
                        container.style.transform = "translateX(-66.6666%)";
                        break;
                }
            };

            // Validate email format
            function validateEmail(email) {
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return regex.test(email);
            }

            // Check email existence in the database
            document.getElementById('register-email').addEventListener('input', function() {
                const email = this.value;
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const emailCheck = document.getElementById('emailCheck');
                const emailError = document.getElementById('emailError');

                // Kiểm tra nếu trường trống
                if (email.trim() === "") {
                    emailCheck.textContent = "";
                    emailCheck.className = "";
                    emailError.style.display = "none"; // Ẩn thông báo lỗi
                } else if (regex.test(email)) {
                    emailCheck.textContent = "✔";
                    emailCheck.className = "valid";
                    emailError.style.display = "none"; // Ẩn thông báo lỗi
                } else {
                    emailCheck.textContent = "✘";
                    emailCheck.className = "invalid";
                    emailError.style.display = "block"; // Hiện thông báo lỗi
                }
            });




            // Kiểm tra trường "Tên"
            document.getElementById('register-name').addEventListener('input', function() {
                const name = this.value;
                // const regex = /^[a-zA-ZàáảãạầấẩẫậèéẻẽệêễìíịòóỏõọồốổỗộơớờợởỡùúủũụuứừửữựỳýỵÀÁẢÃẠẦẤẨẪẬÈÉẺẼỄỆÊÌÍỊÒÓỎÕỌỒỐỔỖỘƠỜỚỞỠỠÙÚỦŨỤƯỨỪỬỮỰÝỲỸỶỴ\s]+$/u;
                //const regex =/^[a-zA-ZàáảãạầấẩẫậèéẻẽệêễìíịòóỏõọồốổỗộơớờợởỡùúủũụuứừửữựỳýỵÀÁẢÃẠẦẤẨẪẬÈÉẺẼỄỆÊÌÍỊÒÓỎÕỌỒỐỔỖỘƠỜỚỞỠỠÙÚỦŨỤƯỨỪỬỮỰÝỲỸỶỴ\s]+$/u; // Cho phép ký tự tiếng Việt
                const regex = /[a-zA-ZàáảãạầấẩẫậèéẻẽệêễìíịòóỏõọồốổỗộơớờợởỡùúủũụuứừửữựỳýỵÀÁẢÃẠẦẤẨẪẬÈÉẺẼỄỆÊÌÍỊÒÓỎÕỌỒỐỔỖỘƠỜỚỞỠỠÙÚỦŨỤƯỨỪỬỮỰÝỲỸỶỴ\s]/g;
                const nameCheck = document.getElementById('nameCheck');
                const nameError = document.getElementById('nameError');

                // Kiểm tra nếu trường trống
                if (name.trim() === "") {
                    nameCheck.textContent = "";
                    nameCheck.className = "";
                    nameError.style.display = "none"; // Ẩn thông báo lỗi
                } else if (regex.test(name)) {
                    nameCheck.textContent = "✔";
                    nameCheck.className = "valid";
                    nameError.style.display = "none"; // Ẩn thông báo lỗi
                } else {
                    nameCheck.textContent = "✘";
                    nameCheck.className = "invalid";
                    nameError.style.display = "block"; // Hiện thông báo lỗi
                }
            });



            // Kiểm tra trường "Số điện thoại"
            document.getElementById('register-phone').addEventListener('input', function() {
                const phone = this.value;
                const regex = /^0[0-9]{9}$/;
                const phoneCheck = document.getElementById('phoneCheck');
                const phoneError = document.getElementById('phoneError');

                // Kiểm tra nếu trường trống
                if (phone.trim() === "") {
                    phoneCheck.textContent = "";
                    phoneCheck.className = "";
                    phoneError.style.display = "none"; // Ẩn thông báo lỗi
                } else if (regex.test(phone)) {
                    phoneCheck.textContent = "✔";
                    phoneCheck.className = "valid";
                    phoneError.style.display = "none"; // Ẩn thông báo lỗi
                } else {
                    phoneCheck.textContent = "✘";
                    phoneCheck.className = "invalid";
                    phoneError.style.display = "block"; // Hiện thông báo lỗi
                }
            });

            document.getElementById('password').addEventListener('input', function() {
                const passwordField = this;
                const passwordCheck = document.getElementById('passwordCheck');
                const passwordError = document.getElementById('passwordError');
                const passwordValue = passwordField.value;
                if (passwordValue.length >= 6) {
                    passwordCheck.innerHTML = "<span class='valid'>✓</span>";
                    passwordError.style.display = 'none';
                } else {
                    passwordCheck.innerHTML = "<span class='invalid'>✗</span>";
                    passwordError.style.display = 'block';
                }
            });

            // Kiểm tra trường "Ngày sinh"
            document.getElementById('register-dob').addEventListener('input', function() {
                const birthdate = new Date(this.value);
                const today = new Date();

                const age = today.getFullYear() - birthdate.getFullYear();
                const monthDifference = today.getMonth() - birthdate.getMonth();
                const dayDifference = today.getDate() - birthdate.getDate();

                if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                    age--; // Điều chỉnh nếu chưa qua sinh nhật trong năm hiện tại
                }

                const birthdateCheck = document.getElementById('birthdateCheck');
                const birthdateError = document.getElementById('birthdateError');

                // Kiểm tra nếu trường trống
                if (this.value.trim() === "") {
                    birthdateCheck.textContent = "";
                    birthdateCheck.className = "";
                    birthdateError.style.display = "none"; // Ẩn thông báo lỗi
                } else if (age >= 12 && age <= 120) {
                    birthdateCheck.textContent = "✔";
                    birthdateCheck.className = "valid";
                    birthdateError.style.display = "none"; // Ẩn thông báo lỗi
                } else {
                    birthdateCheck.textContent = "✘";
                    birthdateCheck.className = "invalid";
                    birthdateError.style.display = "block"; // Hiện thông báo lỗi
                    birthdateError.textContent = "Tuổi phải từ 12 đến 120.";
                }
            });

            // Kiểm tra trường "Mật khẩu" dăng kí
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const passwordCheck = document.getElementById('passwordCheck');
            const strengthIndicator = document.getElementById('strengthIndicator');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });





            passwordInput.addEventListener('input', function() {
                const passwordValue = passwordInput.value;

                // Kiểm tra xem mật khẩu có chứa ký tự tiếng Việt không
                const vietnameseRegex = /[àáảãạầấẩẫậèéẻẽệêìíịòóỏõọồốổỗộùúủũụỳýỵ]/i;

                // Kiểm tra xem mật khẩu có chứa khoảng trắng không
                const containsWhitespace = /\s/.test(passwordValue);

                if (vietnameseRegex.test(passwordValue)) {
                    passwordCheck.textContent = "Mật khẩu không được chứa ký tự tiếng Việt.";
                    strengthIndicator.textContent = ""; // Xóa thông báo độ mạnh
                    return; // Ngừng kiểm tra nếu có lỗi
                } else if (containsWhitespace) {
                    passwordCheck.textContent = "Mật khẩu không được chứa khoảng trắng.";
                    strengthIndicator.textContent = ""; // Xóa thông báo độ mạnh
                    return; // Ngừng kiểm tra nếu có lỗi
                } else {
                    passwordCheck.textContent = ""; // Xóa thông báo khi không có lỗi
                }

                // Kiểm tra độ mạnh của mật khẩu
                let strength = 0;

                if (passwordValue.length >= 8) strength++; // Độ dài tối thiểu 8 ký tự
                if (/[a-z]/.test(passwordValue)) strength++; // Có chữ cái thường
                if (/[A-Z]/.test(passwordValue)) strength++; // Có chữ cái hoa
                if (/[0-9]/.test(passwordValue)) strength++; // Có số
                if (/[\W_]/.test(passwordValue)) strength++; // Có ký tự đặc biệt

                switch (strength) {
                    case 0:
                    case 1:
                        strengthIndicator.textContent = "Rất yếu";
                        strengthIndicator.style.color = "red";
                        break;
                    case 2:
                        strengthIndicator.textContent = "Yếu";
                        strengthIndicator.style.color = "orange";
                        break;
                    case 3:
                        strengthIndicator.textContent = "Khá";
                        strengthIndicator.style.color = "yellow";
                        break;
                    case 4:
                    case 5:
                        strengthIndicator.textContent = "Mạnh";
                        strengthIndicator.style.color = "green";
                        break;
                }

                // Xóa trạng thái khi ô nhập rỗng
                if (passwordValue.length === 0) {
                    passwordCheck.textContent = "";
                    strengthIndicator.textContent = "";
                }
            });



            // Kiểm tra xem tất cả các trường có hợp lệ không
            function isValidForm() {
                const nameCheck = document.getElementById('nameCheck').textContent === "✔";
                const emailCheck = document.getElementById('emailCheck').textContent === "✔";
                const phoneCheck = document.getElementById('phoneCheck').textContent === "✔";
                const birthdateCheck = document.getElementById('birthdateCheck').textContent === "✔";
                const passwordStrength = document.getElementById('strengthIndicator').textContent === "Mạnh";

                return nameCheck && emailCheck && phoneCheck && birthdateCheck && passwordStrength;
            }

            document.querySelector('form').addEventListener('submit', function(e) {
                if (!isValidForm()) {
                    e.preventDefault(); // Ngăn không cho form submit nếu không hợp lệ
                    alert('Vui lòng điền đúng các thông tin trước khi đăng ký.');
                }
            });
            $('#btnDangKy').on('click', function(e) {
                e.preventDefault();

                const formData = new FormData($('#formDangKy')[0]);
                formData.append('action', 'register'); // Gắn thêm action để phân biệt

                $.ajax({
                    url: 'Login-process.php', // Đường dẫn PHP xử lý
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json', // Chỉ định kiểu dữ liệu trả về là JSON
                    success: function(response) {
                        if (response.status === 'success') {
                            Fancybox.show([{
                                src: `
                        <div style="padding: 20px; text-align: center;">
                            <img src="img/verified.gif" width="50" height="50" alt="Verified">
                            <h3>Thông báo</h3>
                            <p>${response.message}</p>
                            <button  onclick="toggleForms('login')" class="btn btn-primary mt-2">Đăng nhập tại đây</button>
                        </div>`,
                                type: 'html',
                            }]);
                            $('#formDangKy')[0].reset();
                        } else {
                            Fancybox.show([{
                                src: `
                        <div style="padding: 20px; text-align: center;">
                            <img src="img/error.gif" width="50" height="50" alt="Error">
                            <h3>Thông báo</h3>
                            <p>${response.message}</p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                                type: 'html',
                            }]);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Đã có lỗi xảy ra!');
                    }
                });
            });
            $('#btnDangNhap').on('click', function(e) {
                e.preventDefault();

                const formData = new FormData($('#FormDangNhap')[0]);
                formData.append('action', 'login'); // Gắn thêm action để phân biệt

                $.ajax({
                    url: 'Login-process.php', // Đường dẫn PHP xử lý
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json', // Chỉ định kiểu dữ liệu trả về là JSON
                    success: function(response) {
                        if (response.status === 'success') {
                            Fancybox.show([{
                                src: `
                        <div style="padding: 20px; text-align: center;">
                            <img src="img/verified.gif" width="50" height="50" alt="Verified">
                            <h3>Thông báo</h3>
                            <p>${response.message}</p>
                          <a href="https://banhangviet-tmi.net/doan_php/">  <button  onclick="toggleForms('login')" class="btn btn-primary mt-2">Đăng nhập tại đây</button></a>
                        </div>`,
                                type: 'html',
                            }]);
                            $('#formDangNhap')[0].reset();
                        } else {
                            Fancybox.show([{
                                src: `
                        <div style="padding: 20px; text-align: center;">
                            <img src="img/error.gif" width="50" height="50" alt="Error">
                            <h3>Thông báo</h3>
                            <p>${response.message}</p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                                type: 'html',
                            }]);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Đã có lỗi xảy ra!');
                    }
                });
            });
        </script>

    </body>

    </html>
<?php } else {
    header("Location: https://banhangviet-tmi.net/doan_php/");
}
?>
<!-- Fancybox JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.css" />

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.umd.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

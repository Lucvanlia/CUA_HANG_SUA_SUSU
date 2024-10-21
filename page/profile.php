<?php
// Kết nối cơ sở dữ liệu
include("config.php");

// Kiểm tra đăng nhập Facebook hoặc Google
if (isset($_SESSION["login-facebook"]) && $_SESSION["login-facebook"] != "") {
    $sql = "SELECT * FROM khachhang WHERE facebook_id = " . $_SESSION["login-facebook"];
    $result = mysqli_query($link, $sql);
} elseif (isset($_SESSION["login-google"]) && $_SESSION["login-google"] != "") {
    $sql = "SELECT * FROM khachhang WHERE google_id = " . $_SESSION["login-google"];
    $result = mysqli_query($link, $sql);
} else {
    // Xử lý khi không có thông tin đăng nhập
    $result = null;
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    <!-- Tích hợp Bootstrap 5 từ CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .profile-img img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .hidden {
            display: none;
        }

        #uploadImage {
            display: none;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="profile-container">
            <h2 class="text-center mb-4" id="formTitle">Thông tin tài khoản</h2>
            <?php if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
            ?>
                <div class="text-center mb-4">
                    <?php if ($row["Authen_Email"] <= 0) { ?>
                        <button class="btn btn-primary btn-sm" id="showProfileForm">Thông tin chính</button>
                        <button class="btn btn-success btn-sm" id="showPasswordForm">Đổi mật khẩu</button>
                        <a class="btn btn-success btn-sm" href="?action=profile&query=orders">Lịch sử mua hàng</a>
                    <?php } else { ?>
                        <button class="btn btn-primary btn-sm" id="showProfileForm">Thông tin chính</button>
                        <button class="btn btn-success btn-sm" id="showPasswordForm">Đổi mật khẩu</button>
                        <button class="btn btn-danger btn-sm" id="showEmailVerificationForm">Xác minh email</button>
                    <?php } ?>
                </div>

                <div class="profile-img text-center mb-4">
                    <?php
                    if ($row["profile_pic"] != "")
                        echo '<img src="' . $row["profile_pic"] . '" alt="' . $row["id_kh"] . '" id="profilePicture" class="img-thumbnail">';
                    else
                        echo '<img src="default-profile.png" alt="Profile Picture" id="profilePicture" class="img-thumbnail">';
                    ?>
                    <br>
                    <input type="file" id="uploadImage" accept="image/*">
                    <button class="btn btn-primary mt-3" id="changeImage">Đổi ảnh</button>
                </div>

                <form id="profileForm" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="update" name ="status_profile">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Tên người dùng:</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $row["Ten_KH"] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $row["email_kh"] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row["sdt_kh"] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Ngày sinh:</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $row["namsinh_kh"] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Address" class="form-label">Địa chỉ</label>
                        <textarea class="form-control" id="Address" name="address" rows="3"><?php echo $row["DChi_kh"]; ?></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" id="submit-update" name="submit-kh" class="btn btn-success">Cập nhật thông tin</button>
                    </div>
                </form>
                <div class="container mt-5 hidden" id="otpForm">
                    <div class="card p-4">
                        <h2 class="text-center mb-4">Quên mật khẩu</h2>

                        <!-- Nút gửi OTP -->
                        <button class="btn btn-primary mb-3" id="sendOtpBtn">Gửi OTP</button>

                        <!-- Input để nhập OTP -->
                        <div class="mb-3" >
                            <label for="otp" class="form-label">Nhập OTP:</label>
                            <input type="text" class="form-control" id="otp" name="otp" required>
                        </div>

                        <!-- Form đổi mật khẩu (ẩn ban đầu) -->
                        <div id="passwordForm" class="hidden">
                            <form id="changePasswordForm">
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Mật khẩu mới:</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới:</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                <button type="submit" class="btn btn-success">Đổi mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>



            <?php } else { ?>
                <p>Không tìm thấy thông tin người dùng.</p>
            <?php } ?>
        </div>
    </div>

    <!-- Bootstrap và jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Hiển thị form thông tin chính khi nhấn vào nút "Thông tin chính"
            $('#showProfileForm').click(function() {
                $('#profileForm').removeClass('hidden');
                $('#otpForm').addClass('hidden');
                $('#passwordForm').addClass('hidden');
            });

            // Hiển thị form OTP khi nhấn vào nút "Đổi mật khẩu"
            $('#showPasswordForm').click(function() {
                $('#otpForm').removeClass('hidden');
                $('#passwordForm').addClass('hidden');
                $('#profileForm').addClass('hidden');
            });



            // Hiển thị ảnh người dùng ngay khi chọn ảnh mới
            $('#uploadImage').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profilePicture').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Gửi OTP
            $(document).ready(function() {
                var countdownTimer;
                var countdownDuration = 120; // Thời gian đếm ngược 120 giây

                // Yêu cầu OTP
                $('#requestOtpBtn').click(function() {
                    $.post('send-otp.php', function(response) {
                        if (response === "OTP sent") {
                            alert("OTP đã được gửi đến email của bạn. Vui lòng kiểm tra.");
                            $('#verifyOtpContainer').removeClass('hidden');
                            startCountdown();
                            $('#requestOtpBtn').prop('disabled', true);
                        } else {
                            alert("Có lỗi khi gửi OTP. Vui lòng thử lại.");
                        }
                    });
                });

                // Đếm ngược 120 giây
                function startCountdown() {
                    var timeLeft = countdownDuration;
                    $('#countdownText').text("Vui lòng chờ " + timeLeft + " giây để gửi yêu cầu tiếp.");

                    countdownTimer = setInterval(function() {
                        timeLeft--;
                        $('#countdownText').text("Vui lòng chờ " + timeLeft + " giây để gửi yêu cầu tiếp.");

                        if (timeLeft <= 0) {
                            clearInterval(countdownTimer);
                            $('#requestOtpBtn').prop('disabled', false);
                            $('#countdownText').text("");
                        }
                    }, 1000);
                }

                // Xác nhận OTP
                $('#verifyOtpForm').on('submit', function(event) {
                    event.preventDefault();
                    var otp = $('#otp').val();

                    $.post('verify-otp.php', {
                        otp: otp
                    }, function(response) {
                        if (response === "OTP verified") {
                            alert("OTP xác nhận thành công. Bạn có thể đổi mật khẩu.");
                            $('#verifyOtpContainer').addClass('hidden');
                            $('#changePasswordForm').removeClass('hidden');
                        } else {
                            alert("OTP không chính xác. Vui lòng thử lại.");
                        }
                    });
                });

                // Đổi mật khẩu
                $('#passwordForm').on('submit', function(event) {
                    event.preventDefault();
                    var newPassword = $('#newPassword').val();
                    var confirmPassword = $('#confirmPassword').val();

                    if (newPassword !== confirmPassword) {
                        alert("Mật khẩu xác nhận không khớp.");
                        return;
                    }


                });
            });
        });
    </script>
    <?php
    $sql_email = "SELECT email_kh from khachhang where id_kh = " . $_SESSION['id_user'];
    $result_email = mysqli_query($link, $sql_email);
    $email = ''; // Khởi tạo biến email
    while ($row = mysqli_fetch_array($result_email)) {
        $email = $row['email_kh'];
    }
    ?>
    <script>
        $(document).ready(function() {
            // Hiển thị ảnh người dùng ngay khi chọn
            $('#uploadImage').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profilePicture').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Click vào nút đổi ảnh sẽ mở hộp chọn file
            $('#changeImage').click(function() {
                $('#uploadImage').click();
            });

            // Cập nhật thông tin người dùng bằng AJAX
            $('#profileForm').on('submit', function(event) {
                event.preventDefault(); // Ngăn chặn hành động submit mặc định
                var formData = new FormData(this);
                $.ajax({
                    url: 'update-profile.php', // File PHP xử lý việc cập nhật
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response === "success") {
                            alert("Cập nhật thông tin thành công!");
                        } else {
                            alert("Có lỗi xảy ra: " + response);
                        }
                    },
                    error: function() {
                        alert("Có lỗi xảy ra, vui lòng thử lại!");
                    }
                });
            });

            // AJAX cho việc cập nhật ảnh
            $('#uploadImage').on('change', function() {
                var formData = new FormData();
                formData.append('profile_pic', this.files[0]);

                $.ajax({
                    url: 'update-profile.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response === "success") {
                            alert("Ảnh đã được cập nhật thành công!");
                        } else if(response === "false") {
                            alert("Có lỗi khi cập nhật ảnh: " + response);
                        }
                    },
                    error: function() {
                        alert("Có lỗi xảy ra khi cập nhật ảnh, vui lòng thử lại!");
                    }
                });
            });
        });
        $(document).ready(function() {
            // Gửi OTP khi người dùng yêu cầu
            $('#sendOtpBtn').click(function() {
                var email = $('#email').val();
                $.post('send-otp.php', {
                    email: email
                }, function(response) {
                    if (response === "OTP sent") {
                        alert("OTP đã được gửi đến email của bạn.");
                    } else {
                        alert("Có lỗi khi gửi OTP.");
                    }
                });
            });

            // Xác nhận OTP và hiển thị form đổi mật khẩu nếu OTP đúng
            $('#verifyOtpForm').on('submit', function(event) {
                event.preventDefault();
                var otp = $('#otp').val();
                $.post('verify-otp.php', {
                    otp: otp
                }, function(response) {
                    if (response === "OTP verified") {
                        alert("OTP xác nhận thành công. Bạn có thể đổi mật khẩu.");
                        $('#otpForm').addClass('hidden');
                        $('#passwordForm').removeClass('hidden');
                    } else {
                        alert("OTP không chính xác.");
                    }
                });
            });

            // Đổi mật khẩu khi OTP đã được xác nhận

        });
    </script>
    <script>
        $(document).ready(function() {
            var isOtpSent = false;
            var otpTimer;

            // Chức năng gửi OTP
            $('#sendOtpBtn').click(function() {
                if (isOtpSent) {
                    alert('Vui lòng đợi 120 giây để yêu cầu lại.');
                    return;
                }

                var email = alert('Bạn có chắc muốn gửi email về:<?php echo $email   ?>');
                if (email) {
                    $.post('send-otp.php', {
                        email: email
                    }, function(response) {
                        if (response === "OTP sent") {
                            alert('OTP đã được gửi đến email của bạn. Vui lòng kiểm tra và nhập OTP.');

                            // Vô hiệu hóa nút gửi OTP và bắt đầu đếm ngược 120 giây
                            isOtpSent = true;
                            $('#sendOtpBtn').prop('disabled', true);
                            otpTimer = setTimeout(function() {
                                isOtpSent = false;
                                $('#sendOtpBtn').prop('disabled', false);
                            }, 120000);
                        } else {
                            alert('Có lỗi khi gửi OTP. Vui lòng thử lại.');
                        }
                    });
                }
            });

            // Xác nhận OTP
            $('#otp').on('input', function() {
                var otp = $('#otp').val();
                if (otp.length === 6) { // Giả sử OTP có độ dài 6 ký tự
                    $.post('verify-otp.php', {
                        otp: otp
                    }, function(response) {
                        if (response === "OTP verified") {
                            alert('OTP xác nhận thành công. Bạn có thể đổi mật khẩu.');
                            $('#passwordForm').removeClass('hidden');
                        } else {
                            alert('OTP không chính xác.');
                        }
                    });
                }
            });

            // Đổi mật khẩu
            // Cập nhật thông tin người dùng bằng AJAX
            $(document).ready(function() {
                // Cập nhật thông tin người dùng bằng AJAX
                $('#changePasswordForm').on('submit', function(event) {
                    event.preventDefault(); // Ngăn chặn hành động submit mặc định
                    var formData = new FormData(this);
                    $.ajax({
                        url: 'update-profile.php', // File PHP xử lý việc cập nhật
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response === "success") {
                                alert("Cập nhật thông tin thành công!");
                            } else if (response === "empty") {
                                alert("Mật khẩu không được để trống");
                            } else {
                                alert("Có lỗi xảy ra: " + response);
                            }
                        },
                        error: function() {
                            alert("Có lỗi xảy ra, vui lòng thử lại!");
                        }
                    });
                });
            });

        });
    </script>

    <script></script>
</body>

</html>
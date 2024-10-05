<?php 
        // người dùng sử dụng facebook đăng nhập vào 
        if(isset($_SESSION["login-facebook"]) && $_SESSION["login-facebook"] != "")
        {
            // truy vấn lấy thông tin người dùng
                $sql = "SELECT * FROM khachhang where facebook_id = ".$_SESSION["login-facebook"];
                $result = mysqli_query($link, $sql);
        }
        // người dùng sử dụng google đăng nhập vào 
        if(isset($_SESSION["login-google"]) && $_SESSION["login-google"] != "")
        {
            // truy vấn lấy thông tin người dùng
                $sql = "SELECT * FROM khachhang where google_id = ".$_SESSION["login-google"];
                $result = mysqli_query($link, $sql);
        }
        // người dùng sử dụng tài khoản thông thường
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile, Change Password, and Email Verification</title>

    <!-- Tích hợp Bootstrap 5 từ CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-img input[type="file"] {
            display: none;
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
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="profile-container">
        <h2 class="text-center mb-4" id="formTitle">Thông tin tài khoản</h2>
        <?php 
        
            while($row = mysqli_fetch_array($result) )
            {
        ?>
        <!-- Nút chuyển đổi giữa ba form -->
        <div class="text-center mb-4">
           <?php 
           //kiểm tra authent_email
                    echo $row["Authen_Email"];

                if($row["Authen_Email"] <= 0)
                {
                    echo'
                         <button class="btn btn-primary" id="showProfileForm">Thông tin chính</button>
                         <button class="btn btn-success" id="showPasswordForm">Đổi mật khẩu</button>

                    ';
                }
                else    
                {
                    echo'
                         <button class="btn btn-primary" id="showProfileForm">Thông tin chính</button>
                         <button class="btn btn-success" id="showPasswordForm">Đổi mật khẩu</button>
                         <button class="btn btn-danger" id="showEmailVerificationForm">Xác minh email</button>

                    ';
                }
           ?>
        </div>
            
        <!-- Form thông tin cá nhân -->
        <div class="profile-img text-center mb-4">
            <?php 
                    if($row["profile_pic"] != "")
                    echo '<img src="'.$row["profile_pic"].'" alt="'.$row["id_kh"].'" id="profilePicture" class="img-thumbnail">';
                    else    
                    echo '<img src="default-profile.png" alt="Profile Picture" id="profilePicture" class="img-thumbnail">';
            ?>
                <br>
                <input type="file" id="uploadImage" accept="image/*">
                <button class="btn btn-primary mt-3  " id="changeImage">Đổi ảnh</button>

            </div>

        <form id="profileForm" action="page/Xly.php" enctype="multipart/form-data">
          
            <div class="mb-3">
                <label for="fullname" class="form-label">Tên người dùng:</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $row["Ten_KH"]?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email"  name="email" value="<?php echo $row["email_kh"]?>" required>

            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại:</label>
                <?php
                           if($row["sdt_kh"] == "")
                           {
                               echo '<input type="text" class="form-control" id="dob" name="phone" placeholder="Nhập số điện thoại" required>';
                           }
                           else 
                           {
                               echo '<input type="text" class="form-control" id="dob" name="phone" value="'.$row["sdt_kh"].'" required>';
                           }
                ?>  
          </div>
          <div class="mb-3">
            <label for="dob" class="form-label">123123<label>
          <?php 
                    if(isset($_SESSION["Login-facebook"]))
                    {
                        echo $_SESSION["Login-faceb123123ook"];
                        echo'<input type="text" class="form-control" id="id_session"  name="id_session" value="'.$_SESSION["login-facebook"].'" required>';
                        echo '<p>asdasdasdasd</p>';
                    }
                    if(isset($_SESSION["Login-gooogle"]))
                    {
                        echo'<input type="text" class="form-control" id="id_session"  name="id_session" value="'.$_SESSION["login-google"].'" required>';
                    }
                    if(isset($_SESSION["Login-user"]))
                    {
                        echo'<input type="text" class="form-control" id="id_session"  name="id_session" value="'.$_SESSION["login-user"].'" required>';
                    }
                ?>
          </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Ngày sinh:</label>
                <?php 
                    if($row["namsinh_kh"] == "")
                    {
                        echo '<input type="date" class="form-control" id="dob" name="dob" value="1990-01-01" required>';
                    }
                    else 
                    {
                        echo '<input type="date" class="form-control" id="dob" name="dob" value="'.$row["namsinh_kh"].'" required>';
                    }
                ?>
            </div>

            <div class="mb-3">
                <label for="lastUpdate" class="form-label">Ngày chỉnh sửa:</label>
                <input type="text" class="form-control" id="lastUpdate" name="lastUpdate" value="<?php echo $row["updated_at"]?>" readonly>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">Cập nhật thông tin </button>
            </div>
        </form> 
                    
        <!-- Form đổi mật khẩu -->
        <form id="passwordForm" class="hidden">
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password:</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
            </div>

            <div class="mb-3">
                <label for="otpCode" class="form-label">Enter OTP Code:</label>
                <input type="text" class="form-control" id="otpCode" name="otpCode" required>
            <button type="button" class="btn btn-primary mt-2" id="sendVerificationOtp">Send OTP</button>
            </div>

            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password:</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm New Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">Change Password</button>
            </div>
        </form>

        <!-- Form xác minh email -->
        <form id="emailVerificationForm" class="hidden">
            <div class="mb-3">
                <label for="emailVerification" class="form-label">Enter Email for Verification:</label>
                <input type="email" class="form-control" id="emailVerification" name="emailVerification" required>
            </div>

            <div class="mb-3">
                <label for="verificationOtp" class="form-label">Enter OTP Code:</label>
                <input type="text" class="form-control" id="verificationOtp" name="verificationOtp" required>
                <button type="button" class="btn btn-primary mt-2" id="sendOtp">Send OTP</button>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success">Verify Email</button>
            </div>
        </form>
                <?php 
            }// kt while 
                ?>
    </div>
</div>
<style>
        /* Định dạng popup */
        #popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
<div id="overlay"></div>
    <div id="popup">
        <p id="popupMessage"></p>
        <button id="closePopup">Close</button>
    </div>
<!-- Bootstrap JavaScript và các plugin cần thiết -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
    // Sự kiện khi nhấn nút 'Change Picture'
    document.getElementById('changeImage').addEventListener('click', function () {
        document.getElementById('uploadImage').click();
    });

    // Hiển thị ảnh người dùng ngay khi chọn
    document.getElementById('uploadImage').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePicture').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Chuyển đổi giữa form thông tin cá nhân, đổi mật khẩu và xác minh email
    document.getElementById('showProfileForm').addEventListener('click', function () {
        document.getElementById('profileForm').classList.remove('hidden');
        document.getElementById('passwordForm').classList.add('hidden');
        document.getElementById('emailVerificationForm').classList.add('hidden');
        document.getElementById('formTitle').textContent = 'Thông tin tài khoản';
    });

    document.getElementById('showPasswordForm').addEventListener('click', function () {
        document.getElementById('profileForm').classList.add('hidden');
        document.getElementById('passwordForm').classList.remove('hidden');
        document.getElementById('emailVerificationForm').classList.add('hidden');
        document.getElementById('formTitle').textContent = 'Đổi mật khẩu';
    });

    document.getElementById('showEmailVerificationForm').addEventListener('click', function () {
        document.getElementById('profileForm').classList.add('hidden');
        document.getElementById('passwordForm').classList.add('hidden');
        document.getElementById('emailVerificationForm').classList.remove('hidden');
        document.getElementById('formTitle').textContent = 'Xác minh email';
    });

    // Xử lý khi nhấn nút 'Send OTP' cho đổi mật khẩu
    // document.getElementById('sendOtp').addEventListener('click', function () {
    //     alert('OTP has been sent to your email.');
    //     // Ở đây bạn có thể thêm chức năng gửi OTP qua email cho đổi mật khẩu
    // });

    // Xử lý khi nhấn nút 'Send OTP' cho xác minh email
    document.getElementById('sendVerificationOtp').addEventListener('click', function () {
        alert('Verification OTP has been sent to your email.');
        // Ở đây bạn có thể thêm chức năng gửi OTP qua email cho xác minh email
    });
    
</script>
<script>
    // Hàm đếm ngược thời gian cho nút OTP
    function startOtpCountdown(button, countdownTime) {
        let timeLeft = countdownTime;

        // Vô hiệu hóa nút và hiển thị thời gian đếm ngược
        button.disabled = true;
        button.textContent = `Resend OTP (${timeLeft}s)`;

        let countdownInterval = setInterval(function () {
            timeLeft--;
            button.textContent = `Resend OTP (${timeLeft}s)`;

            // Khi thời gian đếm ngược kết thúc, kích hoạt lại nút
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                button.disabled = false;
                button.textContent = 'Send OTP';
            }
        }, 1000); // Đếm ngược mỗi giây
    }

    // Xử lý khi nhấn nút 'Send OTP' cho đổi mật khẩu
    document.getElementById('sendOtp').addEventListener('click', function () {
        alert('OTP has been sent to your email.');
        startOtpCountdown(this, 120); // Đếm ngược 120 giây cho đổi mật khẩu
        // Ở đây bạn có thể thêm chức năng gửi OTP qua email cho đổi mật khẩu
    });

    // Xử lý khi nhấn nút 'Send OTP' cho xác minh email
    document.getElementById('sendVerificationOtp').addEventListener('click', function () {
        alert('Verification OTP has been sent to your email.');
        startOtpCountdown(this, 120); // Đếm ngược 120 giây cho xác minh email
        // Ở đây bạn có thể thêm chức năng gửi OTP qua email cho xác minh email
    });
</script>

<script>
        $(document).ready(function () {
            // Bắt sự kiện khi form được submit
            $('#uploadForm').on('submit', function (event) {
                event.preventDefault(); // Ngăn chặn submit mặc định

                // Tạo đối tượng FormData để chứa dữ liệu từ form
                var formData = new FormData(this);

                // Gửi yêu cầu Ajax
                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        // Hiển thị popup với thông báo từ server
                        $('#popupMessage').text(response);
                        $('#overlay').fadeIn();
                        $('#popup').fadeIn();
                    },
                    error: function () {
                        // Xử lý lỗi nếu có
                        $('#popupMessage').text('There was an error processing your request.');
                        $('#overlay').fadeIn();
                        $('#popup').fadeIn();
                    }
                });
            });

            // Đóng popup khi người dùng nhấn nút "Close"
            $('#closePopup').on('click', function () {
                $('#popup').fadeOut();
                $('#overlay').fadeOut();
            });
        });
    </script>
</body>
</html>

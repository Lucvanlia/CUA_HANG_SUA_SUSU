<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
                    include "ketnoi/conndb.php";



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
                                <h2>Đăng nhập quản trị</h2>
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



                            </form>
                            <!-- Đăng nhập bằng Facebook và Google -->
                          
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
                          <a href="http://localhost/doan_php/admin_test/">  <button  onclick="toggleForms('login')" class="btn btn-primary mt-2">Đăng nhập tại đây</button></a>
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

<!-- Fancybox JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.css" />

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.umd.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

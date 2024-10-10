<?php
    session_start(); // Khởi tạo session

    require_once __DIR__ . "/vendor/autoload.php";

    $client = new Google\Client;
    $client->setClientId("134714873321-su3v5rb3icl2b2ean2ap4kn6a5q7mluv.apps.googleusercontent.com");
    $client->setClientSecret("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");
    $client->setRedirectUri("https://localhost/doan_php/redirect.php");

    if (!isset($_GET["code"])) {
        exit("Đăng nhập thất bại!");
    }

    // Lấy access token từ Google
    $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if (isset($token["access_token"])) {
        $client->setAccessToken($token["access_token"]);
    } else {
        exit("Lỗi: Không thể lấy token.");
    }

    $oauth2 = new Google\Service\Oauth2($client);
    $userinfo = $oauth2->userinfo->get();

    $email = $userinfo->email;
    $name = $userinfo->name;
    $google_id = $userinfo->id;
    $pic = $userinfo->picture;

    // Kết nối database
    $conn = new mysqli('localhost', 'root', '', 'banhangviet');
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Kiểm tra xem người dùng đã tồn tại chưa
    $sql = "SELECT * FROM khachhang WHERE google_id='$google_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Người dùng đã tồn tại
        $_SESSION["login-google"] = $google_id;
        $_SESSION['message'] = "Đăng nhập thành công!";
     header("location:http://localhost/doan_php/");

    } else {
        $currentDate = date('Y-m-d H:i:s');
        // Thêm người dùng mới vào database
        $stmt = $conn->prepare("INSERT INTO khachhang (google_id, Ten_KH, email_kh, profile_pic,created_at,updated_at) VALUES (?, ?, ?, ?, ?,?)");
        $stmt->bind_param("ssssss", $google_id, $name, $email, $pic,$currentDate,$currentDate);
        $stmt->execute();

        // Lấy ID của người dùng mới thêm
        $sql = "SELECT id_kh FROM khachhang WHERE google_id='$google_id'";
        $result = $conn->query($sql);
        if ($row = $result->fetch_assoc()) {
            $id_kh = $row['id_kh']; 
            $_SESSION["name"] = $id_kh;
        }

        $_SESSION["login-google"] = $google_id;
        $_SESSION['message'] = "Đăng nhập thành công!";
        $stmt->close();
    }

    $conn->close();

    // Điều hướng người dùng sau khi đăng nhập
     header("location:http://localhost/doan_php/");
    exit();
?>
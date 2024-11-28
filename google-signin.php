<?php
require 'vendor/autoload.php'; // Thư viện Google Client (sử dụng Composer)

use Google\Client;

// Tạo đối tượng Google Client
$client = new Client();
$client->setClientId('1044221773699-g85p8fvc3fs57l72s03ialb1t1n60hv4.apps.googleusercontent.com'); // Thay bằng Client ID của bạn

// Nhận token từ phía frontend
$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'];

// Xác thực token JWT
try {
    $payload = $client->verifyIdToken($token);
    if ($payload) {
        // Nhận thông tin người dùng từ Google
        $googleId = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'];
        
        // Kiểm tra xem người dùng đã tồn tại trong cơ sở dữ liệu hay chưa
        $conn = new mysqli("localhost", "root", "", "banhangviet");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM users WHERE google_id='$googleId' OR email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Người dùng đã tồn tại, chỉ cần đăng nhập
            echo json_encode(['success' => true]);
        } else {
            // Người dùng chưa tồn tại, thêm mới vào cơ sở dữ liệu
            $sql = "INSERT INTO users (google_id, name, email) VALUES ('$googleId', '$name', '$email')";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error creating user']);
            }
        }

        $conn->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid ID token']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';

try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    echo 'Bad request';
    exit;
}

$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId('511847038124775'); // Thay bằng App ID của bạn
$tokenMetadata->validateExpiration();

if (!$accessToken->isLongLived()) {
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Error getting long-lived access token: ' . $e->getMessage();
        exit;
    }
}

$_SESSION['fb_access_token'] = (string) $accessToken;

// Lấy thông tin người dùng từ Facebook
try {
    $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
    $user = $response->getGraphUser();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

// Kết nối đến cơ sở dữ liệu và lưu thông tin người dùng
$conn = new mysqli('localhost', 'root', '', 'banhangviet'); // Thay thông tin database nếu cần

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$facebook_id = $user['id'];
$name = $user['name'];
$email = $user['email'];
$profile_pic = $user['picture']['url'];

// Kiểm tra nếu người dùng đã tồn tại
$sql = "SELECT * FROM khachhang WHERE facebook_id='$facebook_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Người dùng đã tồn tại    
    $_SESSION["login-facebook"] = $facebook_id;
    $_SESSION['message'] = "Đăng nhập thành công";
} else {
    // Thêm người dùng mới vào database
    $stmt = $conn->prepare("INSERT INTO khachhang (facebook_id, Ten_KH, email_kh, profile_pic) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $facebook_id, $name, $email, $profile_pic);

    if ($stmt->execute()) {
        $new_id = $stmt->insert_id; // Lấy id của bản ghi vừa thêm
        $_SESSION["login-facebook"] = $facebook_id;
        $_SESSION["name"] = $new_id;
        $_SESSION['message'] = "Đăng ký thành công!";
    } else {
        echo 'Error inserting user: ' . $stmt->error;
        exit;
    }

    $stmt->close();
}

$conn->close();
header("Location: https://banhangviet-tmi.net/doan_php/");
exit;
?>

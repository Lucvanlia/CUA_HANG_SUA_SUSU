<?php
session_start();
require_once("config_vnpay.php"); // Include VNPAY configuration

// Connect to the database
$conn = new mysqli("localhost", "root", "", "susu");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if vnp_SecureHash exists
$vnp_SecureHash = $_GET['vnp_SecureHash'] ?? null;

// Ensure $vnp_HashSecret is defined in config_vnpay.php
if (!isset($vnp_HashSecret)) {
    die("Hash secret is not defined in the configuration.");
}

// Collect VNPAY return parameters
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) === "vnp_") {
        $inputData[$key] = $value;
    }
}

// Sort parameters alphabetically
ksort($inputData);

// Create hash data string
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($key !== "vnp_SecureHash") { // Exclude vnp_SecureHash from the hash calculation
        $hashData .= urlencode($key) . "=" . urlencode($value) . "&";
    }
}
$hashData = rtrim($hashData, "&");

// Generate the secure hash using the secret key
$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Validate the secure hash
if ($secureHash === $vnp_SecureHash) {
    $id_kh = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
    // If the hash is valid, proceed with order processing
    $order_id = $_GET['vnp_TxnRef'];
    $amount = $_GET['vnp_Amount'] / 100;
    $bank_code = $_GET['vnp_BankCode'];
    $time_hd = time();
    // Check if the order already exists
    $stmt = $conn->prepare("SELECT * FROM hdb WHERE id_hdb = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Nếu đơn hàng chưa tồn tại, tiến hành thêm vào cơ sở dữ liệu
        $id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

        // Nếu không có id_user, tạo tài khoản khách hàng mới
        if (!$id_user) {
            // Lấy thông tin từ form (người dùng nhập khi thanh toán)
            $name = $_SESSION['name'];  // Tên khách hàng
            $email = $_SESSION['email'];  // Email khách hàng
            $address = $_SESSION['address'];  // Địa chỉ khách hàng
            $phone = $_SESSION['phone'];  // Số điện thoại khách hàng
            $image = 'hinh_kh.jpg';  // Hình ảnh mặc định nếu không có hình ảnh khách hàng

            // Kiểm tra email có tồn tại trong bảng KhachHang không
            $checkEmailQuery = "SELECT * FROM KhachHang WHERE Email_kh = '$email'";
            $result = mysqli_query($conn, $checkEmailQuery);

            if (mysqli_num_rows($result) > 0) {
                // Nếu email đã tồn tại, lấy id_kh của khách hàng đó
                $user = mysqli_fetch_assoc($result);
                $id_user = $user['id_kh'];
            } else {
                // Nếu email chưa tồn tại, tạo tài khoản khách hàng mới
                $insertCustomer = "INSERT INTO KhachHang (Ten_kh, Email_kh, Dchi_kh, Hinh_kh, HoatDong) 
                                   VALUES ('$name', '$email', '$address', '$image', 1)";
                if (mysqli_query($conn, $insertCustomer)) {
                    $id_user = mysqli_insert_id($conn); // Lấy id_kh của khách hàng mới
                } else {
                    echo "Không thể tạo tài khoản mới.";
                    exit();
                }
            }
        }

        // Tiến hành thêm hóa đơn và chi tiết hóa đơn
        mysqli_begin_transaction($conn); // Bắt đầu giao dịch

        try {
            // Tính tổng tiền giỏ hàng
            $tong_tien = 0;
            foreach ($_SESSION['cart'] as $item) {
                $tong_tien += $item['SoLuong'] * $item['GiaBan']; // Tính tổng tiền
            }

            // Thêm hóa đơn vào bảng HDB
            $id_nv = 1; // ID nhân viên mặc định, có thể lấy từ session nếu có

            $insertOrder = "INSERT INTO HDB (id_kh, id_nv, TrangThai, ThanhToan ) 
                            VALUES ($id_user, $id_nv, 6, 2)";
            if (!mysqli_query($conn, $insertOrder)) {
                throw new Exception("Không thể thêm hóa đơn.");
            }

            $id_hdb = mysqli_insert_id($conn); // Lấy ID của hóa đơn vừa tạo

            // Thêm chi tiết hóa đơn vào bảng CT_HDB
            foreach ($_SESSION['cart'] as $item) {
                $id_sp = $item['id_sp'];
                $id_dv = $item['id_dv'];
                $quantity = $item['SoLuong'];
                $price = $item['GiaBan'];
                $thanh_tien = $quantity * $price;

                $insertDetail = "INSERT INTO CT_HDB (id_hdb, id_sp, id_dv, SoLuong, DonGia, ThanhTien) 
                                 VALUES ($id_hdb, $id_sp, $id_dv, $quantity, $price, $thanh_tien)";
                if (!mysqli_query($conn, $insertDetail)) {
                    throw new Exception("Không thể thêm chi tiết hóa đơn cho sản phẩm ID: $id_sp.");
                }
            }

            // Commit giao dịch nếu không có lỗi
            mysqli_commit($conn);

            // Xóa giỏ hàng sau khi đặt hàng thành công
            unset($_SESSION['cart']);
            $_SESSION['tong_tien'] = 0; // Xóa tổng tiền giỏ hàng

            // Trả về kết quả thành công
            echo "success_vnpay";
            $_SESSION['login_success'] = "Chúc mưng bạn thanh toán thành công VNPAY";
            header("location:https://banhangviet-tmi.net/doan_php/");
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            mysqli_rollback($conn);
            echo "Lỗi: " . $e->getMessage();
        }
    } else {
        echo "Phương thức thanh toán không hợp lệ.";
    }
    // // Nếu mọi thứ thành công
    // if ($success) {
    //     unset($_SESSION['cart']);  // Xóa giỏ hàng

    // } else {
    //     echo "Có lỗi xảy ra trong quá trình xử lý đơn hàng.";
    // }


} else {
    // If the hash is not valid, report an error
    echo "Invalid transaction!<br>";
    echo "Hash Data String: " . htmlspecialchars($hashData) . "<br>";
    echo "Generated Hash: " . htmlspecialchars($secureHash) . "<br>";
    echo "VNPAY Hash: " . htmlspecialchars($vnp_SecureHash) . "<br>";
}

// Close the database connection
$conn->close();

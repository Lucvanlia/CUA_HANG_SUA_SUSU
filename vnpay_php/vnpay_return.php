<?php
require_once("config_vnpay.php"); // Include VNPAY configuration

// Connect to the database
$conn = new mysqli("localhost", "root", "", "banhangviet");
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
    $id_kh = $_SESSION['id_user'];
    // If the hash is valid, proceed with order processing
    $order_id = $_GET['vnp_TxnRef'];
    $amount = $_GET['vnp_Amount'] / 100;
    $bank_code = $_GET['vnp_BankCode'];
    $time_hd = time();
    // Check if the order already exists
    $stmt = $conn->prepare("SELECT * FROM hoadon WHERE id_hd = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If the order doesn't exist, insert it into the database
        // Thêm hóa đơn vào bảng hoadon (thực hiện sau khi trừ kho thành công)
        $id_khachhang = $_SESSION['id_user'];
        $ngay_tao = time();
        $tong_tien = 0;

        // Tính tổng tiền
        foreach ($_SESSION['cart'] as $product_details) {
            $quantity = $product_details[2];
            $price = $product_details[4]; // Giả sử giá sản phẩm lưu tại chỉ mục 1
            $tong_tien += $quantity * $price;
        }

        $insertOrder = "INSERT INTO hoadon (id_kh,NgayLapHD,TrangThai,pttt,tongtien,thanhtoan,bankcode ) VALUES ($id_khachhang, $ngay_tao,'1','0', $tong_tien,'0','$bank_code')";
        if (mysqli_query($conn, $insertOrder)) {
            // Thêm chi tiết hóa đơn
            $id_hoadon = mysqli_insert_id($conn);
            foreach ($_SESSION['cart'] as $product_details) {
                $product_id = $product_details[0];
                $quantity = $product_details[4];
                $price = $product_details[2];

                $insertDetail = "INSERT INTO ctiethd (id_hd, id_sp, SoLuong, dongia) 
                                      VALUES ($id_hoadon, $product_id, $quantity, $price)";
                if (!mysqli_query($conn, $insertDetail)) {
                    $success = false;
                    break;
                }
            }
            foreach ($_SESSION['cart'] as $product_details) {
                $product_id = $product_details[0];  // ID sản phẩm
                $quantity = $product_details[4];    // Số lượng sản phẩm

                // Kiểm tra số lượng tồn kho
                $query = "SELECT SoLuong FROM dmsp WHERE id_sp = $product_id";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);

                if ($row['SoLuong'] >= $quantity) {
                    // Cập nhật số lượng nếu đủ tồn kho
                    $updateQuery = "UPDATE dmsp SET SoLuong = SoLuong - $quantity WHERE id_sp = $product_id";
                    if (!mysqli_query($conn, $updateQuery)) {
                        $success = false;
                        break;
                    }
                    
                }
            }
            unset($_SESSION['cart']);
            $_SESSION['message'] = 'Đơn hàng của bạn đã được thanh toán';
            header("Location: https://banhangviet-tmi.net/doan_php/index.php?action=cart&query=view");
        }
    } else {
        echo "The order has already been processed.";
    }
} else {
    // If the hash is not valid, report an error
    echo "Invalid transaction!<br>";
    echo "Hash Data String: " . htmlspecialchars($hashData) . "<br>";
    echo "Generated Hash: " . htmlspecialchars($secureHash) . "<br>";
    echo "VNPAY Hash: " . htmlspecialchars($vnp_SecureHash) . "<br>";
}

// Close the database connection
$conn->close();

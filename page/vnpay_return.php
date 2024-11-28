<?php

// Kết nối đến cơ sở dữ liệu

// Lấy mã băm trả về từ VNPAY
$vnp_SecureHash = $_GET['vnp_SecureHash'];

// Tạo mảng chứa các tham số trả về từ VNPAY
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value; // Lấy tất cả tham số có tiền tố "vnp_"
    }
}

// Sắp xếp các tham số theo thứ tự từ điển (alphabetical order)
ksort($inputData);

// Tạo chuỗi hash data từ các tham số
$hashData = "";
foreach ($inputData as $key => $value) {
    $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
}
$hashData = rtrim($hashData, '&'); // Xóa ký tự & cuối cùng

// Tạo lại mã băm từ các tham số đã nhận
$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Kiểm tra mã băm có khớp với mã trả về từ VNPAY không
if ($secureHash == $vnp_SecureHash) {
    // Nếu mã băm hợp lệ, xử lý đơn hàng
    $order_id = $_GET['vnp_TxnRef']; // Mã đơn hàng
    $amount = $_GET['vnp_Amount'] / 100; // Tổng số tiền (phải chia cho 100 để lấy đúng giá trị)
    $bank_code = $_GET['vnp_BankCode']; // Mã ngân hàng

    // Kiểm tra xem đơn hàng đã tồn tại trong CSDL chưa
    $stmt = $conn->prepare("SELECT * FROM hoadon WHERE 	id_HoaDon = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Nếu đơn hàng chưa tồn tại, thêm vào CSDL
        $stmt = $conn->prepare("INSERT INTO hoadon (pttt, tongtien, bank_code) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", 1, $amount, $bank_code);
        $stmt->execute();
        echo "Thanh toán thành công. Mã đơn hàng: " . $order_id;
    } else {
        echo "Đơn hàng đã được xử lý trước đó!";
    }

} else {
    // Nếu mã băm không hợp lệ, báo lỗi
    $order_id = $_GET['vnp_TxnRef'] ?? 'Chưa có mã đơn hàng';
    $amount = $_GET['vnp_Amount'] ? ($_GET['vnp_Amount'] / 100) : 'Chưa có số tiền';
    $bank_code = $_GET['vnp_BankCode'] ?? 'Chưa có mã ngân hàng';
    var_dump($vnp_SecureHash,$secureHash );
    echo "Giao dịch không hợp lệ! <br>";
    echo "Thông tin giao dịch:<br>";
    echo "Mã đơn hàng: " . htmlspecialchars($order_id) . "<br>";
    echo "Số tiền: " . htmlspecialchars($amount) . "<br>";
    echo "Mã ngân hàng: " . htmlspecialchars($bank_code) . "<br>";
}
?>

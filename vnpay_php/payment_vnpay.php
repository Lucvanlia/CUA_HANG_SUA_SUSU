<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once("config_vnpay.php");

$total = 0;
// Tính tổng tiền giỏ hàng
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $stt = 1;
    foreach ($_SESSION['cart'] as $item) {
        $gia = $item['GiaBan'];
        $max_quantity = $item['max_quantity'];
        $soluong = $item['SoLuong'];
        $tt = $gia * $soluong;
        $total += $tt;
    }
}

$amount = $total; // Tổng số tiền từ giỏ hàng

if ($amount <= 0) {
    die('Giỏ hàng rỗng hoặc giá trị không hợp lệ.' .$amount);
}

// Tạo mã đơn hàng
$order_id = rand(100000, 999999);

// Tạo thông tin thanh toán
$vnp_Amount = $amount * 100; // Nhân với 100 để tính theo đồng
$vnp_TxnRef = $order_id;
$vnp_OrderInfo = 'Thanh toán đơn hàng';
$vnp_Locale = 'vn';
$vnp_BankCode = 'NCB';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => 'billpayment',
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef,
);

ksort($inputData);
$hashdata = http_build_query($inputData);
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$query = $hashdata . '&vnp_SecureHash=' . $vnpSecureHash;

$vnp_Url = $vnp_Url . "?" . $query;

header('Location: ' . $vnp_Url);
exit();

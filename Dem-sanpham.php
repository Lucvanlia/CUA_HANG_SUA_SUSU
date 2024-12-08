<?php
session_start();

// Kiểm tra giỏ hàng trong session
if (isset($_SESSION['cart'])) {
    // Đếm số lượng sản phẩm khác nhau (phần tử trong mảng `$_SESSION['cart']`)
    $cart_count = count($_SESSION['cart']);
    echo json_encode(['count' => $cart_count]);
} else {
    // Nếu giỏ hàng rỗng, trả về 0
    echo json_encode(['count' => 0]);
}
?>


<?php
session_start();
$conn = new mysqli("localhost", "root", "", "myshop");

if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

// Nhận ID sản phẩm
$product_id = $_POST['product_id'];

// Truy vấn sản phẩm từ CSDL
$query = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // Kiểm tra giỏ hàng
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Thêm sản phẩm vào giỏ hàng
    $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price']
    ];

    // Chuyển hướng về trang giỏ hàng
    header("Location: cart.php");
} else {
    echo "Không tìm thấy sản phẩm!";
}
?>

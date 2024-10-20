<?php
session_start();
$conn = new mysqli("localhost", "root", "", "myshop");

if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sản phẩm</title>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>
    <?php while ($row = $result->fetch_assoc()): ?>
        <form action="add_to_cart.php" method="POST">
            <p><?php echo $row['name']; ?> - <?php echo number_format($row['price']); ?> VND</p>
            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
            <button type="submit">Thêm vào giỏ hàng</button>
        </form>
    <?php endwhile; ?>
    <br>
    <a href="cart.php">Xem giỏ hàng</a>
</body>
</html>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
</head>
<body>
    <h1>Giỏ hàng của bạn</h1>
    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <table border="1">
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item): 
                $total += $item['price'];
            ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo number_format($item['price']); ?> VND</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Tổng cộng</strong></td>
                <td><strong><?php echo number_format($total); ?> VND</strong></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Giỏ hàng của bạn đang trống!</p>
    <?php endif; ?>

    <br>
    <a href="index.php">Tiếp tục mua sắm</a>
    <form action="payment_vnpay.php" method="POST">
        <button type="submit">Thanh toán VNPAY</button>
    </form>
</body>
</html>

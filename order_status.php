<?php
session_start();
include"admin_test/ketnoi/conndb.php";

if (isset($_SESSION['id_user'])) {
    $id_kh = $_SESSION['id_user'];
    $sql = "SELECT id_hd, TrangThai, NgayCapNhat
            FROM hoadon
            WHERE id_kh = $id_kh";
    $result = mysqli_query($link, $sql);

    if ($result) {
        echo "<div class='order-list'>";
        echo "<h3>Danh sách đơn hàng</h3>";
        while ($row = mysqli_fetch_assoc($result)) {
            $trangThai = "";
            switch ($row['TrangThai']) {
                case '0':
                    $trangThai = "Đã giao hàng";
                    break;
                case '1':
                    $trangThai = "Chờ xác nhận";
                    break;
                case '2':
                    $trangThai = "Đã xác nhận";
                    break;
                case '3':
                    $trangThai = "Đang giao hàng";
                    break;
                case '4':
                    $trangThai = "Đã nhận hàng";
                    break;
                case '5':
                    $trangThai = "Yêu cầu hủy đơn hàng";
                    break;
                case '6':
                    $trangThai = "Đơn hàng đã được hủy";
                    break;
                default:
                    $trangThai = "Không xác định";
            }

            // Định dạng thời gian cập nhật
            $formatted_date = date("d/m/Y H:i:s", $row['NgayCapNhat']);

            echo "<div class='order-item'>";
            echo "<p><strong>Mã đơn hàng:</strong> " . $row['id_hd'] . "</p>";
            echo "<p><strong>Trạng thái:</strong> " . $trangThai . "</p>";
            echo "<p><strong>Cập nhật lúc:</strong> " . $formatted_date . "</p>";
            echo "</div><hr>";
        }
        echo "</div>";
    } else {
        echo "<p>Không có đơn hàng nào.</p>";
    }
} else {
    echo "<p>Bạn cần đăng nhập để xem thông tin đơn hàng.</p>";
}
?>

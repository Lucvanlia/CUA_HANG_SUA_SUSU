<?php
include "../ketnoi/conndb.php";

if (isset($_GET['id_sp'])) {
    $id_sp = $_GET['id_sp'];

    // Truy vấn lấy thông tin sản phẩm
    $sql = "SELECT * FROM SanPham WHERE id_sp = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_sp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($product = mysqli_fetch_assoc($result)) {
        // Trả về thông tin sản phẩm dưới dạng JSON
        echo json_encode([
            'status' => 'success',
            'data' => $product
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Không tìm thấy sản phẩm'
        ]);
    }
}
?>

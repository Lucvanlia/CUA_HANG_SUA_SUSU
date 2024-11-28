<?php

mysqli_set_charset($link, "utf8");

// Số sản phẩm mỗi lần tải (ở đây là 4)
$items_per_load = 4;

// Lấy giá trị của trang từ AJAX request
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$offset = ($page - 1) * $items_per_load;

// Truy vấn sản phẩm với giới hạn phân trang
$sql = "SELECT * FROM dmsp as sp 
        JOIN xuatxu as xx ON sp.id_xuatxu = xx.id_xuatxu 
        JOIN hang as h ON sp.id_hang = h.id_hang 
        JOIN loai as l ON sp.id_loai = l.id_loai 
        LIMIT $offset, $items_per_load";
$result = $link->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Đếm tổng số sản phẩm
$total_sql = "SELECT COUNT(*) as total FROM dmsp";
$total_result = $link->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $items_per_load); // Tính tổng số trang

// Trả về dữ liệu dưới dạng JSON
echo json_encode([
    'products' => $products,
    'total_pages' => $total_pages
]);
?>

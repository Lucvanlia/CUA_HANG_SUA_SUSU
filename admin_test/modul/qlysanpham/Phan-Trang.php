<?php
include('ketnoi/conndb.php');

// Thiết lập số lượng sản phẩm trên mỗi trang
$limit = 4;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$start = ($page - 1) * $limit;

// Kiểm tra xem có từ khóa tìm kiếm không
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Tạo truy vấn SQL với từ khóa tìm kiếm nếu có
$sql = "SELECT sp.id_sp, sp.TenSP, h.TenHang, l.TenLoai, xx.TenXuatXu, sp.hinh
        FROM dmsp sp 
        JOIN hang h ON sp.id_hang = h.id_hang 
        JOIN loai l ON sp.id_loai = l.id_loai 
        JOIN xuatxu xx ON sp.id_xuatxu = xx.id_xuatxu
        WHERE sp.TenSP LIKE '%$query%'
        LIMIT $start, $limit";
$result_sp = mysqli_query($link, $sql);

// Đếm tổng số sản phẩm
$total_sql = "SELECT COUNT(*) AS total 
              FROM dmsp 
              WHERE TenSP LIKE '%$query%'";
$total_result = mysqli_query($link, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];

// Tạo HTML cho danh sách sản phẩm
$output = '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Hãng</th>
                    <th>Loại</th>
                    <th>Xuất xứ</th>
                    <th>Hình ảnh</th>
                </tr>
            </thead>
            <tbody>';
if (mysqli_num_rows($result_sp) > 0) {
    $stt = $start + 1;
    while ($row = mysqli_fetch_assoc($result_sp)) {
        $output .= "<tr>
                        <td>{$stt}</td>
                        <td>{$row['TenSP']}</td>
                        <td>{$row['TenHang']}</td>
                        <td>{$row['TenLoai']}</td>
                        <td>{$row['TenXuatXu']}</td>
                        <td>
                            <a href='uploads/{$row['HinhAnh']}' data-fancybox='gallery'>
                                <img src='uploads/{$row['HinhAnh']}' alt='Hình ảnh' style='width: 50px;'>
                            </a>
                        </td>
                    </tr>";
        $stt++;
    }
} else {
    $output .= "<tr><td colspan='6' class='text-center'>Không tìm thấy sản phẩm nào</td></tr>";
}
$output .= '</tbody></table>';

// Tạo phân trang
$total_pages = ceil($total_products / $limit);
$pagination = '';
if ($total_pages > 1) {
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination .= '<li class="page-item"><a href="#" class="page-link" data-page="'.$i.'">'.$i.'</a></li>';
    }
}

// Trả về kết quả dạng JSON
echo json_encode(['products' => $output, 'pagination' => $pagination]);
?>

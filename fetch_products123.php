<?php
include "admin_test/ketnoi/conndb.php";

$query = isset($_GET['query']) ? mysqli_real_escape_string($link, $_GET['query']) : '';
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$sortOrder = isset($_GET['SapXepGia']) ? $_GET['SapXepGia'] : 'default';

// Khởi tạo SQL cơ bản
$sql = "
    SELECT 
    SP.id_sp, 
    SP.Ten_sp, 
    SP.Hinh_Nen, 
    MIN(DG.GiaBan) AS GiaBan 
   FROM 
    SanPham SP
   JOIN 
    DonGia DG ON SP.id_sp = DG.id_sp
   WHERE 
    SP.HoatDong = 0 
";

// Nếu có từ khóa tìm kiếm
if (!empty($query)) {
    $sql .= " AND SP.Ten_sp LIKE '%$query%'";
}
else
{
    $sql = "
    SELECT 
    SP.id_sp, 
    SP.Ten_sp, 
    SP.Hinh_Nen, 
    MIN(DG.GiaBan) AS GiaBan 
   FROM 
    SanPham SP
   JOIN 
    DonGia DG ON SP.id_sp = DG.id_sp
   WHERE 
    SP.HoatDong = 0 
";
}

// Nếu có danh mục
if ($category_id > 0) {
    $sql .= " AND SP.id_dm = $category_id";
}

// Nếu có sắp xếp
if ($sortOrder == 'asc') {
    $sql .= " ORDER BY DG.GiaBan ASC";
} elseif ($sortOrder == 'desc') {
    $sql .= " ORDER BY DG.GiaBan DESC";
}

// Phân trang
$limit = 12; // Số sản phẩm mỗi trang
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$sql .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($link, $sql);

// Hiển thị sản phẩm
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
        echo '<div class="card shadow-sm h-100">';
        echo '<img src="admin_test/uploads/sanpham/' . $row['Hinh_Nen'] . '" class="card-img-top" style="height: 200px; object-fit: cover;" alt="' . $row['Ten_sp'] . '">';
        echo '<div class="card-body text-center">';
        echo '<h6 class="card-title">' . $row['Ten_sp'] . '</h6>';
        echo '<p class="text-danger fw-bold">' . number_format($row['GiaBan'], 0, ',', '.') . ' VND</p>';
        echo '   <button type="button" id="btnDetail" class="btn btn-success" data-id="' . $row['id_sp'] . '>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Mua ngay
                    </button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p class="text-center">Không tìm thấy sản phẩm nào.</p>';
}
?>

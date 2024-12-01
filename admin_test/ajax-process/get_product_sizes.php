<?php
include "../ketnoi/conndb.php";

if (isset($_GET['id_sp'])) {
    $id_sp = $_GET['id_sp'];

    // Truy vấn lấy các kích thước của sản phẩm
    $sql = "SELECT *, dg.id_dv as id_dv FROM DonGia dg ,DonVi dv WHERE dv.id_dv = dg.id_dv and id_sp = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_sp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $sizes = [];
    while ($size = mysqli_fetch_assoc($result)) {
        $sizes[] = $size;
    }

    echo json_encode($sizes);
}
?>

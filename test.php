<?php
// Kết nối cơ sở dữ liệu
include "admin_test/ketnoi/conndb.php";
$id_sp = isset($_POST['id_sp']) ? (int)$_POST['id_sp'] : 0;
if ($id_sp > 0) {
    // Truy vấn thông tin sản phẩm
    $sql_sp = "
        SELECT SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, DG.GiaBan
        FROM SanPham SP
        LEFT JOIN DonGia DG ON SP.id_sp = DG.id_sp
        WHERE SP.id_sp = $id_sp AND SP.HoatDong = 0
        LIMIT 1";
    $result_sp = mysqli_query($link, $sql_sp);
    $sp = mysqli_fetch_assoc($result_sp);

    // Truy vấn các đơn vị của sản phẩm
    $sql_donvi = "
        SELECT DV.Ten_dv, DG.GiaBan,DG.SoLuong,DG.id_dv
        FROM DonGia DG
        JOIN DonVi DV ON DG.id_dv = DV.id_dv
        WHERE DG.id_sp = $id_sp AND DG.HoatDong = 0";
    $result_donvi = mysqli_query($link, $sql_donvi);
    $donvi = [];
    while ($dv = mysqli_fetch_assoc($result_donvi)) {
        $donvi[] = $dv;
    }

    if ($sp) {
        // Trả về thông tin sản phẩm và danh sách đơn vị
        echo json_encode([
            'status' => 'success',
            'data' => [
                'Ten_sp' => $sp['Ten_sp'],
                'Hinh_Nen' => $sp['Hinh_Nen'],
                'GiaBan' => $sp['GiaBan'],
                'donvi' => $donvi // Đơn vị
            ]
        ]);
    } else {
        // Sản phẩm không tồn tại
        echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID sản phẩm không hợp lệ.']);
}
exit;
    // Trả về dữ liệu dưới dạng JSON


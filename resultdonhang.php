<?php
// Kết nối cơ sở dữ liệu
include"admin_test/ketnoi/conndb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    $response = [];

    if (empty($content)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập thông tin tìm kiếm!']);
        exit;
    }

    // Truy vấn đơn hàng dựa vào email, số điện thoại, hoặc mã đơn hàng
    $sql = "
        SELECT 
            HDB.id_hdb,
            HDB.TrangThai,
            HDB.ThanhToan,
            HDB.created_at,
            KH.Ten_kh,
            KH.Email_kh,
            KH.SDT_kh,
            SUM(CT.SoLuong * CT.DonGia) AS TongTien
        FROM HDB
        LEFT JOIN CT_HDB CT ON HDB.id_hdb = CT.id_hdb
        LEFT JOIN KhachHang KH ON HDB.id_kh = KH.id_kh
        WHERE KH.Email_kh = ? OR KH.SDT_kh = ? OR HDB.id_hdb = ?
        GROUP BY HDB.id_hdb";

    $stmt = $link->prepare($sql);
    $stmt->bind_param('sss', $content, $content, $content);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $response = ['status' => 'success', 'data' => $orders];
    } else {
        $response = ['status' => 'error', 'message' => 'Không tìm thấy đơn hàng nào!'];
    }

    echo json_encode($response);
}
?>

<?php
include "../ketnoi/conndb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case 'search':
            $keyword = isset($_POST['search']) ? mysqli_real_escape_string($link, $_POST['search']) : '';
            $query = "
                SELECT hdb.id_hdb, kh.Ten_kh, kh.Email_kh, hdb.TrangThai, hdb.ThanhToan, hdb.created_at
                FROM HDB hdb
                JOIN KhachHang kh ON hdb.id_kh = kh.id_kh
                WHERE kh.Ten_kh LIKE '%$keyword%' OR kh.Email_kh LIKE '%$keyword%'
                ORDER BY hdb.created_at DESC
            ";
            $result = mysqli_query($link, $query);

            $hdbList = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $hdbList[] = $row;
                }
            }

            echo json_encode(['data' => $hdbList, 'total' => count($hdbList), 'page' => 1, 'limit' => count($hdbList)]);
            exit;

        case 'load':
            $limit = 10; // Số hóa đơn mỗi trang
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $offset = ($page - 1) * $limit;

            // Tổng số hóa đơn
            $totalQuery = "SELECT COUNT(*) as total FROM HDB";
            $totalResult = mysqli_query($link, $totalQuery);
            $totalRow = mysqli_fetch_assoc($totalResult);
            $total = $totalRow['total'];

            // Lấy dữ liệu hóa đơn cho trang hiện tại
            $query = "
                SELECT hdb.id_hdb, kh.Ten_kh, kh.Email_kh, hdb.TrangThai, hdb.ThanhToan, hdb.created_at
                FROM HDB hdb
                JOIN KhachHang kh ON hdb.id_kh = kh.id_kh
                ORDER BY hdb.created_at DESC
                LIMIT $offset, $limit
            ";
            $result = mysqli_query($link, $query);

            $hdbList = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $hdbList[] = $row;
                }
            }

            echo json_encode(['data' => $hdbList, 'total' => $total, 'page' => $page, 'limit' => $limit]);
            exit;
        case 'getOrderDetails':
            $id_hdb = intval($_POST['id_hdb']);

            // Lấy thông tin hóa đơn
            $orderQuery = "SELECT * FROM HDB WHERE id_hdb = $id_hdb";
            $orderResult = mysqli_query($link, $orderQuery);
            $order = mysqli_fetch_assoc($orderResult);

            // Lấy thông tin chi tiết hóa đơn
            $itemsQuery = "SELECT ct.*, sp.Ten_sp  , dv.Ten_dv
                               FROM CT_HDB ct 
                               JOIN SanPham sp ON ct.id_sp = sp.id_sp 
                               Join Donvi dv on ct.id_dv = dv.id_dv
                               WHERE ct.id_hdb = $id_hdb";
            $itemsResult = mysqli_query($link, $itemsQuery);

            $items = [];
            while ($row = mysqli_fetch_assoc($itemsResult)) {
                $items[] = $row;
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'order' => $order,
                    'items' => $items,
                ],
            ]);
            exit;

        case 'updateOrderStatus':
            $id_hdb = $_POST['id_hdb'];
            $TrangThai =$_POST['TrangThai'];

            // Kiểm tra dữ liệu đầu vào
            if (empty($id_hdb)) {
                $response = [
                    'message' => 'Lỗi id',
                    'status' => 'error',
                ];                exit;
            }
            if (empty($TrangThai)) {
                $response = [
                    'message' => 'Lỗi trạng thái',
                    'status' => 'error',
                ];
                exit;
            }
            $updateQuery = "UPDATE HDB SET TrangThai = $TrangThai WHERE id_hdb = $id_hdb";
            $result = mysqli_query($link, $updateQuery);

            if ($result) {
                $response = [
                    'message' => 'Cập nhật đơn hàng thành công',
                    'status' => 'success',
                ];
            } else {
                $response = [
                    'message' => 'Không thể cập nhật trạng thái đơn hàng',
                    'status' => 'error',
                ];
            }

            echo json_encode($response);
            exit;

        default:
            echo json_encode(['error' => 'Invalid action.']);
            exit;
    }
}

<?php

include "../ketnoi/conndb.php";

header('Content-Type: application/json'); // Đặt header JSON
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['message' => 'Hành động không hợp lệ!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'details':
            $id_ctkm = intval($_POST['id_ctkm']);  // Lấy ID chương trình khuyến mãi

            if ($id_ctkm <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID chương trình khuyến mãi không hợp lệ.',
                ]);
                exit;
            }

            // Lấy thông tin chương trình khuyến mãi từ bảng ChuongTrinhKM
            $ctkmQuery = "SELECT * FROM ChuongTrinhKM WHERE id_ctkm = $id_ctkm";
            $ctkmResult = mysqli_query($link, $ctkmQuery);

            if ($ctkmResult && mysqli_num_rows($ctkmResult) > 0) {
                $ctkm = mysqli_fetch_assoc($ctkmResult);

                // Lấy thông tin sản phẩm khuyến mãi từ bảng KMSanPham
                $productsQuery = "SELECT kmsp.*, sp.Ten_sp
                                  FROM KMSanPham kmsp
                                  JOIN SanPham sp ON kmsp.id_sp = sp.id_sp
                                  WHERE kmsp.id_ctkm = $id_ctkm";
                $productsResult = mysqli_query($link, $productsQuery);

                $products = [];
                while ($row = mysqli_fetch_assoc($productsResult)) {
                    $products[] = $row;
                }

                // Lấy thông tin khuyến mãi hóa đơn từ bảng kmHoaDon
                $invoiceQuery = "SELECT kmhd.*, km.TenCTKM
                                 FROM kmHoaDon kmhd
                                 JOIN ChuongTrinhKM km ON kmhd.id_ctkm = km.id_ctkm
                                 WHERE kmhd.id_ctkm = $id_ctkm";
                $invoiceResult = mysqli_query($link, $invoiceQuery);

                $invoice = [];
                if ($invoiceResult && mysqli_num_rows($invoiceResult) > 0) {
                    $invoice = mysqli_fetch_assoc($invoiceResult);
                }

                // Trả về kết quả dưới dạng JSON
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'ctkm' => $ctkm,  // Thông tin chương trình khuyến mãi
                        'products' => $products,  // Danh sách sản phẩm khuyến mãi
                        'invoice' => $invoice,  // Thông tin khuyến mãi hóa đơn
                    ],
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy chương trình khuyến mãi với ID này.',
                ]);
            }

            exit;
            break;


        case 'toggleProductStatus':
            $id_sp = intval($_POST['id_sp']);
            $currentStatus = intval($_POST['status']);

            // Đảo trạng thái hoạt động
            $newStatus = $currentStatus ? 0 : 1;

            $query = mysqli_query($link, "UPDATE kmsanpham SET HoatDong = $newStatus WHERE id_kmsp = $id_sp");

            if ($query) {
                echo json_encode(['status' => 'success', 'message' => 'Trạng thái đã được cập nhật.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cập nhật trạng thái thất bại.']);
            }
            break;



        case 'toggle_status':
            $id_ncc = $_POST['id'] ?? 0;
            $newStatus = $_POST['status'] ?? 0;

            if ($id_ncc > 0) {
                // Truy vấn cập nhật trạng thái
                $query = "UPDATE ChuongTrinhKM  SET HoatDong = ? WHERE id_ctkm  = ?";
                $stmt = $link->prepare($query);
                $stmt->bind_param("ii", $newStatus, $id_ncc);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Cập nhật trạng thái thành công!';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Cập nhật trạng thái thất bại!';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'ID nhà cung cấp không hợp lệ!';
            }

            echo json_encode($response);
            exit;


            echo $html;
            exit;

        default:
            $response['message'] = 'Hành động không hợp lệ!';
            break;
    }
}

echo json_encode($response);
exit();

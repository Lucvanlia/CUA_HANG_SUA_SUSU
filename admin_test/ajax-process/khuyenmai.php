<?php

include "../ketnoi/conndb.php";

header('Content-Type: application/json'); // Đặt header JSON
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['message' => 'Hành động không hợp lệ!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $TenCTKM = $_POST['tenCTKM'];
            $MaKM = $_POST['MaKM'];  // Mã khuyến mãi đã được tạo tự động
            $NgayBatDau = $_POST['ngayBatDau'];
            $NgayKetThuc = $_POST['ngayKetThuc'];
            $hoatdong = 0;
            // Lưu vào bảng ChuongTrinhKM
            $query = "INSERT INTO ChuongTrinhKM (TenCTKM, MaKM, NgayBatDau, NgayKetThuc,HoatDong) 
                      VALUES ('$TenCTKM', '$MaKM', '$NgayBatDau', '$NgayKetThuc','$hoatdong')";
            
            if (mysqli_query($link, $query)) {
                $id_ctkm = mysqli_insert_id($link); // Lấy ID chương trình khuyến mãi vừa tạo
                
                // Lưu khuyến mãi sản phẩm (nếu có)
                if (isset($_POST['sanPham'])) {
                    $sanPham = $_POST['sanPham'];
                    $soLuong = $_POST['soLuong'];
                    $giamGiaSP = $_POST['giamGiaSP'];
                    foreach ($sanPham as $index => $id_sp) {
                        $soLuongSP = $soLuong[$index];
                        $giamGiaSPValue = $giamGiaSP[$index];
                        
                        $querySP = "INSERT INTO KMSanPham (id_ctkm, id_sp, GiamGia, SoLuongKhuyenMai,HoatDong) 
                                    VALUES ('$id_ctkm', '$id_sp', '$giamGiaSPValue', '$soLuongSP','$hoatdong')";
                        mysqli_query($link, $querySP);
                    }
                }
                
                // Lưu khuyến mãi hóa đơn (nếu có)
                if (isset($_POST['dieuKienHoaDon'])) {
                    $dieuKienHoaDon = $_POST['dieuKienHoaDon'];
                    $giamGiaHD = $_POST['giamGiaHD'];
                    foreach ($dieuKienHoaDon as $index => $dieukien) {
                        $giamGiaHoaDon = $giamGiaHD[$index];
                        
                        $queryHD = "INSERT INTO KMHoaDon (id_ctkm, DieuKienHoaDon, GiamGia) 
                                    VALUES ('$id_ctkm', '$dieukien', '$giamGiaHoaDon')";
                        mysqli_query($link, $queryHD);
                    }
                }
                
                echo json_encode(['status' => 'success', 'message' => 'Chương trình khuyến mãi đã được lưu!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi lưu chương trình!']);
            }
            exit;
            break;
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
                $productsQuery = "SELECT kmsp.*, sp.Ten_sp,kmsp.HoatDong as HoatDong
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
            $id_sp = intval($_POST['id_sp']); // ID sản phẩm
            $currentStatus = intval($_POST['status']); // Trạng thái hiện tại của sản phẩm (0 hoặc 1)
    
            // Đảo trạng thái hoạt động
            $newStatus = $currentStatus ? 0 : 1;
    
            // Cập nhật trạng thái vào cơ sở dữ liệu
            $query = "UPDATE kmsanpham SET HoatDong = $newStatus WHERE id_kmsp = $id_sp";
            $result = mysqli_query($link, $query);
    
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Trạng thái đã được cập nhật.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cập nhật trạng thái thất bại.']);
            }
            exit;
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

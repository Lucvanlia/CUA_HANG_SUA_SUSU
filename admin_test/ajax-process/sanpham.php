<?php
// Kết nối cơ sở dữ liệu
ini_set('display_errors', 1);
error_reporting(E_ALL);
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
            // Case 1: Load dữ liệu cần thiết
        case 'load_select_data':
            $response = ['status' => 'error', 'message' => '', 'danhmuc' => '', 'xuatxu' => '', 'nhacungcap' => '', 'donvi' => ''];

            // Load danh mục
            $queryDanhMuc = "SELECT id_dm, Ten_dm FROM DanhMuc where parent_dm != 0 ";
            $resultDanhMuc = $link->query($queryDanhMuc);
            if ($resultDanhMuc->num_rows > 0) {
                while ($row = $resultDanhMuc->fetch_assoc()) {
                    $response['danhmuc'] .= "<option value='{$row['id_dm']}'>{$row['Ten_dm']}</option>";
                }
            }

            // Load xuất xứ
            $queryXuatXu = "SELECT id_xx, Ten_xx FROM XuatXu WHERE Hoatdong = 0";
            $resultXuatXu = $link->query($queryXuatXu);
            if ($resultXuatXu->num_rows > 0) {
                while ($row = $resultXuatXu->fetch_assoc()) {
                    $response['xuatxu'] .= "<option value='{$row['id_xx']}'>{$row['Ten_xx']}</option>";
                }
            }

            // Load nhà cung cấp
            $queryNhaCungCap = "SELECT id_ncc, Ten_ncc FROM NhaCungCap WHERE Hoatdong = 0";
            $resultNhaCungCap = $link->query($queryNhaCungCap);
            if ($resultNhaCungCap->num_rows > 0) {
                while ($row = $resultNhaCungCap->fetch_assoc()) {
                    $response['nhacungcap'] .= "<option value='{$row['id_ncc']}'>{$row['Ten_ncc']}</option>";
                }
            }

            // Load đơn vị kích thước (Parent)
            $queryDonVi = "SELECT id_dv, Ten_dv FROM DonVi WHERE  parent_dv = 0";
            $resultDonVi = $link->query($queryDonVi);
            if ($resultDonVi->num_rows > 0) {
                while ($row = $resultDonVi->fetch_assoc()) {
                    $response['donvi'] .= "<option value='{$row['id_dv']}'>{$row['Ten_dv']}</option>";
                }
            }

            $response['status'] = 'success';
            echo json_encode($response);
            break;
        case 'load_sizes':
            $parentId = $_POST['parent_id'] ?? 0;

            if ($parentId > 0) {
                $query = "SELECT id_dv, Ten_dv FROM DonVi WHERE parent_dv = ? ";
                $stmt = $link->prepare($query);
                $stmt->bind_param("i", $parentId);
                $stmt->execute();
                $result = $stmt->get_result();

                $sizes = [];
                while ($row = $result->fetch_assoc()) {
                    $sizes[] = $row;
                }
                echo json_encode(['status' => 'success', 'data' => $sizes]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy kích thước con!']);
            }
            break;

            // Case 2: Thêm sản phẩm
        case 'add_product':
            //Lấy thông tin từ form
            // Lấy thông tin từ form
          
            break;
     
            // Case load kích thước chính
        case 'load_main_sizes':
            $query = "SELECT id_dv, Ten_dv FROM DonVi WHERE parent_dv = 0 ";
            $result = $link->query($query);
            $sizes = [];
            while ($row = $result->fetch_assoc()) {
                $sizes[] = $row;
            }
            echo json_encode(['status' => 'success', 'data' => $sizes]);
            break;

            // Case load kích thước con
        case 'load_sizes':
            $parentId = $_POST['parent_id'] ?? 0;
            if ($parentId > 0) {
                $query = "SELECT id_dv, Ten_dv FROM DonVi WHERE parent_dv = ?";
                $stmt = $link->prepare($query);
                $stmt->bind_param("i", $parentId);
                $stmt->execute();
                $result = $stmt->get_result();

                $sizes = [];
                while ($row = $result->fetch_assoc()) {
                    $sizes[] = $row;
                }
                echo json_encode(['status' => 'success', 'data' => $sizes]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy kích thước con']);
            }
            break;
            case 'load':
                // Số sản phẩm mỗi trang
                $sanPhamMoiTrang = 5;
            
                // Trang hiện tại
                $trangHienTai = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            
                // Tính offset
                $offset = ($trangHienTai - 1) * $sanPhamMoiTrang;
            
                // Lấy tổng số sản phẩm
                $tongSanPhamQuery = "SELECT COUNT(*) AS total FROM SanPham";
                $tongSanPhamResult = mysqli_query($link, $tongSanPhamQuery);
                $tongSanPhamRow = mysqli_fetch_assoc($tongSanPhamResult);
                $tongSanPham = (int)$tongSanPhamRow['total'];
            
                // Lấy danh sách sản phẩm cho trang hiện tại
                $query = "SELECT *,Dongia.GiaBan  as GiaBan FROM SanPham INNER JOIN DonGia On sanpham.id_sp = dongia.id_sp ORDER BY sanpham.id_sp ASC LIMIT $sanPhamMoiTrang OFFSET $offset";
                $result = mysqli_query($link, $query);
            
                $product = [];
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $product[] = $row;
                    }
                }
            
                // Gọi hàm hiển thị sản phẩm và phân trang
                $htmlProduct = hienThiProduct($product, $tongSanPham, $trangHienTai, $sanPhamMoiTrang);
            
                // Trả về dữ liệu cho AJAX
                echo json_encode([
                    'productsHtml' => $htmlProduct,
                    'paginationHtml' => createPagination($tongSanPham, $sanPhamMoiTrang, $trangHienTai)
                ]);
                break;
            
                break;
                case 'toggle_status':
                    $id_ncc = $_POST['id'] ?? 0;
                    $newStatus = $_POST['status'] ?? 0;
        
                    if ($id_ncc > 0) {
                        // Truy vấn cập nhật trạng thái
                        $query = "UPDATE Sanpham SET HoatDong = ? WHERE id_sp = ?";
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
            echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ.']);
            break;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
}
function hienThiProduct($danhSachProduct, $tongSanPham, $trangHienTai, $sanPhamMoiTrang) {
    if (empty($danhSachProduct)) {
        return '<tr><td colspan="4" class="text-center">Không có sản phẩm nào!</td></tr>';
    }

    $html = '';
    $stt = ($trangHienTai - 1) * $sanPhamMoiTrang + 1;

    foreach ($danhSachProduct as $ncc) {
        $id = htmlspecialchars($ncc['id_sp']);
        $name = htmlspecialchars($ncc['Ten_sp']);
        $hinh = htmlspecialchars($ncc['Hinh_Nen']);
        $Giaban = htmlspecialchars($ncc['GiaBan']);
        $status = $ncc['HoatDong']; // Trạng thái hoạt động
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';

        $html .= '
            <tr>
                <td>' . $stt . '</td>
                <td><span>' . $name . '</span></td>
                <td>
                    ' . (!empty($hinh)
            ? '<img src="uploads/sanpham/' . $hinh . '" style="width: 100px; height: 100px; object-fit: cover;" />'
            : 'Không có hình'
        ) . '
                </td>
                <td>' . number_format($Giaban,0,3) . '</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning btn-edit" 
                        data-id="' . $id . '" 
                        data-name="' . $name . '" 
                        data-hinh="' . $hinh . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm ' . $statusClass . ' btn-toggle-status" 
                        data-id="' . $id . '" 
                        data-status="' . $status . '">
                        <i class="fas ' . $iconClass . '"></i> ' . $statusText . '
                    </button>
                </td>
            </tr>';
        $stt++;
    }

    // Tính toán số trang
    $tongTrang = ceil($tongSanPham / $sanPhamMoiTrang);

    // Thêm phân trang
    $html .= '<nav><ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $tongTrang; $i++) {
        $activeClass = ($i == $trangHienTai) ? 'active' : '';
        $html .= '<li class="page-item ' . $activeClass . '">
                    <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                  </li>';
    }
    $html .= '</ul></nav>';

    return $html;
}
function createPagination($tongSanPham, $sanPhamMoiTrang, $trangHienTai) {
    $tongTrang = ceil($tongSanPham / $sanPhamMoiTrang);
    $html = '<ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $tongTrang; $i++) {
        $activeClass = ($i == $trangHienTai) ? 'active' : '';
        $html .= '<li class="page-item ' . $activeClass . '">
                    <a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a>
                  </li>';
    }
    $html .= '</ul>';
    return $html;
}

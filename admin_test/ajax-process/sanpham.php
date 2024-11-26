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
            $ten_sp = $_POST['Ten_sp'];
            $mo_ta = $_POST['MoTa_sp'];
            $id_dm = $_POST['id_dm'];
            $id_xx = $_POST['id_xx'];
            $id_ncc = $_POST['id_ncc'];

            // Xử lý hình nền
            if (isset($_FILES['Hinh_Nen']) && $_FILES['Hinh_Nen']['error'] === UPLOAD_ERR_OK) {
                $hinh_nen = $_FILES['Hinh_Nen']['name'];
                move_uploaded_file($_FILES['Hinh_Nen']['tmp_name'], 'uploads/' . $hinh_nen); // Lưu file vào thư mục uploads
            } else {
                $hinh_nen = ''; // Nếu không có hình nền
            }

            // Thêm sản phẩm vào cơ sở dữ liệu
            $sql = "INSERT INTO SanPham (Ten_sp, MoTa_sp, id_dm, id_xx, id_ncc, Hinh_Nen) 
                    VALUES ('$ten_sp', '$mo_ta', '$id_dm', '$id_xx', '$id_ncc', '$hinh_nen')";

            // Giả sử bạn đã kết nối tới cơ sở dữ liệu và thực thi câu SQL
            if (mysqli_query($link, $sql)) {
                $id_sp = mysqli_insert_id($link); // Lấy id của sản phẩm vừa thêm
                $id_sp = mysqli_insert_id($link); // Lấy ID của sản phẩm

                // Xử lý upload các ảnh chi tiết từ Dropzone
                if (isset($_FILES['hinh_chi_tiet'])) {
                    $images = [];
                    foreach ($_FILES['hinh_chi_tiet']['tmp_name'] as $key => $tmp_name) {
                        if ($_FILES['hinh_chi_tiet']['error'][$key] == 0) {
                            $file_name = $_FILES['hinh_chi_tiet']['name'][$key];
                            $file_tmp = $_FILES['hinh_chi_tiet']['tmp_name'][$key];
                            $target_file = $target_dir . basename($file_name);

                            if (move_uploaded_file($file_tmp, $target_file)) {
                                $images[] = $file_name; // Thêm tên file vào mảng
                            } else {
                                echo json_encode(['status' => 'error', 'message' => 'File ảnh']);
                                echo json_encode($response);
                                exit();
                            }
                        }
                    }

                    // Cập nhật các ảnh chi tiết vào cơ sở dữ liệu
                    if (!empty($images)) {
                        $images_string = implode(',', $images);
                        $update_sql = "UPDATE Sanpham SET Hinh_ChiTiet = '$images_string' WHERE id_sp = '$id_sp'";
                        if (!mysqli_query($link, $update_sql)) {
                            echo json_encode(['status' => 'error', 'message' => 'lỗi truy vấn thêm ảnh']);
                            echo json_encode($response);
                            exit();
                        }
                    }
                }
                else{
                    echo json_encode(['status' => 'error', 'message' => 'lỗi truy vấn thêm ảnh']);
                    exit();
                }
                // Thêm đơn giá vào bảng DonGia
                if (isset($_POST['sizes']['Size'])) {
                    foreach ($_POST['sizes']['Size'] as $key => $size) {
                        $giaNhap = $_POST['sizes']['GiaNhap'][$key];
                        $giaBan = $_POST['sizes']['GiaBan'][$key];
                        $khuyenMai = $_POST['sizes']['KhuyenMai_Fast'][$key];

                        // Chèn dữ liệu vào bảng DonGia
                        $sizeSql = "INSERT INTO DonGia (id_sp, GiaNhap, GiaBan, KhuyenMai_Fast) 
                                    VALUES ('$id_sp', '$giaNhap', '$giaBan', '$khuyenMai')";
                        mysqli_query($link, $sizeSql);
                    }
                }
                echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được thêm thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm sản phẩm']);
            }
            exit;
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
                // Lấy danh sách nhà cung cấp
                $query = "SELECT * FROM SanPham  ORDER BY id_sp ASC";
                $result = mysqli_query($link, $query);
    
                $product = [];
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $product[] = $row;
                    }
                }
                // Trả về HTML cho AJAX
                echo hienThiProduct($product);
                exit; // Ngăn không cho mã khác chạy tiếp
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
function hienThiProduct($danhSachProduct)
{
    if (empty($danhSachProduct)) {
        return '<tr><td colspan="4" class="text-center">Không có nhà cung cấp nào!</td></tr>';
    }

    $html = '';
    $stt = 1 ; 
    foreach ($danhSachProduct as $ncc) {
        $id = htmlspecialchars($ncc['id_sp']);
        $name = htmlspecialchars($ncc['Ten_sp']);
        $hinh = htmlspecialchars($ncc['Hinh_Nen']);
        $status = $ncc['HoatDong']; // Lấy trạng thái hoạt động của nhà cung cấp
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';

        $html .= '
            <tr>
                <td>' . $stt . '</td>
                <td>' . $name . '</td>
                <td>
                    ' . (!empty($hinh)
            ? '<img src="uploads/sanpham/' . $hinh . '" style="width: 100px; height: 100px; object-fit: cover;" />'
            : 'Không có hình'
        ) . '
                </td>
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
    return $html;
}
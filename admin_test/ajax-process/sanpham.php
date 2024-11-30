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
            if (isset($_POST['Ten_sp'])) {
                // Lấy dữ liệu từ form
                $ten_sp = $_POST['Ten_sp'];
                $mo_ta = $_POST['MoTa_sp'];
                $id_dm = $_POST['id_dm'];
                $id_xx = $_POST['id_xx'];
                $id_ncc = $_POST['id_ncc'];
                $Hinh_Nen = null; // Biến lưu ảnh đại diện

                // Kiểm tra và xử lý ảnh đại diện
                $target_dir = "../uploads/sanpham/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true); // Tạo thư mục nếu chưa có
                }

                if (isset($_FILES['Hinh_Nen']) && $_FILES['Hinh_Nen']['error'] === UPLOAD_ERR_OK) {
                    $file_name = getRandomStringRandomInt() . basename($_FILES['Hinh_Nen']['name']);
                    $target_file = $target_dir . $file_name;

                    // Di chuyển file ảnh vào thư mục
                    if (move_uploaded_file($_FILES['Hinh_Nen']['tmp_name'], $target_file)) {
                        $Hinh_Nen = $file_name;
                    } else {
                        $response['message'] = 'Không thể tải ảnh đại diện lên.';
                        echo json_encode($response);
                        exit;
                    }
                }

                // Thêm sản phẩm vào bảng `SanPham`
                $sql = "INSERT INTO SanPham (Ten_sp, MoTa_sp, id_dm, id_xx, id_ncc, Hinh_Nen, HoatDong) 
                        VALUES (?, ?, ?, ?, ?, ?, 0)"; // Hoạt động mặc định là 1 (hoạt động)
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, "ssiiis", $ten_sp, $mo_ta, $id_dm, $id_xx, $id_ncc, $Hinh_Nen);

                if (mysqli_stmt_execute($stmt)) {
                    $id_sp = mysqli_insert_id($link); // Lấy ID của sản phẩm vừa thêm

                    // Xử lý bảng `DonGia`
                    if (isset($_POST['sizes']['GiaBan']) && is_array($_POST['sizes']['GiaBan'])) {
                        // Tạo một mảng để lưu các kích thước đã xử lý
                        $checkedSizes = [];

                        foreach ($_POST['sizes']['GiaBan'] as $key => $giaBan) {
                            $soLuong = $_POST['sizes']['SoLuong'][$key] ?? 0;
                            $giaNhap = $_POST['sizes']['GiaNhap'][$key] ?? 0;
                            $khuyenMai = $_POST['sizes']['KhuyenMai_Fast'][$key] ?? 0;
                            $dv = $_POST['sizes']['child_dv'][$key] ?? 0;

                            // Kiểm tra trùng lặp kích thước
                            if (in_array($dv, $checkedSizes)) {
                                // Trả về thông báo lỗi nếu bị trùng
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Kích thước  đã bị trùng. Vui lòng nhập các kích thước khác nhau.'
                                ];
                                echo json_encode($response);
                                exit; // Kết thúc xử lý nếu phát hiện lỗi
                            }

                            // Nếu không trùng, thêm vào danh sách kiểm tra
                            $checkedSizes[] = $dv;

                            // Thêm thông tin vào bảng `DonGia`
                            $sizeSql = "INSERT INTO DonGia (id_sp, GiaNhap, GiaBan, KhuyenMai_Fast, SoLuong, HoatDong, id_dv)
                                        VALUES (?, ?, ?, ?, ?, 0, ?)";
                            $sizeStmt = mysqli_prepare($link, $sizeSql);
                            mysqli_stmt_bind_param($sizeStmt, "iddiii", $id_sp, $giaNhap, $giaBan, $khuyenMai, $soLuong, $dv);
                            mysqli_stmt_execute($sizeStmt);
                        }
                    }

                    // Xử lý ảnh chi tiết (nếu có)
                    if (isset($_FILES['files']) && count($_FILES['files']['name']) > 0) {
                        $images = [];
                        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
                            if ($_FILES['files']['error'][$key] == 0) {
                                $file_name = getRandomStringRandomInt() . basename($_FILES['files']['name'][$key]);
                                $target_file = $target_dir . $file_name;
                                if (move_uploaded_file($tmp_name, $target_file)) {
                                    $images[] = $file_name; // Lưu tên file vào mảng
                                }
                            }
                        }

                        // Cập nhật ảnh chi tiết vào bảng `SanPham`
                        if (!empty($images)) {
                            $images_string = implode(',', $images); // Nối tên các ảnh lại thành chuỗi
                            $update_sql = "UPDATE SanPham SET Hinh_ChiTiet = ? WHERE id_sp = ?";
                            $update_stmt = mysqli_prepare($link, $update_sql);
                            mysqli_stmt_bind_param($update_stmt, "si", $images_string, $id_sp);
                            mysqli_stmt_execute($update_stmt);
                        }
                    }

                    // Phản hồi thành công
                    $response['status'] = 'success';
                    $response['message'] = 'Sản phẩm đã được thêm thành công!';
                } else {
                    $response['message'] = 'Lỗi khi thêm sản phẩm vào cơ sở dữ liệu.';
                }
            } else {
                $response['message'] = 'Dữ liệu không hợp lệ.';
            }

            // Trả về JSON
            echo json_encode($response);
            exit();
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
            $sanPhamMoiTrang = 10;

            // Trang hiện tại
            $trangHienTai = isset($_POST['page']) ? (int)$_POST['page'] : 1;

            // Tính offset
            $offset = ($trangHienTai - 1) * $sanPhamMoiTrang;

            // Lấy tổng số sản phẩm
            $tongSanPhamQuery = "SELECT COUNT(DISTINCT sp.id_sp) AS total FROM SanPham sp";
            $tongSanPhamResult = mysqli_query($link, $tongSanPhamQuery);
            $tongSanPhamRow = mysqli_fetch_assoc($tongSanPhamResult);
            $tongSanPham = (int)$tongSanPhamRow['total'];

            // Lấy danh sách sản phẩm cho trang hiện tại
            $query = "
                    SELECT 
                        sp.id_sp, 
                        sp.Ten_sp, 
                        sp.Hinh_Nen, 
                        sp.HoatDong, 
                        GROUP_CONCAT(CONCAT(dg.id_dv, ':', dv.Ten_dv, '(', dg.GiaBan, ')') SEPARATOR ', ') AS Sizes,
                        MAX(dg.GiaBan) AS GiaBanMax
                    FROM SanPham sp
                    LEFT JOIN DonGia dg ON sp.id_sp = dg.id_sp
                    LEFT JOIN DonVi dv ON dg.id_dv = dv.id_dv
                    GROUP BY sp.id_sp
                    ORDER BY sp.id_sp ASC
                    LIMIT $sanPhamMoiTrang OFFSET $offset
                ";

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
        case 'chitiet':
            if (isset($_POST['id_sp']) && !empty($_POST['id_sp'])) {
                $id_sp = intval($_POST['id_sp']);

                // Thông tin cơ bản của sản phẩm
                $sql = "SELECT sp.*, dm.Ten_dm, xx.Ten_xx, ncc.Ten_ncc 
                            FROM SanPham sp
                            LEFT JOIN DanhMuc dm ON sp.id_dm = dm.id_dm
                            LEFT JOIN XuatXu xx ON sp.id_xx = xx.id_xx
                            LEFT JOIN NhaCungCap ncc ON sp.id_ncc = ncc.id_ncc
                            WHERE sp.id_sp = ?";
                $stmt = $link->prepare($sql);
                $stmt->bind_param("i", $id_sp);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();

                    // Chuyển đổi hình ảnh chi tiết từ JSON thành mảng
                    $product['Hinh_ChiTiet'] = !empty($product['Hinh_ChiTiet']) ? json_decode($product['Hinh_ChiTiet'], true) : [];

                    // Lấy danh sách các đơn giá (kích thước và giá) của sản phẩm
                    $sqlSize = "SELECT dg.*, dv.Ten_dv 
                                    FROM DonGia dg
                                    LEFT JOIN DonVi dv ON dg.id_dv = dv.id_dv
                                    WHERE dg.id_sp = ?";
                    $stmtSize = $link->prepare($sqlSize);
                    $stmtSize->bind_param("i", $id_sp);
                    $stmtSize->execute();
                    $resultSize = $stmtSize->get_result();
                    $sizes = [];
                    while ($row = $resultSize->fetch_assoc()) {
                        $sizes[] = $row;
                    }
                    $product['sizes'] = $sizes;

                    // Lấy thông tin các bạn liền kề (sản phẩm trước và sau theo id_sp)
                    $prevProduct = null;
                    $nextProduct = null;

                    // Sản phẩm trước
                    $sqlPrev = "SELECT id_sp, Ten_sp FROM SanPham WHERE id_sp < ? ORDER BY id_sp DESC LIMIT 1";
                    $stmtPrev = $link->prepare($sqlPrev);
                    $stmtPrev->bind_param("i", $id_sp);
                    $stmtPrev->execute();
                    $resultPrev = $stmtPrev->get_result();
                    if ($resultPrev->num_rows > 0) {
                        $prevProduct = $resultPrev->fetch_assoc();
                    }

                    // Sản phẩm sau
                    $sqlNext = "SELECT id_sp, Ten_sp FROM SanPham WHERE id_sp > ? ORDER BY id_sp ASC LIMIT 1";
                    $stmtNext = $link->prepare($sqlNext);
                    $stmtNext->bind_param("i", $id_sp);
                    $stmtNext->execute();
                    $resultNext = $stmtNext->get_result();
                    if ($resultNext->num_rows > 0) {
                        $nextProduct = $resultNext->fetch_assoc();
                    }

                    // Kết quả trả về
                    echo json_encode([
                        'status' => 'success',
                        'data' => [
                            'product' => $product,
                            'prevProduct' => $prevProduct,
                            'nextProduct' => $nextProduct,
                        ],
                    ]);
                    exit;
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID sản phẩm không hợp lệ.']);
                exit;
            }

            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ.']);
            break;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ.']);
}
function hienThiProduct($danhSachProduct, $tongSanPham, $trangHienTai, $sanPhamMoiTrang)
{
    if (empty($danhSachProduct)) {
        return '<tr><td colspan="5" class="text-center">Không có sản phẩm nào!</td></tr>';
    }

    $htmlRows = [];
    $stt = ($trangHienTai - 1) * $sanPhamMoiTrang + 1;

    foreach ($danhSachProduct as $product) {
        $id = htmlspecialchars($product['id_sp']);
        $name = htmlspecialchars($product['Ten_sp']);
        $hinh = $product['Hinh_Nen'] ? 'uploads/sanpham/' . htmlspecialchars($product['Hinh_Nen']) : '';
        $sizes = htmlspecialchars($product['Sizes']);
        $giaBanMax = number_format($product['GiaBanMax'], 0, ',', '.');
        $status = $product['HoatDong'];
        $statusText = $status == 1 ? 'OFF' : 'ON';
        $statusClass = $status == 1 ? 'btn-danger' : 'btn-success';
        $iconClass = $status == 1 ? 'fa-times' : 'fa-check';

        $htmlRows[] = '
            <tr>
                <td>' . $stt . '</td>
                <td><span>' . $name . '</span></td>
                <td>
                    ' . ($hinh ? '<img src="' . $hinh . '" style="width: 100px; height: 100px; object-fit: cover;" />' : 'Không có hình') . '
                </td>
                <td>' . $sizes . '</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning btn-edit" 
                        data-id="' . $id . '" 
                        data-name="' . $name . '" 
                        data-hinh="' . htmlspecialchars($product['Hinh_Nen']) . '">
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

    $html = implode('', $htmlRows);
    $html .= hienThiPhanTrang($tongSanPham, $trangHienTai, $sanPhamMoiTrang);

    return $html;
}


/**
 * Hàm hiển thị phân trang
 */
function hienThiPhanTrang($tongSanPham, $trangHienTai, $sanPhamMoiTrang)
{
    $tongTrang = ceil($tongSanPham / $sanPhamMoiTrang);
    if ($tongTrang <= 1) return '';

    $paginationHtml = '<nav><ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $tongTrang; $i++) {
        $activeClass = $i == $trangHienTai ? 'active' : '';
        $paginationHtml .= '<li class="page-item ' . $activeClass . '">
                                <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                            </li>';
    }
    $paginationHtml .= '</ul></nav>';

    return $paginationHtml;
}

function createPagination($tongSanPham, $sanPhamMoiTrang, $trangHienTai)
{
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
function getRandomStringRandomInt($length = 50)
{
    $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $pieces = [];
    $max = mb_strlen($stringSpace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $stringSpace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

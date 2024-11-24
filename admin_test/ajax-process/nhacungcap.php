<?php

// Kiểm tra phương thức AJAX
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'add': // Thêm nhà cung cấp
            $Ten_ncc = trim($_POST['Ten_ncc'] ?? ''); // Lấy tên nhà cung cấp
            $Hinh_ncc = '';

            // Kiểm tra nếu tên nhà cung cấp không để trống
            if (!empty($Ten_ncc)) {
                // Kiểm tra trùng lặp tên nhà cung cấp
                $queryCheck = "SELECT id_ncc FROM Nhacungcap WHERE Ten_ncc = ?";
                $stmt = $link->prepare($queryCheck);
                $stmt->bind_param("s", $Ten_ncc);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response = [
                        'message' => 'Tên nhà cung cấp đã tồn tại!',
                        'status' => 'error'
                    ];
                    echo json_encode($response);
                    exit();
                }

                // Xử lý upload hình ảnh
                if (isset($_FILES['Hinh_ncc']) && $_FILES['Hinh_ncc']['error'] == UPLOAD_ERR_OK) {
                    $uploadDir = '../uploads/nhacungcap/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true); // Tạo thư mục nếu chưa tồn tại
                    }

                    $fileTmp = $_FILES['Hinh_ncc']['tmp_name'];
                    $fileName = uniqid() . '-' . basename($_FILES['Hinh_ncc']['name']);
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($fileTmp, $filePath)) {
                        $Hinh_ncc = $fileName; // Lưu tên file vào database
                    } else {
                        $response = [
                            'message' => 'Không thể upload hình ảnh!',
                            'status' => 'error'
                        ];
                        echo json_encode($response);
                        exit();
                    }
                }

                // Thêm nhà cung cấp vào database
                $query = "INSERT INTO Nhacungcap (Ten_ncc, Hinh_ncc) VALUES (?, ?)";
                $stmt = $link->prepare($query);
                $stmt->bind_param("ss", $Ten_ncc, $Hinh_ncc);

                if ($stmt->execute()) {
                    $response = [
                        'message' => 'Thêm nhà cung cấp thành công!',
                        'status' => 'success'
                    ];
                } else {
                    $response = [
                        'message' => 'Lỗi khi thêm nhà cung cấp!',
                        'status' => 'error'
                    ];
                }
            } else {
                $response = [
                    'message' => 'Tên nhà cung cấp không được để trống!',
                    'status' => 'error'
                ];
            }
            break;

        case 'edit':
            $id_dv = $_POST['id_dv'] ?? 0;
            $Ten_dv = trim($_POST['Ten_dv'] ?? '');
            $parent_dv = $_POST['parent_dv'] ?? 0;

            if (empty($Ten_dv) || $id_dv <= 0) {
                $response = ['status' => 'error', 'message' => 'Dữ liệu không hợp lệ!'];
                break;
            }

            // Kiểm tra danh mục có tồn tại hay không
            $queryCheck = "SELECT * FROM DonVi WHERE id_dv = $id_dv";
            $resultCheck = mysqli_query($link, $queryCheck);
            if (!$resultCheck || mysqli_num_rows($resultCheck) == 0) {
                $response = ['status' => 'error', 'message' => 'Danh mục không tồn tại!'];
                break;
            }
            $currentCategory = mysqli_fetch_assoc($resultCheck);

            // Kiểm tra tên danh mục đã tồn tại
            $queryNameExist = "SELECT id_dv FROM DonVi WHERE Ten_dv = '$Ten_dv' AND id_dv != $id_dv";
            $resultNameExist = mysqli_query($link, $queryNameExist);
            if ($resultNameExist && mysqli_num_rows($resultNameExist) > 0) {
                $response = ['status' => 'error', 'message' => 'Tên danh mục đã tồn tại!'];
                break;
            }

            // Ràng buộc: Chuyển từ gốc sang con hoặc ngược lại
            if ($currentCategory['parent_dv'] == 0 && $parent_dv != 0) {
                $response = ['status' => 'error', 'message' => 'Danh mục gốc chỉ có thể thay đổi tên, không thể trở thành con danh mục khác!'];
                break;
            }

            if ($parent_dv == 0) {
                // Chuyển danh mục con thành danh mục gốc
                $queryUpdate = "UPDATE DonVi SET parent_dv = 0, Ten_dv = '$Ten_dv' WHERE id_dv = $id_dv";
                if (mysqli_query($link, $queryUpdate)) {
                    $response = ['status' => 'success', 'message' => 'Danh mục đã trở thành danh mục gốc!', 'html' => reloadDonVi($link)];
                } else {
                    $response = ['status' => 'error', 'message' => 'Không thể chuyển danh mục con thành danh mục gốc!'];
                }
                break;
            }

            // Kiểm tra vòng lặp khi chuyển danh mục
            function getChildCategories($link, $id)
            {
                $childIds = [];
                $query = "SELECT id_dv FROM DonVi WHERE parent_dv = $id";
                $result = mysqli_query($link, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $childIds[] = $row['id_dv'];
                        $childIds = array_merge($childIds, getChildCategories($link, $row['id_dv'])); // Đệ quy
                    }
                }
                return $childIds;
            }

            $childCategories = getChildCategories($link, $id_dv);
            if (in_array($parent_dv, $childCategories)) {
                $response = ['status' => 'error', 'message' => 'Không thể chuyển danh mục cha thành con của chính nó!'];
                break;
            }

            // Cập nhật danh mục nếu hợp lệ
            $queryUpdate = "UPDATE DonVi SET Ten_dv = '$Ten_dv', parent_dv = $parent_dv WHERE id_dv = $id_dv";
            if (mysqli_query($link, $queryUpdate)) {
                $response = ['status' => 'success', 'message' => 'Cập nhật danh mục thành công!', 'html' => reloadDonVi($link)];
            } else {
                $response = ['status' => 'error', 'message' => 'Lỗi khi cập nhật danh mục!'];
            }
            break;
        case 'delete':
            $id_dv = $_POST['id_dv'] ?? 0;

            if (!is_numeric($id_dv) || $id_dv <= 0) {
                $response = [
                    'message' => 'ID danh mục không hợp lệ!',
                    'status' => 'error',
                ];
                echo json_encode($response);
                exit; // Dừng thực thi mã
            }

            // Hàm đệ quy lấy danh mục con
            function getChildCategories($link, $parentId)
            {
                $childIds = [];
                $query = "SELECT id_dv FROM DonVi WHERE parent_dv = $parentId";
                $result = mysqli_query($link, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $childIds[] = $row['id_dv'];
                        $childIds = array_merge($childIds, getChildCategories($link, $row['id_dv']));
                    }
                }
                return $childIds;
            }

            // Lấy danh mục con
            $childCategories = getChildCategories($link, $id_dv);

            // Xóa danh mục con
            if (!empty($childCategories)) {
                $childIds = implode(',', $childCategories);
                $queryDeleteChildren = "DELETE FROM DonVi WHERE id_dv IN ($childIds)";
                if (!mysqli_query($link, $queryDeleteChildren)) {
                    $response = [
                        'message' => 'Lỗi khi xóa danh mục con!',
                        'status' => 'error',
                    ];
                    echo json_encode($response);
                    exit; // Dừng thực thi mã
                }
            }

            // Xóa danh mục cha
            $queryDeleteParent = "DELETE FROM DonVi WHERE id_dv = $id_dv";
            if (mysqli_query($link, $queryDeleteParent)) {
                $response = [
                    'message' => 'Xóa danh mục thành công!',
                    'status' => 'success',
                ];
            } else {
                $response = [
                    'message' => 'Lỗi khi xóa danh mục cha!',
                    'status' => 'error',
                ];
            }

            echo json_encode($response);
            exit; // Đảm bảo kết thúc mã tại đây


        case 'load':
            // Lấy danh sách nhà cung cấp
            $query = "SELECT * FROM NhaCungCap ORDER BY id_ncc ASC";
            $result = mysqli_query($link, $query);

            $NhaCungCap = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $NhaCungCap[] = $row;
                }
            }
            // Trả về HTML cho AJAX
            echo hienThiNhaCungCap($NhaCungCap);
            exit; // Ngăn không cho mã khác chạy tiếp
            break;

        case 'loadParent':
            $query = "SELECT id_dv, Ten_dv FROM DonVi ORDER BY parent_dv ASC, id_dv ASC";
            $result = mysqli_query($link, $query);

            $html = '<option value="0">Không có danh mục cha</option>'; // Mặc định

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $html .= '<option value="' . $row['id_dv'] . '">' . $row['Ten_dv'] . '</option>';
                }
            }

            echo $html;
            exit;
        default:
            $response['message'] = 'Hành động không hợp lệ!';
            break;
    }

    // Trả về phản hồi JSON
    echo json_encode($response);
    exit();
} else {
    $response['message'] = 'Hành động không hợp lệ!';
    echo json_encode($response);
}
function hienThiNhaCungCap($danhSachNCC)
{
    if (empty($danhSachNCC)) {
        return '<tr><td colspan="4" class="text-center">Không có nhà cung cấp nào!</td></tr>';
    }

    $html = '';
    foreach ($danhSachNCC as $ncc) {
        $id = htmlspecialchars($ncc['id_ncc']);
        $name = htmlspecialchars($ncc['Ten_ncc']);
        $hinh = htmlspecialchars($ncc['Hinh_ncc']);
        $status = $ncc['Hoatdong']; // Lấy trạng thái hoạt động của nhà cung cấp
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';

        $html .= '
            <tr>
                <td>' . $name . '</td>
                <td>
                    ' . (!empty($hinh)
            ? '<img src="uploads/nhacungcap/' . $hinh . '" style="width: 100px; height: 100px; object-fit: cover;" />'
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
    }
    return $html;
}

<?php

// Kiểm tra phương thức AJAX
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'add': // Thêm danh mục
            $Ten_dv = trim($_POST['Ten_dv'] ?? ''); // Loại bỏ khoảng trắng
            $parent_dv = $_POST['parent_dv'] ?? 0;
            $hoatdong = 0;
            if (!empty($Ten_dv)) {
                $query = "INSERT INTO DonVi (Ten_dv, parent_dv,Hoatdong) VALUES ('$Ten_dv', '$parent_dv','$hoatdong')";
                // Kiểm tra trùng lặp
                $queryCheck = "SELECT id_dv FROM DonVi WHERE Ten_dv = ?";
                $stmt = $link->prepare($queryCheck);
                $stmt->bind_param("s", $Ten_dv);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response['message'] = 'Tên danh mục đã tồn tại!';
                    $response['error'] = 'success';
                    echo json_encode($response);
                    exit();
                }
                if (mysqli_query($link, $query)) {
                    $response['message'] = 'Thêm danh mục thành công';
                    $response['status'] = 'success';
                } else {
                    $response['message'] = 'Lỗi khi thêm danh mục!';
                }
            } else {
                $response['message'] = 'Tên danh mục không được để trống!';
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

            case 'toggle_status':
                $id_xx = $_POST['id'] ?? 0;
                $newStatus = $_POST['status'] ?? 0;
    
                if ($id_xx > 0) {
                    // Truy vấn cập nhật trạng thái
                    $query = "UPDATE Donvi SET Hoatdong = ? WHERE id_dv = ?";
                    $stmt = $link->prepare($query);
                    $stmt->bind_param("ii", $newStatus, $id_xx);
    
                    if ($stmt->execute()) {
                        $response['status'] = 'success';
                        $response['message'] = 'Cập nhật trạng thái thành công!';
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'Cập nhật trạng thái thất bại!';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'ID xuất xứ không hợp lệ!';
                }
    
                echo json_encode($response);
                exit;
        case 'load':
            // Lấy danh sách danh mục
            $query = "SELECT * FROM DonVi ORDER BY parent_dv ASC, id_dv ASC";
            $result = mysqli_query($link, $query);

            $DonVi = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $DonVi[] = $row;
                }
            }
            // Trả về HTML cho AJAX
            echo hienThiDonVi($DonVi);
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

// Hàm tải lại danh sách danh mục
function reloadDonVi($link)
{
    $query = "SELECT * FROM DonVi ORDER BY parent_dv ASC, id_dv ASC";
    $result = mysqli_query($link, $query);

    $DonVi = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $DonVi[] = $row;
        }
    }

    return hienThiDonVi($DonVi);
}
function hienThiDonVi($DonVi, $parent = 0, $level = 0)
{
    $html = '';
    foreach ($DonVi as $dm) {
        $status = $dm['Hoatdong']; // Lấy trạng thái hoạt động của xuất xứ
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';
        if ($dm['parent_dv'] == $parent) {
            $prefix = str_repeat('|--->', $level);
            $icon = $level === 0 ? '<i class="fas fa-folder-open text-primary"></i>' : '';
            $html .= '<tr>';
            $html .= '<td> &nbsp;&nbsp;' . $icon . ' &nbsp;&nbsp;&nbsp;' . $prefix . $dm['Ten_dv'] . '</td>';
            $html .= '<td class="text-center">';
            $html .= '  <button class="btn btn-sm btn-warning btn-edit" 
                            data-id="' . $dm['id_dv'] . '" 
                            data-name="' . $dm['Ten_dv'] . '" 
                            data-parent="' . $dm['parent_dv'] . '">
                            <i class="fas fa-edit"></i>
                        </button>';
            $html .= '     <button class="btn btn-sm ' . $statusClass . ' btn-toggle-status" 
                        data-id="'. $dm['id_dv'] . '" 
                        data-status="' . $status . '">
                        <i class="fas ' . $iconClass . '"></i> ' . $statusText . '
                    </button>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= hienThiDonVi($DonVi, $dm['id_dv'], $level + 1); // Đệ quy
        }
    }
    return $html; // Trả về HTML
}

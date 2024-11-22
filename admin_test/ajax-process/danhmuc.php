<?php

// Kiểm tra phương thức AJAX
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'add': // Thêm danh mục
            $Ten_dm = trim($_POST['Ten_dm'] ?? ''); // Loại bỏ khoảng trắng
            $parent_dm = $_POST['parent_dm'] ?? 0;

            if (!empty($Ten_dm)) {
                $query = "INSERT INTO DanhMuc (Ten_dm, parent_dm) VALUES ('$Ten_dm', '$parent_dm')";
                // Kiểm tra trùng lặp
                $queryCheck = "SELECT id_dm FROM DanhMuc WHERE Ten_dm = ?";
                $stmt = $link->prepare($queryCheck);
                $stmt->bind_param("s", $Ten_dm);
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
                $id_dm = $_POST['id_dm'] ?? 0;
                $Ten_dm = trim($_POST['Ten_dm'] ?? '');
                $parent_dm = $_POST['parent_dm'] ?? 0;
            
                if (empty($Ten_dm) || $id_dm <= 0) {
                    $response = ['status' => 'error', 'message' => 'Dữ liệu không hợp lệ!'];
                    break;
                }
            
                // Kiểm tra danh mục có tồn tại hay không
                $queryCheck = "SELECT * FROM DanhMuc WHERE id_dm = $id_dm";
                $resultCheck = mysqli_query($link, $queryCheck);
                if (!$resultCheck || mysqli_num_rows($resultCheck) == 0) {
                    $response = ['status' => 'error', 'message' => 'Danh mục không tồn tại!'];
                    break;
                }
                $currentCategory = mysqli_fetch_assoc($resultCheck);
            
                // Kiểm tra tên danh mục đã tồn tại
                $queryNameExist = "SELECT id_dm FROM DanhMuc WHERE Ten_dm = '$Ten_dm' AND id_dm != $id_dm";
                $resultNameExist = mysqli_query($link, $queryNameExist);
                if ($resultNameExist && mysqli_num_rows($resultNameExist) > 0) {
                    $response = ['status' => 'error', 'message' => 'Tên danh mục đã tồn tại!'];
                    break;
                }
            
                // Ràng buộc: Chuyển từ gốc sang con hoặc ngược lại
                if ($currentCategory['parent_dm'] == 0 && $parent_dm != 0) {
                    $response = ['status' => 'error', 'message' => 'Danh mục gốc chỉ có thể thay đổi tên, không thể trở thành con danh mục khác!'];
                    break;
                }
            
                if ($parent_dm == 0) {
                    // Chuyển danh mục con thành danh mục gốc
                    $queryUpdate = "UPDATE DanhMuc SET parent_dm = 0, Ten_dm = '$Ten_dm' WHERE id_dm = $id_dm";
                    if (mysqli_query($link, $queryUpdate)) {
                        $response = ['status' => 'success', 'message' => 'Danh mục đã trở thành danh mục gốc!', 'html' => reloadDanhMuc($link)];
                    } else {
                        $response = ['status' => 'error', 'message' => 'Không thể chuyển danh mục con thành danh mục gốc!'];
                    }
                    break;
                }
            
                // Kiểm tra vòng lặp khi chuyển danh mục
                function getChildCategories($link, $id) {
                    $childIds = [];
                    $query = "SELECT id_dm FROM DanhMuc WHERE parent_dm = $id";
                    $result = mysqli_query($link, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $childIds[] = $row['id_dm'];
                            $childIds = array_merge($childIds, getChildCategories($link, $row['id_dm'])); // Đệ quy
                        }
                    }
                    return $childIds;
                }
            
                $childCategories = getChildCategories($link, $id_dm);
                if (in_array($parent_dm, $childCategories)) {
                    $response = ['status' => 'error', 'message' => 'Không thể chuyển danh mục cha thành con của chính nó!'];
                    break;
                }
            
                // Cập nhật danh mục nếu hợp lệ
                $queryUpdate = "UPDATE DanhMuc SET Ten_dm = '$Ten_dm', parent_dm = $parent_dm WHERE id_dm = $id_dm";
                if (mysqli_query($link, $queryUpdate)) {
                    $response = ['status' => 'success', 'message' => 'Cập nhật danh mục thành công!', 'html' => reloadDanhMuc($link)];
                } else {
                    $response = ['status' => 'error', 'message' => 'Lỗi khi cập nhật danh mục!'];
                }
                break;            
        case 'delete':
            $id_dm = $_POST['id_dm'] ?? 0;

            if (!is_numeric($id_dm) || $id_dm <= 0) {
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
                $query = "SELECT id_dm FROM DanhMuc WHERE parent_dm = $parentId";
                $result = mysqli_query($link, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $childIds[] = $row['id_dm'];
                        $childIds = array_merge($childIds, getChildCategories($link, $row['id_dm']));
                    }
                }
                return $childIds;
            }

            // Lấy danh mục con
            $childCategories = getChildCategories($link, $id_dm);

            // Xóa danh mục con
            if (!empty($childCategories)) {
                $childIds = implode(',', $childCategories);
                $queryDeleteChildren = "DELETE FROM DanhMuc WHERE id_dm IN ($childIds)";
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
            $queryDeleteParent = "DELETE FROM DanhMuc WHERE id_dm = $id_dm";
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
            // Lấy danh sách danh mục
            $query = "SELECT * FROM DanhMuc ORDER BY parent_dm ASC, id_dm ASC";
            $result = mysqli_query($link, $query);

            $danhMuc = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $danhMuc[] = $row;
                }
            }
            // Trả về HTML cho AJAX
            echo hienThiDanhMuc($danhMuc);
            exit; // Ngăn không cho mã khác chạy tiếp
            break;
        case 'loadParent':
            $query = "SELECT id_dm, Ten_dm FROM DanhMuc ORDER BY parent_dm ASC, id_dm ASC";
            $result = mysqli_query($link, $query);

            $html = '<option value="0">Không có danh mục cha</option>'; // Mặc định

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $html .= '<option value="' . $row['id_dm'] . '">' . $row['Ten_dm'] . '</option>';
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
function reloadDanhMuc($link)
{
    $query = "SELECT * FROM DanhMuc ORDER BY parent_dm ASC, id_dm ASC";
    $result = mysqli_query($link, $query);

    $danhMuc = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $danhMuc[] = $row;
        }
    }

    return hienThiDanhMuc($danhMuc);
}
function hienThiDanhMuc($danhMuc, $parent = 0, $level = 0)
{
    $html = '';
    foreach ($danhMuc as $dm) {
        if ($dm['parent_dm'] == $parent) {
            $prefix = str_repeat('|--->', $level);
            $icon = $level === 0 ? '<i class="fas fa-folder-open text-primary"></i>' : '';
            $html .= '<tr>';
            $html .= '<td> &nbsp;&nbsp;' . $icon . ' &nbsp;&nbsp;&nbsp;' . $prefix . $dm['Ten_dm'] . '</td>';
            $html .= '<td class="text-center">';
            $html .= '  <button class="btn btn-sm btn-warning btn-edit" 
                            data-id="' . $dm['id_dm'] . '" 
                            data-name="' . $dm['Ten_dm'] . '" 
                            data-parent="' . $dm['parent_dm'] . '">
                            <i class="fas fa-edit"></i>
                        </button>';
            $html .= '  <button class="btn btn-sm btn-danger btn-delete" 
                            data-id="' . $dm['id_dm'] . '">
                            <i class="fas fa-trash-alt"></i>
                        </button>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= hienThiDanhMuc($danhMuc, $dm['id_dm'], $level + 1); // Đệ quy
        }
    }
    return $html; // Trả về HTML
}

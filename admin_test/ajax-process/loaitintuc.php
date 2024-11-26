<?php

// Kiểm tra phương thức AJAX
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'add': // Thêm Loại tin tức
            $Ten_ltt = trim($_POST['Ten_ltt'] ?? ''); // Loại bỏ khoảng trắng
            $parent_ltt = $_POST['parent_ltt'] ?? 0;

            // Kiểm tra dữ liệu đầu vào
            if (empty($Ten_ltt)) {
                $response['message'] = 'Tên Loại tin tức không được để trống!';
                $response['status'] = 'error';
                echo json_encode($response);
                exit();
            }

            // Kiểm tra tên Loại tin tức có bị trùng không
            $queryCheck = "SELECT id_ltt FROM Loaitintuc WHERE Ten_ltt = ?";
            $stmt = $link->prepare($queryCheck);
            $stmt->bind_param("s", $Ten_ltt);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $response['message'] = 'Tên Loại tin tức đã tồn tại!';
                $response['status'] = 'error';
                echo json_encode($response);
                exit();
            }

            // Kiểm tra nếu có file hình ảnh
            $Hinh_ltt = null;
            if (isset($_FILES['Hinh_ltt']) && $_FILES['Hinh_ltt']['error'] == UPLOAD_ERR_OK) {
                $target_dir = "../uploads/"; // Thư mục lưu trữ ảnh
                $target_file = $target_dir . basename($_FILES['Hinh_ltt']['name']);

                // Kiểm tra và di chuyển file ảnh
                if (move_uploaded_file($_FILES['Hinh_ltt']['tmp_name'], $target_file)) {
                    $Hinh_ltt = $_FILES['Hinh_ltt']['name']; // Lưu tên file ảnh vào cơ sở dữ liệu
                } else {
                    $response['message'] = 'Lỗi khi upload ảnh!';
                    $response['status'] = 'error';
                    echo json_encode($response);
                    exit;
                }
            }
            // Thêm Loại tin tức vào cơ sở dữ liệu
            if ($Hinh_ltt) {
                // Nếu có ảnh
                $query = "INSERT INTO Loaitintuc (Ten_ltt, parent_ltt, Hinh_ltt) VALUES (?, ?, ?)";
                $stmt = $link->prepare($query);
                $stmt->bind_param("sis", $Ten_ltt, $parent_ltt, $Hinh_ltt);
            } else {
                // Nếu không có ảnh
                $query = "INSERT INTO Loaitintuc (Ten_ltt, parent_ltt) VALUES (?, ?)";
                $stmt = $link->prepare($query);
                $stmt->bind_param("si", $Ten_ltt, $parent_ltt);
            }

            if ($stmt->execute()) {
                $response['message'] = 'Thêm Loại tin tức thành công';
                $response['status'] = 'success';
            } else {
                $response['message'] = 'Lỗi khi thêm Loại tin tức!';
                $response['status'] = 'error';
            }
            break;

        case 'edit':
            $id_ltt = $_POST['id_ltt'] ?? 0;
            $Ten_ltt = trim($_POST['Ten_ltt'] ?? '');
            $parent_ltt = $_POST['parent_ltt'] ?? 0;

            if (empty($Ten_ltt) || $id_ltt <= 0) {
                $response = ['status' => 'error', 'message' => 'Dữ liệu không hợp lệ!'];
                break;
            }

            // Kiểm tra Loại tin tức có tồn tại hay không
            $queryCheck = "SELECT * FROM Loaitintuc WHERE id_ltt = $id_ltt";
            $resultCheck = mysqli_query($link, $queryCheck);
            if (!$resultCheck || mysqli_num_rows($resultCheck) == 0) {
                $response = ['status' => 'error', 'message' => 'Loại tin tức không tồn tại!'];
                break;
            }
            $currentCategory = mysqli_fetch_assoc($resultCheck);

            // Kiểm tra tên Loại tin tức đã tồn tại
            $queryNameExist = "SELECT id_ltt FROM Loaitintuc WHERE Ten_ltt = '$Ten_ltt' AND id_ltt != $id_ltt";
            $resultNameExist = mysqli_query($link, $queryNameExist);
            if ($resultNameExist && mysqli_num_rows($resultNameExist) > 0) {
                $response = ['status' => 'error', 'message' => 'Tên Loại tin tức đã tồn tại!'];
                break;
            }

            // Ràng buộc: Chuyển từ gốc sang con hoặc ngược lại
            if ($currentCategory['parent_ltt'] == 0 && $parent_ltt != 0) {
                $response = ['status' => 'error', 'message' => 'Loại tin tức gốc chỉ có thể thay đổi tên, không thể trở thành con Loại tin tức khác!'];
                break;
            }

            if ($parent_ltt == 0) {
                // Chuyển Loại tin tức con thành Loại tin tức gốc
                $queryUpdate = "UPDATE Loaitintuc SET parent_ltt = 0, Ten_ltt = '$Ten_ltt' WHERE id_ltt = $id_ltt";
                if (mysqli_query($link, $queryUpdate)) {
                    $response = ['status' => 'success', 'message' => 'Loại tin tức đã trở thành Loại tin tức gốc!', 'html' => reloadLoaitintuc($link)];
                } else {
                    $response = ['status' => 'error', 'message' => 'Không thể chuyển Loại tin tức con thành Loại tin tức gốc!'];
                }
                break;
            }

            // Kiểm tra vòng lặp khi chuyển Loại tin tức
            function getChildCategories($link, $id)
            {
                $childIds = [];
                $query = "SELECT id_ltt FROM Loaitintuc WHERE parent_ltt = $id";
                $result = mysqli_query($link, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $childIds[] = $row['id_ltt'];
                        $childIds = array_merge($childIds, getChildCategories($link, $row['id_ltt'])); // Đệ quy
                    }
                }
                return $childIds;
            }

            $childCategories = getChildCategories($link, $id_ltt);
            if (in_array($parent_ltt, $childCategories)) {
                $response = ['status' => 'error', 'message' => 'Không thể chuyển Loại tin tức cha thành con của chính nó!'];
                break;
            }

            // Cập nhật Loại tin tức nếu hợp lệ
            $queryUpdate = "UPDATE Loaitintuc SET Ten_ltt = '$Ten_ltt', parent_ltt = $parent_ltt WHERE id_ltt = $id_ltt";
            if (mysqli_query($link, $queryUpdate)) {
                $response = ['status' => 'success', 'message' => 'Cập nhật Loại tin tức thành công!', 'html' => reloadLoaitintuc($link)];
            } else {
                $response = ['status' => 'error', 'message' => 'Lỗi khi cập nhật Loại tin tức!'];
            }
            break;
        case 'delete':
            $id_ltt = $_POST['id_ltt'] ?? 0;

            if (!is_numeric($id_ltt) || $id_ltt <= 0) {
                $response = [
                    'message' => 'ID Loại tin tức không hợp lệ!',
                    'status' => 'error',
                ];
                echo json_encode($response);
                exit; // Dừng thực thi mã
            }
            case 'toggle_status':
                $id_xx = $_POST['id'] ?? 0;
                $newStatus = $_POST['status'] ?? 0;
    
                if ($id_xx > 0) {
                    // Truy vấn cập nhật trạng thái
                    $query = "UPDATE Loaitintuc SET Hoatdong = ? WHERE id_ltt = ?";
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

            // Hàm đệ quy lấy Loại tin tức con
            function getChildCategories($link, $parentId)
            {
                $childIds = [];
                $query = "SELECT id_ltt FROM Loaitintuc WHERE parent_ltt = $parentId";
                $result = mysqli_query($link, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $childIds[] = $row['id_ltt'];
                        $childIds = array_merge($childIds, getChildCategories($link, $row['id_ltt']));
                    }
                }
                return $childIds;
            }

            // Lấy Loại tin tức con
            $childCategories = getChildCategories($link, $id_ltt);

            // Xóa Loại tin tức con
            if (!empty($childCategories)) {
                $childIds = implode(',', $childCategories);
                $queryDeleteChildren = "DELETE FROM Loaitintuc WHERE id_ltt IN ($childIds)";
                if (!mysqli_query($link, $queryDeleteChildren)) {
                    $response = [
                        'message' => 'Lỗi khi xóa Loại tin tức con!',
                        'status' => 'error',
                    ];
                    echo json_encode($response);
                    exit; // Dừng thực thi mã
                }
            }

            // Xóa Loại tin tức cha
            $queryDeleteParent = "DELETE FROM Loaitintuc WHERE id_ltt = $id_ltt";
            if (mysqli_query($link, $queryDeleteParent)) {
                $response = [
                    'message' => 'Xóa Loại tin tức thành công!',
                    'status' => 'success',
                ];
            } else {
                $response = [
                    'message' => 'Lỗi khi xóa Loại tin tức cha!',
                    'status' => 'error',
                ];
            }

            echo json_encode($response);
            exit; // Đảm bảo kết thúc mã tại đây


        case 'load':
            // Lấy danh sách Loại tin tức
            $query = "SELECT * FROM Loaitintuc ORDER BY parent_ltt ASC, id_ltt ASC";
            $result = mysqli_query($link, $query);

            $Loaitintuc = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $Loaitintuc[] = $row;
                }
            }
            // Trả về HTML cho AJAX
            echo hienThiLoaitintuc($Loaitintuc);
            exit; // Ngăn không cho mã khác chạy tiếp
            break;
        case 'loadParent':
            $query = "SELECT id_ltt, Ten_ltt FROM Loaitintuc ORDER BY parent_ltt ASC, id_ltt ASC";
            $result = mysqli_query($link, $query);

            $html = '<option value="0">Không có Loại tin tức cha</option>'; // Mặc định

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $html .= '<option value="' . $row['id_ltt'] . '">' . $row['Ten_ltt'] . '</option>';
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

// Hàm tải lại danh sách Loại tin tức
function reloadLoaitintuc($link)
{
    $query = "SELECT * FROM Loaitintuc ORDER BY parent_ltt ASC, id_ltt ASC";
    $result = mysqli_query($link, $query);

    $Loaitintuc = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $Loaitintuc[] = $row;
        }
    }

    return hienThiLoaitintuc($Loaitintuc);
}
function hienThiLoaitintuc($Loaitintuc, $parent = 0, $level = 0)
{
    $html = '';
    foreach ($Loaitintuc as $dm) {
        if ($dm['parent_ltt'] == $parent) {
            $status = $dm['Hoatdong']; // Lấy trạng thái hoạt động của xuất xứ
            $statusText = ($status == 1) ? 'OFF' : 'ON';
            $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
            $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';
            $prefix = str_repeat('|--->', $level);
            $icon = $level === 0 ? '<i class="fas fa-folder-open text-primary"></i>' : '';
            $html .= '<tr>';
            $html .= '<td> &nbsp;&nbsp;' . $icon . ' &nbsp;&nbsp;&nbsp;' . $prefix . $dm['Ten_ltt'] . '</td>';
            $html .= '<td class="text-center">';
            $html .= '  <button class="btn btn-sm btn-warning btn-edit" 
                            data-id="' . $dm['id_ltt'] . '" 
                            data-name="' . $dm['Ten_ltt'] . '" 
                            data-parent="' . $dm['parent_ltt'] . '">
                            <i class="fas fa-edit"></i>
                        </button>';
            $html .= '     <button class="btn btn-sm ' . $statusClass . ' btn-toggle-status" 
                        data-id="' . $dm['id_dv'] . '" 
                        data-status="' . $status . '">
                        <i class="fas ' . $iconClass . '"></i> ' . $statusText . '
                    </button>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= hienThiLoaitintuc($Loaitintuc, $dm['id_ltt'], $level + 1); // Đệ quy
        }
    }
    return $html; // Trả về HTML
}

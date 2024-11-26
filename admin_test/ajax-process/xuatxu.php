<?php

// Kiểm tra phương thức AJAX
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'add': // Thêm xuất xứ
            $Ten_xx = trim($_POST['Ten_xx'] ?? ''); // Lấy tên xuất xứ

            // Kiểm tra nếu tên xuất xứ không để trống
            if (!empty($Ten_xx)) {
                // Kiểm tra trùng lặp tên xuất xứ
                $queryCheck = "SELECT id_xx FROM Xuatxu WHERE Ten_xx = ?";
                $stmt = $link->prepare($queryCheck);
                $stmt->bind_param("s", $Ten_xx);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response = [
                        'message' => 'Tên xuất xứ đã tồn tại!',
                        'status' => 'error'
                    ];
                    echo json_encode($response);
                    exit();
                }

                // Thêm xuất xứ vào database
                $hoatdong = 0;
                $query = "INSERT INTO Xuatxu (Ten_xx, Hoatdong) VALUES (?,?)";
                $stmt = $link->prepare($query);
                $stmt->bind_param("ss", $Ten_xx,  $hoatdong);

                if ($stmt->execute()) {
                    $response = [
                        'message' => 'Thêm xuất xứ thành công!',
                        'status' => 'success'
                    ];
                } else {
                    $response = [
                        'message' => 'Lỗi khi thêm xuất xứ!',
                        'status' => 'error'
                    ];
                }
            } else {
                $response = [
                    'message' => 'Tên xuất xứ không được để trống!',
                    'status' => 'error'
                ];
            }
            break;

        case 'edit':
            $id = $_POST['id_xx'] ?? 0;
            $Ten_xx = trim($_POST['Ten_xx'] ?? '');
            $response = ['status' => 'error', 'message' => ''];

            // Kiểm tra ID và tên xuất xứ hợp lệ
            if ($id <= 0 || empty($Ten_xx)) {
                $response['message'] = 'Dữ liệu không hợp lệ! ' . $id . '----' . $Ten_xx . ' ';
                echo json_encode($response);
                exit;
            }

            // Kiểm tra trùng lặp tên (loại trừ bản ghi hiện tại)
            $queryCheck = "SELECT id_xx FROM Xuatxu WHERE Ten_xx = ? AND id_xx != ?";
            $stmtCheck = $link->prepare($queryCheck);
            $stmtCheck->bind_param("si", $Ten_xx, $id);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                $response['message'] = 'Tên xuất xứ đã tồn tại!';
                echo json_encode($response);
                exit;
            }
            $query = "UPDATE Xuatxu SET Ten_xx = ? WHERE id_xx = ?";
            $stmt = $link->prepare($query);
            $stmt->bind_param('si', $Ten_xx, $id);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Cập nhật xuất xứ thành công!';
            } else {
                $response['message'] = 'Lỗi khi cập nhật xuất xứ!';
            }

            echo json_encode($response);
            exit;
            break;


        case 'load':
            // Lấy danh sách xuất xứ
            $query = "SELECT * FROM Xuatxu ORDER BY id_xx ASC";
            $result = mysqli_query($link, $query);

            $Xuatxu = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $Xuatxu[] = $row;
                }
            }
            // Trả về HTML cho AJAX
            echo hienThiXuatxu($Xuatxu);
            exit; // Ngăn không cho mã khác chạy tiếp
            break;

        case 'toggle_status':
            $id_xx = $_POST['id'] ?? 0;
            $newStatus = $_POST['status'] ?? 0;

            if ($id_xx > 0) {
                // Truy vấn cập nhật trạng thái
                $query = "UPDATE Xuatxu SET Hoatdong = ? WHERE id_xx = ?";
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
function hienThiXuatxu($danhSachNCC)
{
    if (empty($danhSachNCC)) {
        return '<tr><td colspan="4" class="text-center">Không có xuất xứ nào!</td></tr>';
    }

    $html = '';
    foreach ($danhSachNCC as $ncc) {
        $id = htmlspecialchars($ncc['id_xx']);
        $name = htmlspecialchars($ncc['Ten_xx']);
        $status = $ncc['Hoatdong']; // Lấy trạng thái hoạt động của xuất xứ
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';

        $html .= '
            <tr>
                <td>' . $name . '</td>
           
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning btn-edit" 
                        data-id="' . $id . '" 
                        data-name="' . $name . '" 
                            >
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

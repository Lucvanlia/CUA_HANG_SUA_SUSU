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
                $hoatdong = 0;
                $query = "INSERT INTO Nhacungcap (Ten_ncc, Hinh_ncc,Hoatdong) VALUES (?, ?,?)";
                $stmt = $link->prepare($query);
                $stmt->bind_param("sss", $Ten_ncc, $Hinh_ncc, $hoatdong);

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
            $id = $_POST['id_ncc'] ?? 0;
            $Ten_ncc = trim($_POST['Ten_ncc'] ?? '');
            $currentImage = $_POST['current_image'] ?? '';
            $response = ['status' => 'error', 'message' => ''];

            // Kiểm tra ID và tên nhà cung cấp hợp lệ
            if ($id <= 0 || empty($Ten_ncc)) {
                $response['message'] = 'Dữ liệu không hợp lệ! ' . $id . '----' . $Ten_ncc . ' ';
                echo json_encode($response);
                exit;
            }

            // Kiểm tra trùng lặp tên (loại trừ bản ghi hiện tại)
            $queryCheck = "SELECT id_ncc FROM NhaCungCap WHERE Ten_ncc = ? AND id_ncc != ?";
            $stmtCheck = $link->prepare($queryCheck);
            $stmtCheck->bind_param("si", $Ten_ncc, $id);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                $response['message'] = 'Tên nhà cung cấp đã tồn tại!';
                echo json_encode($response);
                exit;
            }

            // Xử lý hình ảnh
            $newImage = $_FILES['Hinh_ncc']['name'] ?? '';
            $uploadDir = '../uploads/nhacungcap/';
            $imagePath = $currentImage; // Giữ nguyên hình hiện tại nếu không thay đổi

            if (!empty($newImage)) {
                // Xóa hình cũ nếu tồn tại
                if (!empty($currentImage) && file_exists($uploadDir . $currentImage)) {
                    unlink($uploadDir . $currentImage);
                }

                // Tạo tên mới và di chuyển hình ảnh
                $imageExtension = pathinfo($newImage, PATHINFO_EXTENSION);
                $newImageName = 'ncc_' . time() . '.' . $imageExtension;
                $imagePath = $newImageName;

                if (!move_uploaded_file($_FILES['Hinh_ncc']['tmp_name'], $uploadDir . $newImageName)) {
                    $response['message'] = 'Lỗi khi upload hình!';
                    echo json_encode($response);
                    exit;
                }
            }

            // Cập nhật nhà cung cấp (chỉ cập nhật trường hình ảnh nếu có hình mới)
            $query = empty($newImage)
                ? "UPDATE NhaCungCap SET Ten_ncc = ? WHERE id_ncc = ?"
                : "UPDATE NhaCungCap SET Ten_ncc = ?, Hinh_ncc = ? WHERE id_ncc = ?";

            $stmt = $link->prepare($query);

            if (empty($newImage)) {
                $stmt->bind_param('si', $Ten_ncc, $id);
            } else {
                $stmt->bind_param('ssi', $Ten_ncc, $imagePath, $id);
            }

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Cập nhật nhà cung cấp thành công!';
            } else {
                $response['message'] = 'Lỗi khi cập nhật nhà cung cấp!';
            }

            echo json_encode($response);
            exit;
            break;


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

        case 'toggle_status':
            $id_ncc = $_POST['id'] ?? 0;
            $newStatus = $_POST['status'] ?? 0;

            if ($id_ncc > 0) {
                // Truy vấn cập nhật trạng thái
                $query = "UPDATE NhaCungCap SET Hoatdong = ? WHERE id_ncc = ?";
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
            ? '<img src="uploads/nhacungcap/' . $hinh . '" style="width: 100%; height: 100px; object-fit: cover;" />'
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

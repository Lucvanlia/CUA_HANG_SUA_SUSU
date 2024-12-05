<?php

// Kiểm tra phương thức AJAX
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'add': // Thêm nhà cung cấp
            $name = isset($_POST['name']) ? mysqli_real_escape_string($link, $_POST['name']) : '';
            $email = isset($_POST['email']) ? mysqli_real_escape_string($link, $_POST['email']) : '';
            $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
            $phone = isset($_POST['phone']) ? mysqli_real_escape_string($link, $_POST['phone']) : '';

            // Tạo mật khẩu ngẫu nhiên
            // $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
            $password="123";
            $hashedPassword = hash('sha256', $password);
            $token = bin2hex(random_bytes(50)); // 50 byte x 2 = 100 ký tự
            // Thêm người dùng vào cơ sở dữ liệu
            // Kiểm tra nếu tên nhà cung cấp không để trống
            if (!empty($email)) {
                // Kiểm tra trùng lặp tên nhà cung cấp
                $queryCheck = "SELECT  Email_nv  FROM Nhanvien WHERE Email_nv = ?";
                $stmt = $link->prepare($queryCheck);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response = [
                        'message' => 'Địa chỉ eamil này đã được sử dụng',
                        'status' => 'error'
                    ];
                    echo json_encode($response);
                    exit();
                }
                $queryCheck = "SELECT  SDT_nv  FROM Nhanvien WHERE SDT_nv = ?";
                $stmt = $link->prepare($queryCheck);
                $stmt->bind_param("s", $phone);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response = [
                        'message' => 'Số điện thoại này đã được sử dụng',
                        'status' => 'error'
                    ];
                    echo json_encode($response);
                    exit();
                }
                $queryCheck = "SELECT  SDT_nv  FROM Nhanvien WHERE Email_nv = ?";
                $stmt = $link->prepare($queryCheck);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $response = [
                        'message' => 'Email này đã được sử dụng',
                        'status' => 'error'
                    ];
                    echo json_encode($response);
                    exit();
                }
                // Thêm nhân viênvào database
                $hoatdong = 0;
                $query = "INSERT INTO Nhanvien (Ten_nv, Email_nv,NgaySinh_nv,Mk_nv,Hoatdong,SDT_nv) VALUES (?, ?, ?,  ?,?,?)";
                $stmt = $link->prepare($query);
                $stmt->bind_param("ssssis", $name, $email, $dob, $hashedPassword, $hoatdong, $phone);

                if ($stmt->execute()) {
                    $response = [
                        'message' => 'Thêm nhân viên thành công!'.$password,
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
            $id_nv = isset($_POST['id_nv']) ? mysqli_real_escape_string($link, $_POST['id_nv']) : '';
            $name = isset($_POST['name']) ? mysqli_real_escape_string($link, $_POST['name']) : '';
            $email = isset($_POST['email']) ? mysqli_real_escape_string($link, $_POST['email']) : '';
            $phone = isset($_POST['phone']) ? mysqli_real_escape_string($link, $_POST['phone']) : '';
            $dob = isset($_POST['dob']) ? mysqli_real_escape_string($link, $_POST['dob']) : '';
            $password = isset($_POST['password']) ? mysqli_real_escape_string($link, $_POST['password']) : '';

            // Kiểm tra tên, email hoặc số điện thoại trùng
            $query_check = "SELECT * FROM Nhanvien WHERE (Ten_nv = '$name' OR Email_nv = '$email' OR SDT_nv = '$phone') AND id_nv != '$id_nv'";
            $result_check = mysqli_query($link, $query_check);

            if ($result_check && mysqli_num_rows($result_check) > 0) {
                // Trả về lỗi nếu có tài khoản trùng lặp
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tên, email hoặc số điện thoại đã được sử dụng bởi tài khoản khác.'.$id_nv. '123',
                ]);
                exit;
            }

            // Xử lý cập nhật thông tin
            if (empty($password)) {
                $query_update = "UPDATE Nhanvien SET Ten_nv = '$name', Email_nv = '$email', NgaySinh_nv = '$dob', SDT_nv = '$phone' WHERE id_nv = '$id_nv'";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $query_update = "UPDATE Nhanvien SET Ten_nv = '$name', Email_nv = '$email', NgaySinh_nv = '$dob', SDT_nv = '$phone', Mk_nv = '$hashedPassword' WHERE id_nv = '$id_nv'";
            }

            if (mysqli_query($link, $query_update)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Cập nhật thông tin nhân viên thành công.',
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Cập nhật thông tin nhân viên thất bại.',
                ]);
            }
            exit;
            break;

        case 'get_customer':
            $id_nv = isset($_POST['id_nv']) ? (int)$_POST['id_nv'] : 0;
            if ($id_nv > 0) {
                $query = "SELECT * FROM Nhanvien WHERE id_nv = $id_nv";
                $result = mysqli_query($link, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $customer = mysqli_fetch_assoc($result);
                    echo json_encode($customer);
                } else {
                    echo json_encode(null);
                }
            }
            exit;
            break;

        case 'search':
            $keyword = isset($_POST['keyword']) ? mysqli_real_escape_string($link, $_POST['keyword']) : '';
            $query = "SELECT id_nv, Ten_nv, Email_nv, NgaySinh_nv,  created_at,HoatDong FROM Nhanvien 
                          WHERE Ten_nv LIKE '%$keyword%' OR Email_nv LIKE '%$keyword%'";
            $result = mysqli_query($link, $query);

            $nvachhangList = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $nvachhangList[] = $row;
                }
            }

            echo hienThiNhanvien($nvachhangList, 1, count($nvachhangList), count($nvachhangList));
            exit;

        case 'load':
            $limit = 10; // Số nhân viên mỗi trang
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $offset = ($page - 1) * $limit;

            // Lấy tổng số nhân viên
            $totalQuery = "SELECT COUNT(*) as total FROM Nhanvien";
            $totalResult = mysqli_query($link, $totalQuery);
            $totalRow = mysqli_fetch_assoc($totalResult);
            $total = $totalRow['total'];

            // Lấy dữ liệu nhân viên cho trang hiện tại
            $query = "SELECT id_nv, Ten_nv, Email_nv, NgaySinh_nv, created_at,HoatDong FROM Nhanvien LIMIT $offset, $limit";
            $result = mysqli_query($link, $query);

            $nvachhangList = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $nvachhangList[] = $row;
                }
            }

            // Tạo HTML hiển thị
            $html = hienThiNhanvien($nvachhangList, $page, $total, $limit);
            echo $html;
            exit;


        case 'toggle_status':
            $id_ncc = $_POST['id'] ?? 0;
            $newStatus = $_POST['status'] ?? 0;

            if ($id_ncc > 0) {
                // Truy vấn cập nhật trạng thái
                $query = "UPDATE Nhanvien SET Hoatdong = ? WHERE id_nv = ?";
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


function hienThiNhanvien($nvachhangList, $page, $total, $limit)
{
    $html = '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Ngày sinh</th>
                        <th>Ngày tạo</th>
                        <th>Chỉnh sửa</th>
                        <th>Hoạt động</th>
                    </tr>
                </thead>
                <tbody>';
    $index = ($page - 1) * $limit + 1;
    if (empty($nvachhangList)) {
        return '<p>Không có nhân viên</p>';
    }
    foreach ($nvachhangList as $nv) {
        $status = $nv['HoatDong'];
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';

        $html .= "<tr>
                    <td>{$index}</td>
                    <td>{$nv['Ten_nv']}</td>
                    <td>{$nv['Email_nv']}</td>
                    <td>{$nv['NgaySinh_nv']}</td>
                    <td>{$nv['created_at']}</td>
                    <td>
                    <button class='btn btn-sm btn-warning btn-edit' 
                        data-id='{$nv['id_nv']}'>
                        <i class='fas fa-edit'></i>
                    </button>
                    </td>
                    <td>
                        <button class='btn btn-sm {$statusClass} btn-toggle-status' 
                                data-id='{$nv['id_nv']}' 
                                data-status='{$status}'>
                            <i class='fas {$iconClass}'></i> {$statusText}
                        </button>
                    </td>
                  </tr>";
        $index++;
    }
    $html .= '</tbody></table>';

    // Phân trang
    $totalPages = ceil($total / $limit);
    $html .= '<nav><ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'active' : '';
        $html .= "<li class='page-item {$active}'><a href='#' class='page-link' data-page='{$i}'>{$i}</a></li>";
    }
    $html .= '</ul></nav>';

    return $html;
}

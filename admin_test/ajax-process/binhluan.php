<?php

// Kiểm tra phương thức AJAX
include "../ketnoi/conndb.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] ?? '';
    switch ($action) {


        case 'get_customer':
            $id_kh = isset($_POST['id_kh']) ? (int)$_POST['id_kh'] : 0;
            if ($id_kh > 0) {
                $query = "SELECT * FROM binhluan WHERE id_kh = $id_kh";
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
            $query = "SELECT id_kh, Ten_kh, Email_kh, NgaySinh_kh, HoatDong FROM binhluan 
                          WHERE Ten_kh LIKE '%$keyword%' OR Email_kh LIKE '%$keyword%'";
            $result = mysqli_query($link, $query);

            $binhluanList = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $binhluanList[] = $row;
                }
            }

            echo hienThibinhluan($binhluanList, 1, count($binhluanList), count($binhluanList));
            exit;

        case 'load':
            $limit = 10; // Số khách hàng mỗi trang
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $offset = ($page - 1) * $limit;

            // Lấy tổng số khách hàng
            $totalQuery = "SELECT COUNT(*) as total FROM binhluan";
            $totalResult = mysqli_query($link, $totalQuery);
            $totalRow = mysqli_fetch_assoc($totalResult);
            $total = $totalRow['total'];

            // Lấy dữ liệu khách hàng cho trang hiện tại
            $query = "SELECT kh.Ten_kh, sp.Ten_sp, bl.NoiDung, bl.rating, bl.Hinh_BL, bl.created_at,bl.HoatDong ,bl.id_bl 
            FROM binhluan bl 
            INNER JOIN khachhang kh ON bl.id_kh = kh.id_kh 
            INNER JOIN Sanpham sp ON bl.id_sp = sp.id_sp 
            GROUP BY kh.Ten_kh, sp.Ten_sp, bl.NoiDung, bl.rating, bl.Hinh_BL, bl.created_at 
            ORDER BY bl.created_at 
            LIMIT $offset, $limit";
            $result = mysqli_query($link, $query);

            $binhluanList = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $binhluanList[] = $row;
                }
            }

            // Tạo HTML hiển thị
            $html = hienThibinhluan($binhluanList, $page, $total, $limit);
            echo $html;
            exit;


        case 'toggle_status':
            $id_ncc = $_POST['id'] ?? 0;
            $newStatus = $_POST['status'] ?? 0;

            if ($id_ncc > 0) {
                // Truy vấn cập nhật trạng thái
                $query = "UPDATE binhluan SET Hoatdong = ? WHERE id_bl = ?";
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
                $response['message'] = 'ID bình luận không hợp lệ!';
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


function hienThibinhluan($binhluanList, $page, $total, $limit)
{
    $html = '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên sản phẩm</th>
                        <th>Tên khách hàng</th>
                        <th>Nội dung</th>
                        <th>Đánh giá</th>
                        <th>Hoạt động</th>
                    </tr>
                </thead>
                <tbody>';
    $index = ($page - 1) * $limit + 1;
    if (empty($binhluanList)) {
        return '<p>Không có khách hàng</p>';
    }
    $index = ($page - 1) * $limit + 1;
    if (empty($binhluanList)) {
        return '<p>Không có khách hàng</p>';
    }
    foreach ($binhluanList as $kh) {
        $status = $kh['HoatDong'];
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';
    
        // Hiển thị rating (ngôi sao)
        $rating = $kh['rating']; // Giả sử rating là một số từ 1 đến 5
        $stars = ''; // Biến chứa các sao
    
        // Tạo 5 ngôi sao
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= "<i class='fas fa-star text-warning'></i>"; // Sao đầy (được chọn), màu vàng
            } else {
                $stars .= "<i class='fas fa-stasa ' ></i>"; // Sao rỗng
            }
        }
    
        $html .= "<tr>
                    <td>{$index}</td>
                    <td>{$kh['Ten_sp']}</td>
                    <td>{$kh['Ten_kh']}</td>
                    <td>{$kh['NoiDung']}</td>
                    <td>{$stars}</td> <!-- Hiển thị rating bằng sao -->
                    <td>
                        <button class='btn btn-sm {$statusClass} btn-toggle-status' 
                                data-id='{$kh['id_bl']}' 
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

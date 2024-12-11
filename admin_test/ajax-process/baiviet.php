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
            $limit = 10; // Số bản ghi mỗi trang
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $offset = ($page - 1) * $limit;
            $query = "
            SELECT 
                tt.*, 
                GROUP_CONCAT(sp.Ten_sp SEPARATOR ', ') as TenSanPham
            FROM TinTuc tt
            LEFT JOIN Sanpham sp 
                ON FIND_IN_SET(sp.id_sp, tt.tag_sp)
                   WHERE Title LIKE '%$keyword%' OR NoiDung LIKE '%$keyword%' OR created_at LIKE '%$keyword%' 
            GROUP BY tt.id_tt
            ORDER BY tt.created_at DESC
            LIMIT $offset, $limit";

            // $query = "SELECT id_kh, Ten_kh, Email_kh, NgaySinh_kh, HoatDong FROM binhluan 
            //               WHERE Ten_kh LIKE '%$keyword%' OR Email_kh LIKE '%$keyword%'";
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
            $limit = 10; // Số bản ghi mỗi trang
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $offset = ($page - 1) * $limit;

            // Lấy tổng số bản ghi
            $totalQuery = "SELECT COUNT(*) as total FROM TinTuc";
            $totalResult = mysqli_query($link, $totalQuery);
            $totalRow = mysqli_fetch_assoc($totalResult);
            $total = $totalRow['total'];

            // Lấy dữ liệu bài viết và tên sản phẩm liên quan
            $query = "
                    SELECT 
                        tt.*, 
                        GROUP_CONCAT(sp.Ten_sp SEPARATOR ', ') as TenSanPham
                    FROM TinTuc tt
                    LEFT JOIN Sanpham sp 
                        ON FIND_IN_SET(sp.id_sp, tt.tag_sp)
                    GROUP BY tt.id_tt
                    ORDER BY tt.created_at DESC
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
                $query = "UPDATE tintuc SET Hoatdong = ? WHERE id_tt = ?";
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
                        <th>Tiêu đề</th>
                        <th>Sản Phẩm Liên Quan</th>
                        <th>Ngày tạo</th>
                        <th>Hoạt động</th>
                    </tr>
                </thead>
                <tbody>';
    $index = ($page - 1) * $limit + 1;
    if (empty($binhluanList)) {
        return '<p>Không có dữ liệu</p>';
    }
    foreach ($binhluanList as $kh) {
        $status = $kh['HoatDong'];
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';

        // Tách danh sách tên sản phẩm thành mảng
        $tenSanPhamList = explode(', ', $kh['TenSanPham']);
        $tenSanPhamHTML = '<ul class="list-group">';
        foreach ($tenSanPhamList as $tenSanPham) {
            $tenSanPhamHTML .= "<li class='list-group-item'>{$tenSanPham}</li>";
        }
        $tenSanPhamHTML .= '</ul>';

        $html .= "<tr>
                    <td>{$index}</td>
                    <td> <a href='http://localhost/doan_php/admin_test/modul/qlybaiviet/chitiet.php?id={$kh['id_tt']}'> {$kh['Title']}</a></td>
                    <td>{$tenSanPhamHTML}</td>
                    <td>{$kh['created_at']}</td>
                    <td>
                        <button class='btn btn-sm {$statusClass} btn-toggle-status' 
                                data-id='{$kh['id_tt']}' 
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

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài viết</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4/dist/fancybox.css" />
    <style>
        /* Giảm kích thước cột nội dung */
        .table td:nth-child(5) {
            max-width: 200px; /* Điều chỉnh kích thước cột nội dung */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Danh sách bài viết</h2>

    <!-- Ô tìm kiếm -->
    <div class="form-group">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm bài viết...">
    </div>
    
    <!-- Bảng danh sách bài viết -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>ID Sản phẩm</th>
                <th>Loại tin</th>
                <th>Tiêu đề</th>
                <th>Nội dung</th>
                <th>Hình ảnh</th>
            </tr>
        </thead>
        <tbody id="newsList">
            <!-- Bài viết mẫu lấy từ CSDL -->
            <?php
            // Kết nối CSDL và truy vấn dữ liệu
            $query = "SELECT tt.id_tt, tt.id_sp, ltt.Ten_ltt, tt.Title, tt.NoiDung, tt.HinhAnh 
                      FROM tintuc tt 
                      JOIN loaitintuc ltt ON tt.id_ltt = ltt.id_ltt";
            $result = $link->query($query);
            if ($result->num_rows > 0) {
                $stt = 1;
                while ($row = $result->fetch_assoc()) {
                    // Giới hạn nội dung hiển thị 50 từ
                    $noidung = explode(' ', $row['NoiDung']);
                    if (count($noidung) > 50) {
                        $noidung = array_slice($noidung, 0, 50);
                        $noidung = implode(' ', $noidung) . '...';
                    } else {
                        $noidung = $row['NoiDung'];
                    }
                    
                    echo "<tr>
                            <td>{$stt}</td>
                            <td>{$row['id_sp']}</td>
                            <td>{$row['Ten_ltt']}</td>
                            <td>{$row['Title']}</td>
                            <td>{$noidung}</td>
                            <td>
                                <a href='modul/uploads/{$row['HinhAnh']}' data-fancybox='gallery'>
                                    <img src='modul/uploads/{$row['HinhAnh']}' alt='Hình ảnh' style='width: 50px;'>
                                </a>
                            </td>
                          </tr>";
                    $stt++;
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Không có bài viết nào</td></tr>";
            }
            $link->close();
            ?>
        </tbody>
    </table>
</div>

<!-- jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4/dist/fancybox.umd.js"></script>

<script>
    // Tìm kiếm trực tiếp
    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#newsList tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

</body>
</html>

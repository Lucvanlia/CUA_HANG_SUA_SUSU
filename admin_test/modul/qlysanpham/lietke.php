
<?php
include('ketnoi/conndb.php');

// Thiết lập số lượng sản phẩm trên mỗi trang
$limit = 10;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$start = ($page - 1) * $limit;

// Kiểm tra xem có từ khóa tìm kiếm không
$query = isset($_POST['query']) ? $_POST['query'] : '';

// Tạo truy vấn SQL với từ khóa tìm kiếm nếu có
$sql = "SELECT sp.id_sp, sp.TenSP, h.TenHang, l.TenLoai, xx.TenXuatXu, sp.hinh
        FROM dmsp sp 
        JOIN hang h ON sp.id_hang = h.id_hang 
        JOIN loai l ON sp.id_loai = l.id_loai 
        JOIN xuatxu xx ON sp.id_xuatxu = xx.id_xuatxu
        WHERE sp.TenSP LIKE '%$query%'
        LIMIT $start, $limit";
$result_sp = mysqli_query($link, $sql);

// Đếm tổng số sản phẩm
$total_sql = "SELECT COUNT(*) AS total 
              FROM dmsp 
              WHERE TenSP LIKE '%$query%'";
$total_result = mysqli_query($link, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];

?>

<div class="container mt-5">
    <h2>Danh sách sản phẩm</h2>

    <!-- Ô tìm kiếm -->
    <div class="form-group col-lg-4 col-md-12 ">
        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm sản phẩm...">
    </div>
       <!-- Button Thêm sản phẩm -->
       <div class="form-group col-lg-4 col-md-6 ">
            <button >Thêm sản phẩm</button>
            <a data-fancybox data-type="ajax" href="modul/qlysanpham/them.php" class="btn btn-primary" id="addProductBtn">Load content using AJAX</a>

       </div>
<!-- Popup nhập thông tin sản phẩm -->
<div style="display: none;" id="addProductPopup">
    <div class="card p-4">
        <h4>Thêm sản phẩm mới</h4>
        <form id="addProductForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="Tensp">Tên sản phẩm:</label>
                <input type="text" class="form-control" id="Tensp" name="Tensp" required>
            </div>

            <div class="form-group">
                <label for="id_hang">Hãng:</label>
                <select class="form-control" id="id_hang" name="id_hang" required>
                    <!-- Thêm các tùy chọn hãng từ database -->
                </select>
            </div>

            <div class="form-group">
                <label for="id_xuatxu">Xuất xứ:</label>
                <select class="form-control" id="id_xuatxu" name="id_xuatxu" required>
                    <!-- Thêm các tùy chọn xuất xứ từ database -->
                </select>
            </div>

            <div class="form-group">
                <label for="id_loai">Loại sản phẩm:</label>
                <select class="form-control" id="id_loai" name="id_loai" required>
                    <!-- Thêm các tùy chọn loại sản phẩm từ database -->
                </select>
            </div>

            <div class="form-group">
                <label for="Mota">Mô tả:</label>
                <textarea class="form-control" id="Mota" name="Mota"></textarea>
            </div>

            <div class="form-group">
                <label for="hinh">Hình nền sản phẩm:</label>
                <input type="file" class="form-control" id="hinh" name="hinh" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="HinhAnh_ChiTiet">Hình ảnh chi tiết:</label>
                <div id="dropzone" class="dropzone"></div>
                <input type="hidden" name="HinhAnh_ChiTiet" id="HinhAnh_ChiTiet" required>
            </div>

            <div class="form-group">
                <label for="Gia">Giá:</label>
                <input type="number" class="form-control" id="Gia" name="Gia" required>
            </div>

            <div class="form-group">
                <label for="SoLuong">Số lượng:</label>
                <input type="number" class="form-control" id="SoLuong" name="SoLuong" required>
            </div>

            <button type="submit" class="btn btn-success" id = "submit">Lưu sản phẩm</button>
        </form>
    </div>
</div>




    <!-- Bảng danh sách sản phẩm -->
    <div id="productList">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Hãng</th>
                    <th>Loại</th>
                    <th>Xuất xứ</th>
                    <th>Hình ảnh</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result_sp) > 0) {
                    $stt = $start + 1;
                    while ($row = mysqli_fetch_assoc($result_sp)) {
                        $tenhang = explode(' ', $row['TenHang']);
                        if (count($tenhang) > 15) {
                            $tenhang = array_slice($tenhang, 0, 15);
                            $tenhang = implode(' ', $tenhang) . '...';
                        } else {
                            $tenhang= $row['TenLoai'];
                        }
                        echo "<tr>
                            <td>{$stt}</td>
                            <td>{$row['TenSP']}</td>
                            <td>{$row['TenHang']}</td>
                            <td>{$tenhang}</td>
                            <td>{$row['TenXuatXu']}</td>
                            <td>
                                <a href='modul/uploads//{$row['hinh']}' data-fancybox='gallery'>
                                    <img src='modul/uploads//{$row['hinh']}' alt='Hình ảnh' style='width: 50px;'>
                                </a>
                            </td>
                          </tr>";
                        $stt++;
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Không tìm thấy sản phẩm nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <nav>
        <ul class="pagination">
            <?php
            $total_pages = ceil($total_products / $limit);
            if ($total_pages > 1) {
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='page-item'><a href='#' class='page-link' data-page='{$i}'>{$i}</a></li>";
                }
            }
            ?>
        </ul>
    </nav>
</div>

<!-- jQuery và Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4/dist/fancybox.umd.js"></script>
<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.25.0/lts/standard/ckeditor.js"></script>
<!-- Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<script>
    // Khởi tạo CKEditor cho trường Mô tả
    CKEDITOR.replace('Mota');

    // Hiển thị popup thêm sản phẩm khi click button
 
</script>

<script>
    // Tìm kiếm trực tiếp
    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#productList tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

</script>

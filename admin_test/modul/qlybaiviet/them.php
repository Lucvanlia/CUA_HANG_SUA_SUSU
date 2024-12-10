<?php
include "../../ketnoi/conndb.php"; // Kết nối cơ sở dữ liệu

// Kiểm tra kết nối cơ sở dữ liệu
if (!$link) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy danh sách danh mục loại tin tức cha và con
$sql_loaitintuc = "
    SELECT ltt.id_ltt, ltt.Ten_ltt, ltt.parent_ltt
    FROM LoaiTinTuc ltt
    ORDER BY ltt.parent_ltt ASC, ltt.id_ltt ASC";
$query_loaitintuc = mysqli_query($link, $sql_loaitintuc);

// Lấy danh sách danh mục sản phẩm cha và con
$sql_sanpham = "
    SELECT dm.id_dm, dm.Ten_dm, dm.parent_dm, sp.id_sp, sp.Ten_sp
    FROM DanhMuc dm
    LEFT JOIN SanPham sp ON dm.id_dm = sp.id_dm
    WHERE dm.parent_dm != 0
    ORDER BY dm.parent_dm ASC, dm.id_dm ASC";
$query_sanpham = mysqli_query($link, $sql_sanpham);

// Định dạng dữ liệu danh mục loại tin tức
$loaiTinTuc = [];
while ($row = mysqli_fetch_assoc($query_loaitintuc)) {
    $loaiTinTuc[] = $row;
}

// Định dạng dữ liệu danh mục sản phẩm và sản phẩm
$sanPham = [];
while ($row = mysqli_fetch_assoc($query_sanpham)) {
    $sanPham[$row['id_dm']]['Ten_dm'] = $row['Ten_dm'];
    $sanPham[$row['id_dm']]['SanPham'][] = [
        'id_sp' => $row['id_sp'],
        'Ten_sp' => $row['Ten_sp']
    ];
}


?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm bài viết</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
</head>

<body>
    <div class="container">
        <h3 class="text-center">Thêm bài viết</h3>
        <div class="box">
            <form method="post" id="formAddBaiViet" enctype="multipart/form-data" action="http://localhost/doan_php/admin_test/modul/qlybaiviet/xly.php">
                <div class="form-group">
                    <label for="title">Tiêu đề bài viết</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="loaitintuc">Loại bài viết</label>
                    <select name="loaitintuc" class="form-control" required>
                        <?php
                        foreach ($loaiTinTuc as $item) {
                            $prefix = $item['parent_ltt'] ? '--- ' : '';
                            echo '<option value="' . $item['id_ltt'] . '">' . $prefix . htmlspecialchars($item['Ten_ltt']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="hinhnen">Hình nền</label>
                    <input type="file" name="hinhnen" class="form-control" id="hinhnen" required>
                    <img id="preview-img" src="#" alt="Hình nền" style="display: none; width: 200px; margin-top: 10px;">
                </div>

                <div class="form-group">
                    <label for="sanpham">Sản phẩm liên quan</label>
                    <div id="sanPhamContainer">
                        <div class="sanPhamItem row mb-2">
                            <div class="col-md-10">
                                <select name="sanpham[]" class="form-control mb-2">
                                    <?php
                                    foreach ($sanPham as $id_dm => $dm) {
                                        echo '<optgroup label="' . htmlspecialchars($dm['Ten_dm']) . '">';
                                        foreach ($dm['SanPham'] as $sp) {
                                            echo '<option value="' . $sp['id_sp'] . '">' . htmlspecialchars($sp['Ten_sp']) . '</option>';
                                        }
                                        echo '</optgroup>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-danger btn-remove-order">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" style="margin-top: 20px;" id="btn-add-product" class="btn btn-sm btn-success mt-5 py-5 ">+ Thêm sản phẩm</button>
                </div>

                <div class="form-group">
                    <label for="noidung">Nội dung</label>
                    <textarea rows="10" name="noidung" id="content" class="form-control" ></textarea>
                </div>

                <div class="form-group text-center">
                    <input type="submit" name="submit" value="Lưu" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        // CKEditor
        ClassicEditor.create(document.querySelector('#content')).catch(error => console.error(error));

        // Thêm dòng sản phẩm
        $(document).ready(function() {
            // Thêm dòng sản phẩm
            $('#btn-add-product').on('click', function() {
                const productRow = `
                <div class="sanPhamItem row mb-2">
                    <div class="col-md-10">
                        <select name="sanpham[]" class="form-control mb-2">
                            <?php
                            foreach ($sanPham as $id_dm => $dm) {
                                echo '<optgroup label="' . htmlspecialchars($dm['Ten_dm']) . '">';
                                foreach ($dm['SanPham'] as $sp) {
                                    echo '<option value="' . $sp['id_sp'] . '">' . htmlspecialchars($sp['Ten_sp']) . '</option>';
                                }
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-order">X</button>
                    </div>
                </div>
                `;
                $('#sanPhamContainer').append(productRow);
            });

            // Xóa dòng sản phẩm
            $(document).on('click', '.btn-remove-order', function() {
                $(this).closest('.sanPhamItem').remove();
            });
        });

        document.getElementById('hinhnen').addEventListener('change', function(event) {
            const file = event.target.files[0]; 
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.getElementById('preview-img');
                imgElement.src = e.target.result;
                imgElement.style.display = 'block';
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>

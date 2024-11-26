<!-- Fancybox JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.css" />

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.umd.js"></script>
<?php

// Lấy danh sách Loại Tin Tức
$query = "SELECT * FROM Loaitintuc ORDER BY parent_ltt ASC, id_ltt ASC";
$result = mysqli_query($link, $query);

$Loaitintuc = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $Loaitintuc[] = $row;
    }
}

// Hàm hiển thị Loại Tin Tức phân cấp dưới dạng bảng
function hienThiLoaitintuc($Loaitintuc, $parent = 0, $level = 0)
{
    $html = '';
    foreach ($Loaitintuc as $dm) {
        if ($dm['parent_ltt'] == $parent) {
            $prefix = str_repeat('|--->', $level); // Thêm ký tự phân cấp
            $icon = $level === 0 ? '<i class="fas fa-folder-open text-primary"></i>' : ''; // Icon cho Loại Tin Tức cha
            $html .= '<tr>';
            $html .= '<td> &nbsp;&nbsp;' . $icon . ' &nbsp;&nbsp;&nbsp;' . $prefix . $dm['Ten_ltt'] . '</td>';
            $html .= '<td class="text-center">';
            $html .= '  <button class="btn btn-sm btn-warning btn-edit" 
                            data-id="' . $dm['id_ltt'] . '" 
                            data-name="' . $dm['Ten_ltt'] . '" 
                            data-parent="' . $dm['parent_ltt'] . '"
                            data-hinh="' . $dm['Hinh_ltt'] . '"
                            
                            >
                            <i class="fas fa-edit"></i>
                        </button>';
            $html .= '  <button class="btn btn-sm btn-danger btn-delete" 
                            data-id="' . $dm['id_ltt'] . '">
                            <i class="fas fa-trash-alt"></i>
                        </button>';
            $html .= '</td>';
            $html .= '</tr>';

            // Gọi đệ quy để hiển thị Loại Tin Tức con
            $html .= hienThiLoaitintuc($Loaitintuc, $dm['id_ltt'], $level + 1);
        }
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Loại Tin Tức </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Form thêm/sửa bên trái -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Thêm/Sửa Loại Tin Tức</h5>
                    </div>
                    <div class="card-body">

                        <form id="formLoaitintuc" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="Ten_ltt" class="form-label">Tên Loại Tin Tức</label>
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" id="id_ltt" name="id_ltt"> <!-- ID Loại Tin Tức để chỉnh sửa -->
                                <input type="text" class="form-control" id="Ten_ltt" name="Ten_ltt" placeholder="Nhập tên Loại Tin Tức" required>
                            </div>
                            <div class="mb-3">
                                <label for="parent_ltt" class="form-label">Loại Tin Tức cha</label>
                                <select class="form-select" id="parent_ltt" name="parent_ltt">
                                    <option value="0">Không có Loại Tin Tức cha</option>
                                    <!-- Hiển thị Loại Tin Tức cha -->
                                     <?php $sql = "SELECT * FROM Loaitintuc where parent_ltt = 0 ";
                                            $query = mysqli_query($link,$sql);
                                            while($row = mysqli_fetch_array($query))
                                            {
                                                ?>
                                                 <option value="<?= $row['id_ltt'] ?>"><?= $row['Ten_ltt'] ?></option>

                                                <?php
                                            }
                                     ?>
                                  
                                </select>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="Hinh_ltt">Hình ảnh:</label>
                                    <input type="file" class="form-control" id="Hinh_ltt" name="Hinh_ltt" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <img id="Hinh_ltt_preview" src="" alt="Hình ảnh" style="width: 100px; height: 100px; object-fit: cover;display: none;">
                                    <br>
                                    <img id="imagePreview" src="" alt="Preview" style="display: none; width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success" id="btnAdd">Thêm mới</button>
                                    <button type="submit" class="btn btn-warning text-white" id="btnEdit" style="display: none;">Sửa</button>
                                    <button type="submit" class="btn btn-danger text-white text-right ml-3" id="btnBack" style="display: none;">Trở lại thêm</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hiển thị Loại Tin Tức bên phải -->
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Danh sách Loại Tin Tức</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên Loại Tin Tức</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="LoaitintucTable">
                                <?= hienThiLoaitintuc($Loaitintuc); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Thêm Loại Tin Tức

            $('#btnAdd').on('click', function(e) {
                e.preventDefault();

                // Lấy dữ liệu từ form và chuẩn bị gửi bằng FormData
                const formData = new FormData($('#formLoaitintuc')[0]);

                console.log(formData); // Kiểm tra dữ liệu gửi đi

                $.ajax({
                    url: 'ajax-process/loaitintuc.php',
                    type: 'POST',
                    data: formData,
                    processData: false, // Không xử lý dữ liệu
                    contentType: false, // Không thiết lập content type
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            Fancybox.show([{
                                src: `
                    <div style="padding: 20px; text-align: center;">
                        <div style="font-size: 50px; color: green; margin-bottom: 15px;">
                            <img src="img/verified.gif" width="50" height="50">
                        </div>
                        <h3>Thông báo</h3>
                        <p>Trạng thái: <strong>Bạn đã thêm Loại Tin Tức thành công</strong></p>
                        <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                    </div>`,
                                type: "html",
                            }], {
                                afterShow: (instance, current) => {
                                    console.info("Fancybox hiện đã mở!");
                                },
                            });
                            $('#formLoaitintuc')[0].reset(); // Reset form
                            $('#imagePreview').hide(); // Ẩn preview ảnh
                            loadLoaitintuc(); // Tải lại Loại Tin Tức
                            loadParentLoaitintuc(); // Tải lại Loại Tin Tức cha
                        } else {
                            Fancybox.show([{
                                src: `
                    <div style="padding: 20px; text-align: center;">
                        <div style="font-size: 50px; color: green; margin-bottom: 15px;">
                            <img class="img-thumbnail" src="img/delivery.gif" width="50" height="50">
                        </div>
                        <h3>Thông báo</h3>
                        <p>Trạng thái: <strong>${response.message}</strong></p>
                        <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                    </div>`,
                                type: "html",
                            }], {
                                afterShow: (instance, current) => {
                                    console.info("Fancybox hiện đã mở!");
                                },
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Đã có lỗi xảy ra!');
                    }
                });
            });

            $('.btn-edit').on('click', function() {


                // Sau khi gán, nhấn sửa
                $('#btnEdit').on('click', function(e) {
                    e.preventDefault();

                    // Kiểm tra dữ liệu trước khi gửi AJAX
                    const formData = $('#formLoaitintuc').serialize();
                    console.log(formData); // Kiểm tra dữ liệu gửi đi

                    $.ajax({
                        url: 'ajax-process/loaitintuc.php',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.status === 'success') {
                                Fancybox.show([{
                                    src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: green; margin-bottom: 15px;">
                                <img src="img/verified.gif" width="50" height="50">
                            </div>
                            <h3>Thông báo</h3>
                            <p>Trạng thái: <strong>Bạn đã sửa Loại Tin Tức thành công</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                                    type: "html",
                                }], {
                                    afterShow: (instance, current) => {
                                        console.info("Fancybox hiện đã mở!");
                                    },
                                });

                                // Tải lại Loại Tin Tức và Loại Tin Tức cha
                                loadLoaitintuc();
                                loadParentLoaitintuc();
                            } else {
                                Fancybox.show([{
                                    src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: red; margin-bottom: 15px;">
                                <img src="img/error.gif" width="50" height="50">
                            </div>
                            <h3>Thông báo</h3>
                            <p>Trạng thái: <strong>${response.message}</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                                    type: "html",
                                }], {
                                    afterShow: (instance, current) => {
                                        console.info("Fancybox hiện đã mở!");
                                    },
                                });
                                console.log(formData); // Kiểm tra dữ liệu gửi đi

                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('Đã có lỗi xảy ra!');
                        }
                    });
                });
            });


            // Sử dụng event delegation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault(); // Ngăn chặn hành động mặc định
                const id = $(this).data('id');

                if (confirm('Bạn có chắc chắn muốn xóa Loại Tin Tức này cùng với các Loại Tin Tức con?')) {
                    $.ajax({
                        url: 'ajax-process/loaitintuc.php',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            id_ltt: id,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Fancybox.show([{
                                    src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: green; margin-bottom: 15px;">
                                <img src="img/verified.gif" width="50" height="50">
                            </div>
                            <h3>Thông báo</h3>
                            <p>Trạng thái: <strong>${response.message}</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                                    type: "html",
                                }]);

                                // Tải lại Loại Tin Tức sau khi xóa thành công
                                loadLoaitintuc();
                                loadParentLoaitintuc();
                            } else {
                                Fancybox.show([{
                                    src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: red; margin-bottom: 15px;">
                                <img src="img/error.gif" width="50" height="50">
                            </div>
                            <h3>Thông báo</h3>
                            <p>Trạng thái: <strong>${response.message}</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                                    type: "html",
                                }]);
                            }
                            console.log($('#formLoaitintuc').serialize());
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('Đã có lỗi xảy ra!');
                        },
                    });
                }
            });

        });

        function loadParentLoaitintuc() {
            $.ajax({
                url: 'ajax-process/loaitintuc.php',
                type: 'POST',
                data: {
                    action: 'loadParent'
                },
                success: function(response) {
                    $('#parent_ltt').html(response); // Cập nhật danh sách
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tải Loại Tin Tức cha:", error);
                }
            });
        }

        function loadLoaitintuc() {
            $.ajax({
                url: 'ajax-process/loaitintuc.php',
                type: 'POST', // Phương thức GET
                data: {
                    action: 'load'
                },
                success: function(response) {
                    // Cập nhật danh sách Loại Tin Tức vào phần tử có id là Loaitintuc-section
                    $('#LoaitintucTable').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tải Loại Tin Tức:", error);
                    $('#LoaitintucTable').html("<p>Đã có lỗi xảy ra khi tải Loại Tin Tức.</p>");
                }
            });
        }
        $('#btn-delete').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'ajax-process/loaitintuc.php',
                type: 'POST',
                data: $('#formLoaitintuc').serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Thêm dòng này để kiểm tra phản hồi từ server
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#formLoaitintuc').reset(); // Reset form
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // In ra thông báo lỗi chi tiết
                    alert('Đã có lỗi xảy ra!');
                }
            });
        });



        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id'); // Lấy ID Loại Tin Tức
            const name = $(this).data('name'); // Lấy tên Loại Tin Tức
            const parent = $(this).data('parent'); // Lấy ID Loại Tin Tức cha
            const hinh = $(this).data('hinh'); // Lấy đường dẫn hình ảnh

            // Gán dữ liệu vào form
            $('#id_ltt').val(id); // Gán ID vào input hidden
            $('#Ten_ltt').val(name); // Gán tên Loại Tin Tức
            $('#parent_ltt').val(parent); // Gán Loại Tin Tức cha
            $('#imagePreview').attr('src', 'uploads/' + hinh); // Hiển thị ảnh trong form (Giả sử có thẻ <img id="Hinh_ltt_preview">)

            // Đổi action của form thành "edit"
            $('input[name="action"]').val('edit');

            // Chuyển nút submit thành nút sửa
            $('#btnAdd').hide();
            $('#btnEdit').show();
            $('#btnBack').show();
            $('#imagePreview').show();
        });

        $(document).on('click', '#btnBack', function() {
            // Chuyển nút trở về trạng thái "Thêm"
            $('#btnAdd').show();
            $('#btnEdit').hide();
            $('#btnBack').hide();
            $('#imagePreview').hide();
            // Đặt lại action của form thành "add"
            $('input[name="action"]').val('add');

            // Đặt lại form (nếu có các input khác)
            $('#formLoaitintuc')[0].reset();

            // Loại bỏ các tham số không cần thiết trong URL
            const baseUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', baseUrl);
        });

        $('#Hinh_ltt').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').hide();
            }
        });


        // Xóa Loại Tin Tức
    </script>
</body>

</html>
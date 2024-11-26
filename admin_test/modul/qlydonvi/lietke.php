<!-- Fancybox JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.css" />

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0.30/dist/fancybox.umd.js"></script>
<?php

// Lấy danh sách đơn vị
$query = "SELECT * FROM donvi ORDER BY parent_dv ASC, id_dv ASC";
$result = mysqli_query($link, $query);

$donvi = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $donvi[] = $row;
    }
}

// Hàm hiển thị đơn vị phân cấp dưới dạng bảng
function hienThidonvi($donvi, $parent = 0, $level = 0)
{
    $html = '';
    foreach ($donvi as $dm) {
        $status = $dm['Hoatdong']; // Lấy trạng thái hoạt động của xuất xứ
        $statusText = ($status == 1) ? 'OFF' : 'ON';
        $statusClass = ($status == 1) ? 'btn-danger' : 'btn-success';
        $iconClass = ($status == 1) ? 'fa-times' : 'fa-check';
        if ($dm['parent_dv'] == $parent) {
            $prefix = str_repeat('|--->', $level); // Thêm ký tự phân cấp
            $icon = $level === 0 ? '<i class="fas fa-folder-open text-primary"></i>' : ''; // Icon cho đơn vị cha
            $html .= '<tr>';
            $html .= '<td> &nbsp;&nbsp;' . $icon . ' &nbsp;&nbsp;&nbsp;' . $prefix . $dm['Ten_dv'] . '</td>';
            $html .= '<td class="text-center">';
            $html .= '  <button class="btn btn-sm btn-warning btn-edit" 
                            data-id="' . $dm['id_dv'] . '" 
                            data-name="' . $dm['Ten_dv'] . '" 
                            data-parent="' . $dm['parent_dv'] . '">
                            <i class="fas fa-edit"></i>
                        </button>';
            $html .= '     <button class="btn btn-sm ' . $statusClass . ' btn-toggle-status" 
                        data-id="' . $dm['id_dv'] . '" 
                        data-status="' . $status . '">
                        <i class="fas ' . $iconClass . '"></i> ' . $statusText . '
                    </button>';
            $html .= '</td>';
            $html .= '</tr>';

            // Gọi đệ quy để hiển thị đơn vị con
            $html .= hienThidonvi($donvi, $dm['id_dv'], $level + 1);
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
    <title>Quản lý đơn vị </title>
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
                        <h5>Thêm/Sửa đơn vị</h5>
                    </div>
                    <div class="card-body">

                        <form id="formdonvi">
                            <div class="mb-3">
                                <label for="Ten_dv" class="form-label">Tên đơn vị</label>
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" id="id_dv" name="id_dv"> <!-- ID đơn vị để chỉnh sửa -->
                                <input type="text" class="form-control" id="Ten_dv" name="Ten_dv" placeholder="Nhập tên đơn vị" required>
                            </div>
                            <div class="mb-3">
                                <label for="parent_dv" class="form-label">đơn vị cha</label>
                                <select class="form-select" id="parent_dv" name="parent_dv">
                                    <option value="0">Không có đơn vị cha</option>
                                    <!-- Hiển thị đơn vị cha -->
                                    <?php foreach ($donvi as $dm): ?>
                                        <option value="<?= $dm['id_dv'] ?>"><?= $dm['Ten_dv'] ?></option>
                                    <?php endforeach; ?>
                                </select>
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

            <!-- Hiển thị đơn vị bên phải -->
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Danh sách đơn vị</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên đơn vị</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="donviTable">
                                <?= hienThidonvi($donvi); ?>
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
            // Thêm đơn vị

            $('#btnAdd').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'ajax-process/donvi.php',
                    type: 'POST',
                    data: $('#formdonvi').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            Fancybox.show([{
                                src: `
                                <div style="padding: 20px; text-align: center;">
                                    <div style="font-size: 50px; color: green; margin-bottom: 15px; ">
                                            <img  src="img/verified.gif" width="50" height="50">
                                    </div>
                                    <h3>Thông báo</h3>
                                    <p>Trạng thái: <strong>Bạn đẫ thêm đơn vị thành công</strong></p>
                                    <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                                </div>`,
                                type: "html",
                            }, ], {
                                afterShow: (instance, current) => {
                                    console.info("Fancybox hiện đã mở!");
                                },
                            });
                            loaddonvi(); // Tải lại đơn vị
                            loadParentdonvi()
                        } else {
                            // alert(response.message);
                            Fancybox.show([{
                                src: `
                                <div style="padding: 20px; text-align: center;">
                                    <div style="font-size: 50px; color: green; margin-bottom: 15px;">
                                            <img class ="img-thumnail"  src="img/delivery.gif" width="50" height="50">
                                    </div>
                                    <h3>Thông báo</h3>
                                    <p>Trạng thái: <strong>${response.message}</strong></p>
                                    <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                                </div>`,
                                type: "html",
                            }, ], {
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
                    const formData = $('#formdonvi').serialize();
                    console.log(formData); // Kiểm tra dữ liệu gửi đi

                    $.ajax({
                        url: 'ajax-process/donvi.php',
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
                            <p>Trạng thái: <strong>Bạn đã sửa đơn vị thành công</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                                    type: "html",
                                }], {
                                    afterShow: (instance, current) => {
                                        console.info("Fancybox hiện đã mở!");
                                    },
                                });

                                // Tải lại đơn vị và đơn vị cha
                                loaddonvi();
                                loadParentdonvi();
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

                if (confirm('Bạn có chắc chắn muốn xóa đơn vị này cùng với các đơn vị con?')) {
                    $.ajax({
                        url: 'ajax-process/donvi.php',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            id_dv: id,
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

                                // Tải lại đơn vị sau khi xóa thành công
                                loaddonvi();
                                loadParentdonvi();
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
                            console.log($('#formdonvi').serialize());
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('Đã có lỗi xảy ra!');
                        },
                    });
                }
            });

        });

        function loadParentdonvi() {
            $.ajax({
                url: 'ajax-process/donvi.php',
                type: 'POST',
                data: {
                    action: 'loadParent'
                },
                success: function(response) {
                    $('#parent_dv').html(response); // Cập nhật danh sách
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tải đơn vị cha:", error);
                }
            });
        }

        function loaddonvi() {
            $.ajax({
                url: 'ajax-process/donvi.php',
                type: 'POST', // Phương thức GET
                data: {
                    action: 'load'
                },
                success: function(response) {
                    // Cập nhật danh sách đơn vị vào phần tử có id là donvi-section
                    $('#donviTable').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tải đơn vị:", error);
                    $('#donviTable').html("<p>Đã có lỗi xảy ra khi tải đơn vị.</p>");
                }
            });
        }
        $('#btn-delete').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'ajax-process/donvi.php',
                type: 'POST',
                data: $('#formdonvi').serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Thêm dòng này để kiểm tra phản hồi từ server
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#formdonvi').reset(); // Reset form
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
            const id = $(this).data('id'); // Lấy ID đơn vị
            const name = $(this).data('name'); // Lấy tên đơn vị
            const parent = $(this).data('parent'); // Lấy ID đơn vị cha

            // Gán dữ liệu vào form
            $('#id_dv').val(id); // Gán ID vào input hidden
            $('#Ten_dv').val(name); // Gán tên đơn vị
            $('#parent_dv').val(parent); // Gán đơn vị cha

            // Đổi action của form thành "edit"
            $('input[name="action"]').val('edit');

            // Chuyển nút submit thành nút sửa
            $('#btnAdd').hide();
            $('#btnEdit').show();
            $('#btnBack').show();

        });
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id'); // Lấy ID đơn vị
            const name = $(this).data('name'); // Lấy tên đơn vị
            const parent = $(this).data('parent'); // Lấy ID đơn vị cha

            // Gán dữ liệu vào form
            $('#id_dv').val(id); // Gán ID vào input hidden
            $('#Ten_dv').val(name); // Gán tên đơn vị
            $('#parent_dv').val(parent); // Gán đơn vị cha

            // Đổi action của form thành "edit"
            $('input[name="action"]').val('edit');

            // Chuyển nút submit thành nút sửa
            $('#btnAdd').hide();
            $('#btnEdit').show();
            $('#btnBack').show();

        });
        $(document).on('click', '#btnBack', function() {
            // Chuyển nút trở về trạng thái "Thêm"
            $('#btnAdd').show();
            $('#btnEdit').hide();
            $('#btnBack').hide();

            // Đặt lại action của form thành "add"
            $('input[name="action"]').val('add');

            // Đặt lại form (nếu có các input khác)
            $('#formdonvi')[0].reset();

            // Loại bỏ các tham số không cần thiết trong URL
            const baseUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', baseUrl);
        });

        $(document).on('click', '.btn-toggle-status', function() {
            const $this = $(this); // Chỉ tham chiếu đến nút hiện tại
            const id = $this.data('id'); // Lấy ID Xuất xứ
            const currentStatus = $this.data('status'); // Lấy trạng thái hiện tại

            // Xác nhận hành động
            const newStatus = currentStatus == 0 ? 1 : 0; // Đổi trạng thái

            $.ajax({
                url: 'ajax-process/donvi.php',
                type: 'POST',
                data: {
                    action: 'toggle_status',
                    id: id,
                    status: newStatus
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
                        // Cập nhật lại trạng thái của nút hiện tại
                        if (newStatus == 0) {
                            // Kích hoạt lại
                            $this
                                .removeClass('btn-danger')
                                .addClass('btn-success')
                                .data('status', 0) // Cập nhật trạng thái mới vào data attribute
                                .html('<i class="fas fa-check"></i> ON');
                        } else {
                            // Ngừng hoạt động
                            $this
                                .removeClass('btn-success')
                                .addClass('btn-danger')
                                .data('status', 1) // Cập nhật trạng thái mới vào data attribute
                                .html('<i class="fas fa-times"></i> OFF');
                        }
                    } else {
                        alert('Cập nhật trạng thái thất bại!');
                    }
                },
                error: function() {
                    alert('Đã có lỗi xảy ra!');
                }
            });
        });

        // Xóa đơn vị
    </script>
</body>

</html>
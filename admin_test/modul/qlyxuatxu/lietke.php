<div class="container mt-5">
    <div class="row py-2 m-2">
        <!-- Form thêm/sửa bên trái -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Thêm/Sửa Xuất xứ</h5>
                </div>
                <div class="card-body">
                    <form id="formNhaCungCap" enctype="multipart/form-data">
                        <div class="mb-3 form-group">
                            <label for="Ten_xx" class="form-label">Tên Xuất xứ</label>
                            <input type="hidden" name="id_xx" id="id_xx" value="" class="form-control">
                            <input type="hidden" name="action" value="add" class="form-control">
                            <input type="text" class="form-control" id="Ten_xx" name="Ten_xx" placeholder="Nhập tên Xuất xứ" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success" id="btnAdd">Thêm mới</button>
                        <button type="submit" class="btn btn-warning text-white" id="btnEdit" style="display: none;">Sửa</button>
                        <button type="submit" class="btn btn-danger text-white text-right ml-3" id="btnBack" style="display: none;">Trở lại thêm</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Danh sách Xuất xứ</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tên Xuất xứ</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="danhMucTable">
                            <!-- Nội dung danh sách sẽ được tải động ở đây -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function loadNhaCungCap() {
        $.ajax({
            url: 'ajax-process/xuatxu.php',
            type: 'POST', // Phương thức POST vì dùng `action`
            data: {
                action: 'load'
            },
            success: function(response) {
                // Gắn nội dung trả về vào bảng
                $('#danhMucTable').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải Xuất xứ:", error);
                $('#danhMucTable').html("<p>Đã có lỗi xảy ra khi tải Xuất xứ.</p>");
            }
        });
    }
    $('#btnAdd').on('click', function(e) {
        e.preventDefault();

        // Lấy dữ liệu từ form và chuẩn bị gửi bằng FormData
        const formData = new FormData($('#formNhaCungCap')[0]);

        $.ajax({
            url: 'ajax-process/xuatxu.php',
            type: 'POST',
            data: formData,
            processData: false, // Không xử lý dữ liệu
            contentType: false, // Không thiết lập content type
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
                            <p>Trạng thái: <strong>Bạn đã thêm Xuất xứ thành công</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                        type: "html",
                    }]);

                    $('#formNhaCungCap')[0].reset(); // Reset form
                    $('#imagePreview').hide(); // Ẩn preview ảnh
                    loadNhaCungCap(); // Tải lại danh sách Xuất xứ
                } else {
                    Fancybox.show([{
                        src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: red; margin-bottom: 15px;">
                                <img src="img/delivery.gif" width="50" height="50">
                            </div>
                            <h3>Thông báo</h3>
                            <p>Trạng thái: <strong>${response.message}</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                        type: "html",
                    }]);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Đã có lỗi xảy ra!');
            }
        });
    });
    $('#btnEdit').on('click', function(e) {
        e.preventDefault();

        const formData = new FormData($('#formNhaCungCap')[0]);

        $.ajax({
            url: 'ajax-process/xuatxu.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
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
                    $('#formNhaCungCap')[0].reset(); // Reset form
                    $('#imagePreview').hide(); // Ẩn preview ảnh
                    loadNhaCungCap(); // Tải lại danh sách
                } else {
                    Fancybox.show([{
                        src: `
                        <div style="padding: 20px; text-align: center;">
                            <div style="font-size: 50px; color: red; margin-bottom: 15px;">
                                <img src="img/delivery.gif" width="50" height="50">
                            </div>
                            <h3>Lỗi</h3>
                            <p>${response.message}</p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                        type: "html",
                    }]);
                }
            },
            error: function(xhr, status, error) {
                alert('Đã có lỗi xảy ra!');
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.btn-toggle-status', function() {
        const $this = $(this); // Chỉ tham chiếu đến nút hiện tại
        const id = $this.data('id'); // Lấy ID Xuất xứ
        const currentStatus = $this.data('status'); // Lấy trạng thái hiện tại

        // Xác nhận hành động
        const newStatus = currentStatus == 0 ? 1 : 0; // Đổi trạng thái

        $.ajax({
            url: 'ajax-process/xuatxu.php',
            type: 'POST',
            data: {
                action: 'toggle_status',
                id: id,
                status: newStatus
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
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
  


    // Sự kiện khi nhấn nút "Sửa" trong quản lý Xuất xứ
    $(document).on('click', '.btn-edit', function() {
        console.log('Nút Edit đã được nhấn'); // Log để kiểm tra

        const id = $(this).data('id');
        const name = $(this).data('name');
        const hinh = $(this).data('hinh');

        console.log({
            id,
            name,
            hinh
        }); // Xem giá trị được truyền

        // Tiếp tục xử lý logic
        $('#id_xx').val(id);
        $('#Ten_xx').val(name);
        if (hinh) {
            $('#imagePreview').attr('src', 'uploads/nhacungcap/' + hinh).show();
        } else {
            $('#imagePreview').hide();
        }

        $('input[name="action"]').val('edit');
        $('#btnAdd').hide();
        $('#btnEdit').show();
        $('#btnBack').show();
    });


    // Sự kiện khi nhấn nút "Trở lại" để hủy chế độ sửa
    $(document).on('click', '#btnBack', function() {
        // Chuyển form về chế độ "Thêm mới"
        $('#btnAdd').show(); // Hiển thị nút "Thêm mới"
        $('#btnEdit').hide(); // Ẩn nút "Sửa"
        $('#btnBack').hide(); // Ẩn nút "Trở lại"
        $('#imagePreview').hide(); // Ẩn ảnh xem trước (nếu có)

        // Đặt lại action của form thành "add"
        $('input[name="action"]').val('add');

        // Reset form về trạng thái ban đầu
        $('#formNhaCungCap')[0].reset();

        // Loại bỏ các tham số không cần thiết trong URL
        const baseUrl = window.location.origin + window.location.pathname;
        window.history.pushState({}, '', baseUrl);
    });
   
    // Gọi hàm khi trang tải
    loadNhaCungCap();
</script>
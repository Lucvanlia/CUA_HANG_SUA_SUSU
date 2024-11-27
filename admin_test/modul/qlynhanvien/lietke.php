<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 col-md-5">
            <h3>Danh sách nhân viên</h3>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 col-md-5">
            <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm nhân viên..." />
        </div>
        <div class="col-12 col-md-7 text-md-end">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Thêm mới nhân viên</button>
        </div>
    </div>
    <div id="user-list">
        <h4 style="display: none;" id="name_timkiem">Kết quả tìm kiếm</h4>
    </div>
    <div id="Khachhang-list"></div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm nhân viên</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formKhachhang">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id_nv" value="">
                        <label for="name">Tên nhân viên</label>
                        <input type="text" name="name" placeholder="Tên nhân viên" class="form-control mb-2" required>
                        <label for="email">Email liên hệ</label>
                        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
                        <label for="dob">Ngày sinh</label>
                        <input type="date" name="dob" placeholder="Ngày sinh" class="form-control mb-2" required>
                        <label for="phone">Số điện thoại</label>
                        <input type="text" name="phone" placeholder="Số điện thoại" class="form-control mb-2" required>
                        <div id="password-input-group" style="display: none; ">
                            <label for="password">Mật khẩu mới</label>
                            <div class="input-group" style="position: relative;">
                                <input type="password" name="password" id="password" placeholder="Mật khẩu mới" class="form-control">
                                <span class="input-group-text toggle-password" style="cursor: pointer;">
                                    <i class="bi bi-eye-slash-fill"></i>
                                </span>
                            </div>
                        </div>
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnClose">Close</button>
                    <button type="submit" class="btn btn-success" id="btnAdd">Thêm mới</button>
                    <button type="submit" class="btn btn-success" id="btnUpdate" style="display: none;">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>

</style>

<script>
    $(document).on('click', '.toggle-password', function() {
        const passwordField = $('#password');
        const passwordIcon = $(this).find('i'); // Tìm biểu tượng mắt trong phần tử được nhấn

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text'); // Chuyển thành văn bản
            passwordIcon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill'); // Thay đổi icon
        } else {
            passwordField.attr('type', 'password'); // Chuyển thành mật khẩu
            passwordIcon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill'); // Đổi lại icon
        }
    });


    $('#exampleModal').on('hidden.bs.modal', function() {
        $('#btnUpdate').hide();
        $('#btnAdd').show();
    });
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id'); // Lấy id của nhân viên từ thuộc tính data-id
        $.ajax({
            url: 'ajax-process/nhanvien.php',
            type: 'POST',
            data: {
                action: 'get_customer',
                id_nv: id
            },
            success: function(response) {
                var data = JSON.parse(response); // Giả sử dữ liệu trả về là JSON
                if (data) {
                    // Điền thông tin vào các input trong modal
                    $('input[name="name"]').val(data.Ten_nv);
                    $('input[name="email"]').val(data.Email_nv);
                    $('input[name="dob"]').val(data.NgaySinh_nv);
                    $('input[name="phone"]').val(data.SDT_nv);
                    $('input[name="id_nv"]').val(data.id_nv);
                    // Thay đổi action thành "edit"
                    $('input[name="action"]').val('edit');
                    $('input[name="action"]').val('edit');

                    // Hiển thị nút "Cập nhật" và thay đổi nút "Thêm mới" thành "Cập nhật"
                    $('#btnAdd').hide();
                    $('#btnUpdate').show();
                    $('#btnUpdate').show();

                    // Nếu cột Authen_nv rỗng, hiển thị input mật khẩu mới
                    if (!data.Authen_nv) {
                        $('#password-input-group').show();
                        $('input[name="password"]').val('');
                    } else {
                        $('#password-input-group').hide();
                    }

                    // Hiện modal
                    $('#exampleModal').modal('show');
                    $('#exampleModal').on('hidden.bs.modal', function() {
                        $('#btnUpdate').hide();
                        $('#btnAdd').show();
                        $('input[name="action"]').val('add');
                    });
                }
            },
            error: function() {
                alert('Không thể tải thông tin nhân viên.');
            }
        });
    });

    $('#btnAdd').on('click', function(e) {
        e.preventDefault();

        // Lấy dữ liệu từ form và chuẩn bị gửi bằng FormData
        const formData = new FormData($('#formKhachhang')[0]);

        $.ajax({
            url: 'ajax-process/nhanvien.php',
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
                            <p>Trạng thái: <strong>${response.message}</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                        type: "html",
                    }]);
                    $('#formKhachhang')[0].reset(); // Reset form
                    $('#imagePreview').hide(); // Ẩn preview ảnh
                    loadKhachHang();
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
    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            const searchValue = $(this).val();
            $.ajax({
                url: 'ajax-process/nhanvien.php',
                type: 'POST',
                data: {
                    action: 'search',
                    keyword: searchValue
                },
                success: function(response) {
                    $('#Khachhang-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tìm kiếm nhân viên:", error);
                    $('#Khachhang-list').html("<p>Đã có lỗi xảy ra khi tìm kiếm.</p>");
                }
            });
        });
    });


    function loadKhachHang(page = 1) {
        $.ajax({
            url: 'ajax-process/nhanvien.php',
            type: 'POST',
            data: {
                action: 'load',
                page: page
            },
            success: function(response) {
                $('#Khachhang-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải danh sách nhân viên:", error);
                $('#Khachhang-list').html("<p>Đã có lỗi xảy ra khi tải danh sách nhân viên.</p>");
            }
        });
    }

    // Gọi hàm khi tải trang
    $(document).ready(function() {
        loadKhachHang();
    });

    // Sử dụng phân trang
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadKhachHang(page);
    });
    $(document).on('click', '.btn-toggle-status', function() {
        const $this = $(this); // Chỉ tham chiếu đến nút hiện tại
        const id = $this.data('id'); // Lấy ID Xuất xứ
        const currentStatus = $this.data('status'); // Lấy trạng thái hiện tại

        // Xác nhận hành động
        const newStatus = currentStatus == 0 ? 1 : 0; // Đổi trạng thái

        $.ajax({
            url: 'ajax-process/nhanvien.php',
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
    $(document).on('click', '#btnUpdate', function(e) {
        e.preventDefault();
        $('input[name="action"]').val('edit');
        const formData = new FormData($('#formKhachhang')[0]);
        $.ajax({
            url: 'ajax-process/nhanvien.php',
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
                    $('#formKhachhang')[0].reset();
                    $('#exampleModal').modal('hide');
                    $('#btnUpdate').hide();
                    $('#btnAdd').show();
                    $('input[name="action"]').val('add');
                    loadKhachHang(); // Tải lại danh sách nhân viên
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
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
                console.error(xhr.responseText);
            },
        });
    });
</script>
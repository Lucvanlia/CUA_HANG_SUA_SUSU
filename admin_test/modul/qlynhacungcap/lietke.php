<div class="container mt-5">
    <div class="row py-2 m-2">
        <!-- Form thêm/sửa bên trái -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Thêm/Sửa Nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <form id="formNhaCungCap" enctype="multipart/form-data">
                        <div class="mb-3 form-group">
                            <label for="Ten_ncc" class="form-label">Tên nhà cung cấp</label>
                            <input type="hidden" name="action" value="add" class="form-control">
                            <input type="text" class="form-control" id="Ten_ncc" name="Ten_ncc" placeholder="Nhập tên nhà cung cấp" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="Hinh_ncc" class="form-label">Hình ảnh:</label>
                            <input type="file" class="form-control" id="Hinh_ncc" name="Hinh_ncc" accept="image/*" class="form-control">
                            <div class="container-fluid">
                                <img id="imagePreview" src="" alt="Preview" class="mt-3 img-fluid" style="display: none; width: 100px; height: 100px; object-fit: cover;">
                            </div>
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
                    <h5>Danh sách nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tên nhà cung cấp</th>
                                <th>Hình ảnh</th>
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
            url: 'ajax-process/nhacungcap.php',
            type: 'POST', // Phương thức POST vì dùng `action`
            data: {
                action: 'load'
            },
            success: function(response) {
                // Gắn nội dung trả về vào bảng
                $('#danhMucTable').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải nhà cung cấp:", error);
                $('#danhMucTable').html("<p>Đã có lỗi xảy ra khi tải nhà cung cấp.</p>");
            }
        });
    }
    $('#btnAdd').on('click', function(e) {
        e.preventDefault();

        // Lấy dữ liệu từ form và chuẩn bị gửi bằng FormData
        const formData = new FormData($('#formNhaCungCap')[0]);

        $.ajax({
            url: 'ajax-process/nhacungcap.php',
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
                            <p>Trạng thái: <strong>Bạn đã thêm nhà cung cấp thành công</strong></p>
                            <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                        type: "html",
                    }]);

                    $('#formNhaCungCap')[0].reset(); // Reset form
                    $('#imagePreview').hide(); // Ẩn preview ảnh
                    loadNhaCungCap(); // Tải lại danh sách nhà cung cấp
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

    // Sự kiện khi nhấn nút "Sửa" trong quản lý nhà cung cấp
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
        $('#id_ncc').val(id);
        $('#Ten_ncc').val(name);
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
    $('#Hinh_ncc').on('change', function() {
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

    // Gọi hàm khi trang tải
    loadNhaCungCap();
</script>
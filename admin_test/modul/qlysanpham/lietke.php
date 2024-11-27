<div class="container mt-5">
    <h2 class="mb-4">Danh sách sản phẩm</h2>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <input type="text" placeholder="Tìm kiếm" class="form-control">
        </div>
        <div class="col-lg-4">
            <a href="?action=quanlysanpham&query=themsp" class="btn btn-primary text-center text-white">Thêm sản phẩm </a>
        </div>
    </div>
    <table class="table table-bordered py-3 mt-2">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Tên sản phẩm</th>
                <th class="text-center">Hình</th>
                <th class="text-center">Giá</th>
                <th class="text-center">Trạng thái</th>
            </tr>
        </thead>
        <tbody id="product-list">
            <!-- Dữ liệu sản phẩm sẽ được tải ở đây -->
        </tbody>
    </table>
    <nav id="pagination">
        <!-- Nút phân trang sẽ được tải ở đây -->
    </nav>
</div>
<div id="editProductModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh Sửa Sản Phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditSanPham" enctype="multipart/form-data">
                    <!-- Tên sản phẩm -->
                    <div class="mb-3">
                        <label class="form-label">Tên Sản Phẩm</label>
                        <input type="text" class="form-control" name="Ten_sp" id="editTenSp" required>
                    </div>

                    <!-- Danh mục, Xuất xứ, Nhà cung cấp -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Danh Mục</label>
                            <select class="form-select" name="id_dm" id="editDanhMuc" required>
                                <option value="">Chọn danh mục</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Xuất Xứ</label>
                            <select class="form-select" name="id_xx" id="editXuatXu" required>
                                <option value="">Chọn xuất xứ</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nhà Cung Cấp</label>
                            <select class="form-select" name="id_ncc" id="editNhaCungCap" required>
                                <option value="">Chọn nhà cung cấp</option>
                            </select>
                        </div>
                    </div>

                    <!-- Danh sách kích thước Size -->
                    <div class="mb-3 mt-4">
                        <label class="form-label">Kích Thước (Size)</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kích Thước</th>
                                    <th>Giá Nhập</th>
                                    <th>Giá Bán</th>
                                    <th>Khuyến Mãi</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody id="sizeEditRows">
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-secondary" id="addSizeEditButton">Thêm Dòng Size</button>
                    </div>

                    <!-- Hình nền -->
                    <div class="mb-3">
                        <label class="form-label">Hình Nền</label>
                        <input type="file" class="form-control" name="Hinh_Nen" id="editImageUpload" accept="image/*">
                        <img id="editImagePreview" src="#" alt="Hình nền" style="display: none; margin-top: 10px; max-width: 200px;">
                    </div>

                    <!-- Hình chi tiết -->
                    <div class="mb-3">
                        <label class="form-label">Hình Chi Tiết</label>
                        <div id="editImagePreviewContainer" class="mb-2"></div>
                        <input type="file" id="editFiles" name="files[]" multiple accept="image/*">
                        <p id="editFileCount">Đã chọn 0 tệp</p>
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="mb-3">
                        <label class="form-label">Mô Tả Sản Phẩm</label>
                        <textarea id="editEditor" name="MoTa_sp"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnUpdateSanPham">Cập Nhật</button>
            </div>
        </div>
    </div>
</div>



<script>
    // Sự kiện click nút chỉnh sửa
  // Khi click vào nút chỉnh sửa sản phẩm
  


    // function LoadSanPham() {
    //     $.ajax({
    //         url: 'ajax-process/sanpham.php',
    //         type: 'POST', // Phương thức POST vì dùng `action`
    //         data: {
    //             action: 'load'
    //         },
    //         success: function(response) {
    //             // Gắn nội dung trả về vào bảng
    //             $('#product-list').html(response);
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Lỗi khi tải nhà cung cấp:", error);
    //             $('#product-list').html("<p>Đã có lỗi xảy ra khi tải nhà cung cấp.</p>");
    //         }
    //     });
    // }
    // LoadSanPham()
    $(document).on('click', '.btn-toggle-status', function() {
        const $this = $(this); // Chỉ tham chiếu đến nút hiện tại
        const id = $this.data('id'); // Lấy ID nhà cung cấp
        const currentStatus = $this.data('status'); // Lấy trạng thái hiện tại

        // Xác nhận hành động
        const newStatus = currentStatus == 0 ? 1 : 0; // Đổi trạng thái

        $.ajax({
            url: 'ajax-process/sanpham.php',
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
                        $this
                            .removeClass('btn-danger')
                            .addClass('btn-success')
                            .data('status', 0) // Cập nhật trạng thái mới vào data attribute
                            .html('<i class="fas fa-check"></i> ON');
                    } else {
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

    function loadProducts(page = 1) {
        $.ajax({
            url: 'ajax-process/sanpham.php',
            type: 'POST',
            data: {
                page: page,
                action: 'load'
            },
            dataType: 'json',
            success: function(response) {
                $('#product-list').html(response.productsHtml);
                $('#pagination').html(response.paginationHtml);
            },
            error: function() {
                alert('Lỗi khi tải sản phẩm!');
            }
        });
    }

    // Lần đầu tiên tải sản phẩm
    loadProducts();

    // Sự kiện khi bấm nút phân trang
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        loadProducts(page);
    });
</script>
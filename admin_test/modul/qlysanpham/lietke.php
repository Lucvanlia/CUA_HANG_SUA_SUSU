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
                     <input type="hidden" name="action" value="edit">
                     <input type="hidden" name="id_sp" value="" id="masp">
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
                                    <th>Trạng Thái</th>
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


                    <!-- Mô tả sản phẩm -->
                    <div class=" col-lg-12">
                        <div class="main-container">
                            <textarea id="editor" name="MoTa_sp">
            </textarea>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnUpdateSP">Cập Nhật</button>
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
    $(document).ready(function() {
        // Khi nhấn nút chỉnh sửa
        $('.btn-edit').on('click', function() {
            var productId = $(this).data('id'); // Lấy id sản phẩm từ data-id
            $('#masp').val(productId);

            // Gửi AJAX để lấy thông tin sản phẩm từ cơ sở dữ liệu
            $.ajax({
                url: 'ajax-process/get_product_details.php', // File xử lý lấy thông tin sản phẩm
                type: 'GET',
                data: {
                    id_sp: productId
                }, // Gửi id sản phẩm
                success: function(response) {
                    // Kiểm tra dữ liệu trả về
                    var product = JSON.parse(response);

                    if (product.status == 'success') {
                        // Điền vào modal
                        $('#editTenSp').val(product.data.Ten_sp); // Tên sản phẩm
                        $('#editDanhMuc').val(product.data.id_dm); // Danh mục
                        $('#editXuatXu').val(product.data.id_xx); // Xuất xứ
                        $('#editNhaCungCap').val(product.data.id_ncc); // Nhà cung cấp
                        $('#editor').val(product.data.MoTa_sp);

                        // Hiển thị hình nền nếu có
                        if (product.data.Hinh_Nen) {
                            $('#editImagePreview').show().attr('src', 'uploads/sanpham/' + product.data.Hinh_Nen);
                        } else {
                            $('#editImagePreview').hide();
                        }
                        $('#editProductModal').modal('show');


                        // Gửi yêu cầu để lấy danh sách kích thước sản phẩm (nếu có)
                        $.ajax({
                            url: 'ajax-process/get_product_sizes.php', // File xử lý lấy size của sản phẩm
                            type: 'GET',
                            data: {
                                id_sp: productId
                            },
                            success: function(sizeResponse) {
                                var sizes = JSON.parse(sizeResponse);
                                var sizeRows = '';
                                sizes.forEach(function(size) {
                                    sizeRows += `
                                <tr>
                                    <td><input type="hidden" class="form-control" name="sizes[Size1][]" value="${size.id_dv}" required></td>
                                    <td><input type="text" class="form-control" name="sizes[Size][]" value="${size.Ten_dv}" required></td>
                                    <td><input type="number" class="form-control" name="sizes[GiaNhap][]" value="${size.GiaNhap}" required></td>
                                    <td><input type="number" class="form-control" name="sizes[GiaBan][]" value="${size.GiaBan}" required></td>
                                    <td><select class="form-select" name="sizes[TrangThai][]">
                                        <option value="1" ${size.TrangThai == 0 ? 'selected' : ''}>Hoạt Động</option>
                                        <option value="0" ${size.TrangThai == 1 ? 'selected' : ''}>Tạm dừng</option>
                                    </select></td>
                                </tr>
                                `;
                                });
                                $('#sizeEditRows').html(sizeRows);
                            }
                        });

                        // Hiển thị modal chỉnh sửa
                        $('#editProductModal').modal('show');
                    } else {
                        alert('Không tìm thấy sản phẩm');
                    }
                }
            });
        });

        // Thêm dòng kích thước mới
        $('#addSizeEditButton').on('click', function() {
            var newSizeRow = `
        <tr>
            <td><input type="text" class="form-control" name="sizes[Size][]" required></td>
            <td><input type="number" class="form-control" name="sizes[GiaNhap][]" required></td>
            <td><input type="number" class="form-control" name="sizes[GiaBan][]" required></td>
            <td><input type="number" class="form-control" name="sizes[KhuyenMai][]" required></td>
            <td><select class="form-select" name="sizes[TrangThai][]">
                <option value="1">Kích hoạt</option>
                <option value="0">Tạm dừng</option>
            </select></td>
            <td><button type="button" class="btn btn-danger remove-size-row">Xóa</button></td>
        </tr>
        `;
            $('#sizeEditRows').append(newSizeRow);
        });

        // Xóa dòng kích thước
        $(document).on('click', '.remove-size-row', function() {
            $(this).closest('tr').remove();
        });

        // Cập nhật sản phẩm khi nhấn nút cập nhật
       
    });
    $('#btnUpdateSP').on('click', function(e) {
                e.preventDefault();

                // Lấy dữ liệu từ form và chuẩn bị gửi bằng FormData
                const formData = new FormData($('#formEditSanPham')[0]);

                console.log(formData); // Kiểm tra dữ liệu gửi đi
        
                $.ajax({
                    url: 'ajax-process/sanpham.php',
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
                        <p>Trạng thái: <strong>${response.message}</strong></p>
                        <button onclick="Fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                    </div>`,
                                type: "html",
                            }], {
                                afterShow: (instance, current) => {
                                    console.info("Fancybox hiện đã mở!");
                                },
                            });
                      
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
    function loadSelectData() {
        $.ajax({
            url: "ajax-process/sanpham.php",
            type: "POST",
            data: {
                action: "load_select_data"
            },
            dataType: "json",
            success: function(data) {
                if (data.status === "success") {
                    $('select[name="id_dm"]').html(data.danhmuc);
                    $('select[name="id_xx"]').html(data.xuatxu);
                    $('select[name="id_ncc"]').html(data.nhacungcap);
                }
            },
        });
    }
    loadSelectData();
   

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Thêm thư viện maskMoney -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.1.1/jquery.maskMoney.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone/dist/dropzone.css">
<script src="https://cdn.jsdelivr.net/npm/dropzone/dist/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.7/js/froala_editor.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.7/css/froala_editor.min.css" rel="stylesheet">
<script>
    new FroalaEditor('#editor');
</script>
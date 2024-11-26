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
    <table class="table table-bordered py-3 mt-4">
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
<script>
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

    function loadProducts(page = 1) {
        $.ajax({
            url: 'ajax-process/sanpham.php',
            type: 'POST',
            data: {
                page: page,
                action:'load'
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
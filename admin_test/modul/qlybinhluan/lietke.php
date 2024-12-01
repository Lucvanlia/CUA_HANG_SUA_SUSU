<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 col-md-5">
            <h3>Danh sách BÌnh Luận</h3>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 col-md-5">
            <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm người dùng..." />
        </div>
        <div class="col-12 col-md-7 text-md-end">
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Thêm mới BÌnh Luận</button>
        </div>
    </div>
    <div id="user-list">
        <h4 style="display: none;" id="name_timkiem">Kết quả tìm kiếm</h4>
    </div>
    <div id="Khachhang-list"></div>

    <!-- Modal -->
    
</div>

<script>


    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            const searchValue = $(this).val();
            $.ajax({
                url: 'ajax-process/binhluan.php',
                type: 'POST',
                data: {
                    action: 'search',
                    keyword: searchValue
                },
                success: function(response) {
                    $('#Khachhang-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tìm kiếm người dùng:", error);
                    $('#Khachhang-list').html("<p>Đã có lỗi xảy ra khi tìm kiếm.</p>");
                }
            });
        });
    });


    function loadKhachHang(page = 1) {
        $.ajax({
            url: 'ajax-process/binhluan.php',
            type: 'POST',
            data: {
                action: 'load',
                page: page
            },
            success: function(response) {
                $('#Khachhang-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải danh sách BÌnh Luận:", error);
                $('#Khachhang-list').html("<p>Đã có lỗi xảy ra khi tải danh sách BÌnh Luận.</p>");
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
            url: 'ajax-process/binhluan.php',
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
   

</script>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <h3>Danh sách hóa đơn</h3>
        </div>
        <div class="col-md-6 text-end">
            <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm hóa đơn..." />
        </div>
    </div>
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Khách hàng</th>
                    <th>Email</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody id="hoadon-list"></tbody>
        </table>
    </div>
    <nav id="pagination" class="mt-3"></nav>
</div>
<script>
    $(document).ready(function () {
    const fetchData = (searchQuery = "", page = 1) => {
        $.ajax({
            url: 'ajax-process/hoadon.php',
            type: 'POST',
            data: { action: 'fetch', search: searchQuery, page: page },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#hoadon-list').html(response.data.html);
                    $('#pagination').html(response.data.pagination);
                } else {
                    $('#hoadon-list').html('<p class="text-danger">Không có dữ liệu!</p>');
                }
            },
            error: function () {
                alert('Đã có lỗi xảy ra!');
            }
        });
    };

    // Gọi dữ liệu lần đầu tiên
    fetchData();

    // Tìm kiếm
    $('#search-input').on('keyup', function () {
        const searchQuery = $(this).val();
        fetchData(searchQuery);
    });

    // Phân trang
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        const searchQuery = $('#search-input').val();
        fetchData(searchQuery, page);
    });
});

</script>
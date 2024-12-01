<div class="container-fluid">
    <h4>Tìm kiếm đơn hàng</h4>
    <div class="row">
        <div class="col-md-4 mt-2 text-right">
            <form id="searchForm">
                <input class="form-control" type="text" placeholder="Nhập mã đơn hàng, email hoặc số điện thoại của bạn"  id="content">
            </form>
        </div>
        <div class="col-md-8">
            <button class="btn btn-warning" id="btnFind" style="color: white;">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
        </div>
    </div>
    <div class="mt-4" id="List-Orders"></div>
</div>
<script>
    $('#btnFind').on('click', function (e) {
    e.preventDefault();
    var content = $('#content').val().trim();

    if (content === '') {
        alert('Vui lòng nhập thông tin tìm kiếm!');
        return;
    }

    $.ajax({
        url: 'resultdonhang.php',
        type: 'POST',
        data: { content: content },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                var orders = response.data;
                var html = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mã Đơn Hàng</th>
                                <th>Khách Hàng</th>
                                <th>Email</th>
                                <th>Số Điện Thoại</th>
                                <th>Trạng Thái</th>
                                <th>Thanh Toán</th>
                                <th>Ngày Tạo</th>
                                <th>Tổng Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                orders.forEach(function (order, index) {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${order.id_hdb}</td>
                            <td>${order.Ten_kh}</td>
                            <td>${order.Email_kh}</td>
                            <td>${order.SDT_kh}</td>
                            <td>${order.TrangThai == 0 ? 'Chưa xử lý' : 'Đã xử lý'}</td>
                            <td>${order.ThanhToan == 0 ? 'Chưa thanh toán' : 'Đã thanh toán'}</td>
                            <td>${order.created_at}</td>
                            <td>${new Intl.NumberFormat().format(order.TongTien)} VND</td>
                        </tr>
                    `;
                });

                html += '</tbody></table>';
                $('#List-Orders').html(html);
            } else {
                alert(response.message);
                $('#List-Orders').html('');
            }
        },
        error: function () {
            alert('Đã xảy ra lỗi trong quá trình tìm kiếm!');
        }
    });
});

</script>
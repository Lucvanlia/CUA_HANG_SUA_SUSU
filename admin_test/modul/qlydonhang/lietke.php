<style>
    .status-box {
        border-radius: 20px;
        /* Bo tròn nền */
        padding: 5px 10px;
        /* Khoảng cách chữ và viền */
        display: inline-block;
        /* Đảm bảo nội dung không kéo dài */
        font-size: 14px;
        /* Kích thước chữ */
        text-align: center;
        /* Canh giữa chữ */
    }
</style>
<div class="container">
    <h1>Danh sách hóa đơn</h1>
    <input type="text" id="search-hdb" class="form-control mb-3" placeholder="Tìm kiếm hóa đơn...">
    <div id="hdb-list"></div>
</div>
<!-- Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsLabel">Chi tiết hóa đơn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Thông tin hóa đơn</h6>
                <div id="order-info">
                    <!-- Thông tin hóa đơn sẽ được tải từ AJAX -->
                </div>
                <h6 class="mt-4">Cập nhật trạng thái</h6>
                <div class="form-group">
                    <!-- Dropdown trạng thái -->
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownStatus" data-bs-toggle="dropdown" aria-expanded="false">
                            Trạng thái
                        </button>
                        <input type="hidden" id="order-id" value="">
                        <ul class="dropdown-menu" aria-labelledby="dropdownStatus">

                            <li><a class="dropdown-item status-option" href="#" data-value="1">
                                    <i class="fas fa-truck text-warning"></i> Đang vận chuyển
                                </a></li>
                            <li><a class="dropdown-item status-option" href="#" data-value="2">
                                    <i class="fas fa-times-circle text-danger"></i> Chưa nhận hàng
                                </a></li>
                            <li><a class="dropdown-item status-option" href="#" data-value="0">
                                    <i class="fas fa-check-circle text-success"></i> Đã nhận hàng
                                </a></li>
                        </ul>
                    </div>

                    <!-- Hiển thị trạng thái đã chọn -->
                    <p><strong>Trạng thái hiện tại:</strong> <span id="selected-status">Chưa nhận hàng</span></p>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="print-pdf" class="btn btn-primary">In PDF</button>

                <script>
                    document.getElementById('print-pdf').addEventListener('click', function() {
                        const idHdb = $('#order-id').val(); // Lấy mã đơn hàng từ dữ liệu trên trang (hoặc biến có sẵn)

                        // Gửi yêu cầu đến generate_invoice.php với id_hdb
                        const formData = new FormData();
                        formData.append('id_hdb', idHdb); // Gửi id_hdb cho PHP

                        fetch('ajax-process/generate_invoice.php', {
                                method: 'POST',
                                body: formData // Đảm bảo gửi dữ liệu trong body
                            })
                            .then(response => {
                                if (response.ok) {
                                    return response.blob(); // Trả về blob PDF
                                } else {
                                    throw new Error('Không thể tạo file PDF');
                                }
                            })
                            .then(blob => {
                                // Tải file PDF về
                                const link = document.createElement('a');
                                link.href = URL.createObjectURL(blob);
                                link.download = 'invoice_' + idHdb + '.pdf'; // Đặt tên file tải về
                                link.click(); // Mô phỏng hành động tải về
                            })
                            .catch(error => {
                                console.error('Lỗi:', error);
                            });
                    });
                </script>


            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.order-name', function() {
        const orderId = $(this).data('id');
        $('#order-id').val(orderId);
        $.ajax({
            url: 'ajax-process/hoadon.php',
            method: 'POST',
            data: {
                action: 'getOrderDetails',
                id_hdb: orderId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const order = response.data.order;
                    const items = response.data.items;

                    // Render thông tin hóa đơn
                    let orderInfo = `
                    <p><strong>Mã hóa đơn:</strong> ${order.id_hdb}</p>
                    <p><strong>Ngày tạo:</strong> ${order.created_at}</p>
                       `;
                    const paymentHTML =
                        order.ThanhToan == 0 ?
                        `<span class="status-box bg-success text-white"><i class="fas fa-money-bill-wave"></i> Thanh toán COD</span>` :
                        order.ThanhToan == 1 ?
                        `<span class="status-box bg-primary text-white"><i class="fas fa-credit-card"></i> Chuyển khoản</span>` :
                        `<span class="status-box bg-danger text-white"><i class="fas fa-exclamation-triangle"></i> Chưa thanh toán</span>`;

                    // Xử lý trạng thái đơn hàng
                    const statusHTML =
                        order.TrangThai == 0 ?
                        `<span class="status-box bg-success text-white"><i class="fas fa-check-circle"></i> Đã nhận hàng</span>` :
                        order.TrangThai == 1 ?
                        `<span class="status-box bg-warning text-black"><i class="fas fa-truck"></i> Đang vận chuyển</span>` :
                        `<span class="status-box bg-danger text-white"><i class="fas fa-times-circle"></i> Chưa nhận hàng</span>`;

                    // Thêm thông tin thanh toán và trạng thái vào HTML
                    orderInfo += `
                    <p><strong>Thanh toán:</strong> ${paymentHTML}</p>
                    <p><strong>Trạng thái:</strong> ${statusHTML}</p>
                    `;




                    // Render danh sách sản phẩm
                    orderInfo += '<h6>Sản phẩm:</h6>';
                    orderInfo += `<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên sản phẩm</th>
                                        <Th>Kích Thước</Th>
                                        <th>Số lượng</th>
                                        <th>Đơn giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    var tongtien = 0;
                    items.forEach((item, index) => {
                     
                        tongtien += parseFloat(item.ThanhTien); 
                                                orderInfo += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.Ten_sp}</td>
                            <td>${item.Ten_dv}</td>
                            <td>${item.SoLuong}</td>
                            <td>${ Intl.NumberFormat().format(item.DonGia)}</td>
                            <td>${ Intl.NumberFormat().format(item.ThanhTien)}</td>
                        </tr>
                        <tr>${tongtien}</tr>
                        
                    `;
                        


                    });
                    // Định dạng tổng tiền với dấu phân cách nghìn và đảm bảo không mất số 0 đầu
                    var formattedTongTien = tongtien.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });

                    // Thêm tổng tiền vào cuối bảng
                    orderInfo += `
                        <tr>
                            <td colspan="5"><strong>Tổng tiền:</strong></td>
                            <td><strong>${formattedTongTien}</strong></td>
                        </tr>
                    `;
                    orderInfo += '</tbody></table>';

                    $('#order-info').html(orderInfo);

                    // Set trạng thái hiện tại
                    $('#order-status').val(order.TrangThai);

                    // Mở modal
                    $('#orderDetailsModal').modal('show');
                } else {
                    alert('Không thể tải thông tin hóa đơn.');
                }
            }
        });
    });

    // Cập nhật trạng thái
    $(document).on('click', '.status-option', function() {
        const statusValue = $(this).data('value');
        if (statusValue == 3) statusValue = '0';
        const statusValuerep = $(this).text().trim();

        // Kiểm tra dữ liệu trước khi gửi
        console.log('Gửi dữ liệu:', {
            action: 'updateOrderStatus',
            id_hdb: $('#order-id').val(),
            TrangThai: statusValue
        });

        $.ajax({
            url: 'ajax-process/hoadon.php',
            type: 'POST',
            data: {
                action: 'updateOrderStatus',
                id_hdb: $('#order-id').val(),
                TrangThai: statusValue
            },
            dataType: 'json',
            success: function(response) {
                // Kiểm tra phản hồi
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
                    }]);

                    $('#orderDetailsModal').modal('hide');
                    loadHDB();

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
            },
            error: function(xhr, status, error) {
                // Xử lý lỗi chi tiết
                console.log('Lỗi yêu cầu AJAX:', status, error);
                alert('Đã có lỗi xảy ra khi gửi yêu cầu!');
            }
        });
    });

    function loadHDB(page = 1, search = '') {
        $.ajax({
            url: 'ajax-process/hoadon.php',
            method: 'POST',
            data: {
                page,
                search,
                action: 'load'
            },
            dataType: 'json',
            success: function(response) {
                const {
                    data,
                    total,
                    page,
                    limit
                } = response;
                $('#hdb-list').html(renderHDBTable(data, page, limit, total));
            },
            error: function() {
                $('#hdb-list').html('<p>Không thể tải dữ liệu.</p>');
            }
        });
    }
    $(document).ready(function() {
        // Hàm load dữ liệu hóa đơn
        function loadHDB(page = 1, search = '') {
            $.ajax({
                url: 'ajax-process/hoadon.php',
                method: 'POST',
                data: {
                    page,
                    search,
                    action: 'load'
                },
                dataType: 'json',
                success: function(response) {
                    const {
                        data,
                        total,
                        page,
                        limit
                    } = response;
                    $('#hdb-list').html(renderHDBTable(data, page, limit, total));
                },
                error: function() {
                    $('#hdb-list').html('<p>Không thể tải dữ liệu.</p>');
                }
            });
        }

        // Hàm render bảng hóa đơn
        function renderHDBTable(data, page, limit, total) {
            if (data.length === 0) {
                return '<p>Không có hóa đơn nào!</p>';
            }

            let html = `
        <table class="table table-bordered">
            <thead class ="text-center">
                <tr>
                    <th>#</th>
                    <th>Tên khách hàng</th>
                    <th>Email</th>
                    <th>Trạng thái</th>
                    <th>Thanh toán</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody class ="text-center">
    `;
            data.forEach((row, index) => {
                const statusHTML =
                    row.TrangThai == 0 ?
                    `<span class="status-box bg-success text-white"><i class="fas fa-check-circle"></i> Đã nhận hàng</span>` :
                    row.TrangThai == 1 ?
                    `<span class="status-box bg-warning text-black"><i class="fas fa-truck"></i> Đang vận chuyển</span>` :
                    `<span class="status-box bg-danger text-white"><i class="fas fa-times-circle"></i> Chưa nhận hàng</span>`;

                const paymentHTML =
                    row.ThanhToan == 0 ?
                    `<span class="status-box bg-success text-white"><i class="fas fa-money-bill-wave"></i> Thanh toán COD</span>` :
                    row.ThanhToan == 1 ?
                    `<span class="status-box bg-primary text-white"><i class="fas fa-credit-card"></i> Chuyển khoản</span>` :
                    `<span class="status-box bg-danger text-white"><i class="fas fa-exclamation-triangle"></i> Chưa thanh toán</span>`;

                html += `
            <tr>
                <td>${(page - 1) * limit + index + 1}</td>
                <td><button type="button" class="btn btn-link p-0 text-primary order-name" data-id="${row.id_hdb}" data-status="${row.TrangThai}">
    ${row.Ten_kh}
</button>
</td>
                <td>${row.Email_kh}</td>
                <td>${statusHTML}</td>
                <td>${paymentHTML}</td>
                <td>${row.created_at}</td>
            </tr>
        `;
            });
            html += '</tbody></table>';

            // Pagination
            const totalPages = Math.ceil(total / limit);
            html += '<nav><ul class="pagination">';
            for (let i = 1; i <= totalPages; i++) {
                const active = i === page ? 'active' : '';
                html += `<li class="page-item ${active}"><a href="#" class="page-link" data-page="${i}">${i}</a></li>`;
            }
            html += '</ul></nav>';

            return html;
        }


        // Sự kiện tìm kiếm
        $('#search-hdb').on('input', function() {
            const search = $(this).val();
            loadHDB(1, search);
        });

        // Sự kiện phân trang
        $(document).on('click', '.pagination .page-link', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            const search = $('#search-hdb').val();
            loadHDB(page, search);
        });

        // Tải dữ liệu ban đầu
        loadHDB();
    });
    $('#btnPrintPDF').on('click', function() {
        const idHDB = $('#order-id').val(); // Lấy ID hóa đơn

        // Gửi yêu cầu tạo PDF
        window.open(`ajax-process/generate_invoice.php`, '_blank');
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
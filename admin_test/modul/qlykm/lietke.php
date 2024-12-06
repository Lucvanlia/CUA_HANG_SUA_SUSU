<?php

// Lấy danh sách chương trình khuyến mãi
$query = mysqli_query($link, "SELECT * FROM ChuongTrinhKM");

if (!$query) {
    die("Error: " . mysqli_error($link));
}
?>

<head>
    <title>Quản lý Chương trình khuyến mãi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div class="container mt-5">
    <h2>Danh sách Chương trình Khuyến mãi</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Chương Trình</th>
                <th>Ngày Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
                <th>Xem</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?php echo $row['id_ctkm']; ?></td>
                    <td><?php echo $row['TenCTKM']; ?></td>
                    <td><?php echo $row['NgayBatDau']; ?></td>
                    <td><?php echo $row['NgayKetThuc']; ?></td>
                    <td>
                        <span class="badge <?php echo $row['HoatDong'] ? 'badge-success' : 'badge-secondary'; ?>">
                            <?php echo $row['HoatDong'] ? 'Hoạt động' : 'Không hoạt động'; ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm <?php echo $row['HoatDong'] ? 'btn-danger' : 'btn-success'; ?> btn-toggle-status"
                            data-id="<?php echo $row['id_ctkm']; ?>"
                            data-status="<?php echo $row['HoatDong']; ?>">
                            <i class="fas <?php echo $row['HoatDong'] ? 'fa-times' : 'fa-check'; ?>"></i>
                            <?php echo $row['HoatDong'] ? 'OFF' : 'ON'; ?>
                        </button>
                    </td>

                    <td>
                        <button class="btn btn-sm btn-info btn-view-details" data-id="<?php echo $row['id_ctkm']; ?>">
                            Xem chi tiết
                        </button>
                        <input type="hidden" name="id_sp" id="id_sp" value="<?php echo $row['id_ctkm']; ?>">
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Chi tiết chương trình khuyến mãi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nội dung sẽ được load bằng JavaScript -->
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on("click", ".btn-view-details", function() {
        const id_ctkm = $(this).data("id"); // Lấy ID chương trình khuyến mãi từ nút

        $.ajax({
            url: 'ajax-process/khuyenmai.php',
            method: "POST",
            data: {
                action: "details", // Gửi ID chương trình khuyến mãi
                id_ctkm: id_ctkm,
            },
            success: function(response) {
                console.log("Dữ liệu phản hồi từ server:", response); // In ra dữ liệu phản hồi

                try {
                    const data = response; // Nếu server gửi đúng JSON đối tượng, không cần JSON.parse()

                    if (data.success) {
                        const ctkm = data.data.ctkm;
                        const products = data.data.products;
                        const invoice = data.data.invoice;

                        // Xử lý và hiển thị thông tin chương trình khuyến mãi và các sản phẩm
                        let productsTable = '<table class="table table-striped">';
                        productsTable += '<thead><tr><th>Sản phẩm</th><th>Giảm giá</th><th>Số lượng KM</th><th>Trạng thái</th></tr></thead><tbody>';

                        products.forEach(product => {
                            const statusClass = product.HoatDong ? 'btn-danger' : 'btn-success'; // Dùng điều kiện để xác định lớp nút
                            const statusIcon = product.HoatDong ? 'fa-times' : 'fa-check'; // Dùng điều kiện để xác định biểu tượng
                            const statusText = product.HoatDong ? 'OFF' : 'ON'; // Dùng điều kiện để xác định văn bản "ON"/"OFF"

                            productsTable += `<tr>
                            <td>${product.Ten_sp}</td>
                            <td>${product.GiamGia} %</td>
                            <td>${product.SoLuongKhuyenMai}</td>
                            <td>
                                <button class="btn btn-sm ${statusClass} btn-toggle-status toggle-product-status"
                                        data-id="${product.id_sp}" 
                                        data-status="${product.HoatDong}">
                                    <i class="fas ${statusIcon}"></i>  ${statusText}
                                </button>
                            </td>
                        </tr>`;
                        });

                        productsTable += '</tbody></table>';

                        // Hiển thị thông tin hóa đơn khuyến mãi
                        let invoiceInfo = '';
                        if (invoice && invoice.id_kmhd) {
                            invoiceInfo = `
                            <p><strong>Điều kiện hóa đơn:</strong> ${invoice.DieuKienHoaDon}</p>
                            <p><strong>Giảm giá hóa đơn:</strong> ${invoice.GiamGia} %</p>
                        `;
                        }

                        // Hiển thị thông tin trong modal
                        $("#modalDetails .modal-body").html(`
                        <p><strong>Tên chương trình:</strong> ${ctkm.TenCTKM}</p>
                        <p><strong>Ngày bắt đầu:</strong> ${ctkm.NgayBatDau}</p>
                        <p><strong>Ngày kết thúc:</strong> ${ctkm.NgayKetThuc}</p>
                        <p><strong>Loại khuyến mãi:</strong> ${ctkm.LoaiKhuyenMai}</p>
                        <p><strong>Ghi chú:</strong> ${ctkm.GhiChu}</p>
                        <h5>Sản phẩm áp dụng:</h5>
                        ${productsTable}
                        <h5>Khuyến mãi hóa đơn:</h5>
                        ${invoiceInfo}
                    `);

                        // Hiển thị modal
                        $('#modalDetails').modal('show');
                    } else {
                        alert(data.message || "Không thể tải chi tiết!");
                    }
                } catch (e) {
                    console.error("Lỗi khi xử lý dữ liệu JSON:", e); // Nếu có lỗi khi xử lý
                    alert("Dữ liệu phản hồi không hợp lệ!");
                }
            },
            error: function() {
                alert("Có lỗi xảy ra khi kết nối đến máy chủ!");
            }
        });
    });

    $(document).ready(function() {


    });

        // Xử lý nút bật/tắt trạng thái sản phẩm
        $(document).on("click", ".toggle-product-status", function() {
            let id_sp = $(this).data("id");
            let currentStatus = $(this).data("status");

            $.ajax({
                url: "ajax-process/khuyenmai.php",
                method: "POST",
                data: {
                    action: 'toggleProductStatus',
                    id_sp: id_sp,
                    status: currentStatus
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message || "Trạng thái đã được cập nhật!");
                        $(".btn-view-details").click(); // Reload modal
                    } else {
                        alert(response.message || "Cập nhật trạng thái thất bại!");
                    }
                },
                error: function() {
                    alert("Có lỗi xảy ra khi kết nối đến máy chủ!");
                }
            });
        });


    $(document).on('click', '.btn-toggle-status', function() {
        const $this = $(this); // Chỉ tham chiếu đến nút hiện tại
        const id = $this.data('id'); // Lấy ID nhà cung cấp
        const currentStatus = $this.data('status'); // Lấy trạng thái hiện tại

        // Xác nhận hành động
        const newStatus = currentStatus == 0 ? 1 : 0; // Đổi trạng thái

        $.ajax({
            url: 'ajax-process/khuyenmai.php',
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
</script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
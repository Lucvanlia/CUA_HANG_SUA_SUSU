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
    <!-- Nút thêm khuyến mãi -->
    <div class="container mt-5 mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddKhuyenMai">Thêm Khuyến Mãi</button>
    </div>

    <!-- Modal thêm khuyến mãi -->
    <div id="modalAddKhuyenMai" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addKhuyenMaiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKhuyenMaiLabel">Thêm Chương Trình Khuyến Mãi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAddKhuyenMai">
                        <input type="hidden" name="action" value="add">
                        <!-- Thông tin chung -->
                        <div class="mb-3">
                            <label for="tenCTKM" class="form-label">Tên Chương Trình</label>
                            <input type="text" id="tenCTKM" name="tenCTKM" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="promoCodeInput" class="form-label">Mã Khuyến Mãi</label>
                            <input type="text" id="promoCodeInput" name="MaKM" class="form-control" require>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="ngayBatDau" class="form-label">Ngày Bắt Đầu</label>
                                <input type="date" id="ngayBatDau" name="ngayBatDau" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="ngayKetThuc" class="form-label">Ngày Kết Thúc</label>
                                <input type="date" id="ngayKetThuc" name="ngayKetThuc" class="form-control" required>
                            </div>
                        </div>

                        <!-- Khuyến mãi sản phẩm -->
                        <div class="mt-4">
                            <h6>Khuyến Mãi Sản Phẩm</h6>
                            <div id="sanPhamContainer">
                                <div class="sanPhamItem row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Sản phẩm</label>
                                        <select class="form-select sanPhamSelect" name="sanPham[]">
                                            <?php
                                            // Truy vấn danh mục con (parent_dm != 0)
                                            $sql_danhmuccon = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE parent_dm != 0";
                                            $query_danhmuccon = mysqli_query($link, $sql_danhmuccon);

                                            while ($row_danhmuccon = mysqli_fetch_assoc($query_danhmuccon)) {
                                                $id_dm = $row_danhmuccon['id_dm'];
                                                $ten_dm = $row_danhmuccon['Ten_dm'];

                                                // Bắt đầu nhóm danh mục
                                                echo '<optgroup label="-----' . htmlspecialchars($ten_dm) . '">';

                                                // Truy vấn sản phẩm thuộc danh mục hiện tại
                                                $sql_sanpham = "SELECT id_sp, Ten_sp FROM SanPham WHERE id_dm = $id_dm";
                                                $query_sanpham = mysqli_query($link, $sql_sanpham);

                                                if (mysqli_num_rows($query_sanpham) > 0) {
                                                    while ($row_sanpham = mysqli_fetch_assoc($query_sanpham)) {
                                                        echo '<option value="' . $row_sanpham['id_sp'] . '">' . htmlspecialchars($row_sanpham['Ten_sp']) . '</option>';
                                                    }
                                                } else {
                                                    // Nếu không có sản phẩm trong danh mục
                                                    echo '<option value="">Không có sản phẩm</option>';
                                                }

                                                // Kết thúc nhóm danh mục
                                                echo '</optgroup>';
                                            }
                                            ?>
                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Số lượng</label>
                                        <input type="number" class="form-control soLuongInput" name="soLuong[]" min="1" placeholder="Số lượng" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">% Giảm giá</label>
                                        <input type="number" class="form-control giamGiaInput" name="giamGiaSP[]" min="0" max="100" required placeholder="% Giảm Giá">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-remove-product">X</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="btn-add-product" class="btn btn-sm btn-success mt-3">+ Thêm sản phẩm</button>
                        </div>

                        <!-- Khuyến mãi hóa đơn -->
                        <div class="mt-4">
                            <h6>Khuyến Mãi Hóa Đơn</h6>
                            <div class="row mb-3" id="hoadon-container">
                                <div class="row mb-3 hoadon-row align-items-center">
                                    <div class="col-md-5">
                                        <label class="form-label">Giá trị tối thiểu</label>
                                        <input type="number" class="form-control" name="dieuKienHoaDon[]" min="0" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">% Giảm giá</label>
                                        <input type="number" class="form-control" name="giamGiaHD[]" min="0" max="100" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn  btn-danger btn-remove-order">X</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="btn-add-orders" class="btn btn-sm btn-success mt-3">+ Thêm hóa đơn</button>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" id="btn-save-khuyenmai" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('btn-add-orders').addEventListener('click', function() {
            // Container chứa các hóa đơn
            const container = document.getElementById('hoadon-container');

            // Tạo một div row mới
            const newRow = document.createElement('div');
            newRow.className = 'row mb-3 hoadon-row align-items-center';

            // Nội dung HTML của hóa đơn mới
            newRow.innerHTML = `
        <div class="col-md-5">
            <label class="form-label">Giá trị tối thiểu</label>
            <input type="number" class="form-control" name="dieuKienHoaDon[]" min="0" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">% Giảm giá</label>
            <input type="number" class="form-control" name="giamGiaHD[]" min="0" max="100" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-sm btn-danger btn-remove-order">X</button>
        </div>
    `;

            // Thêm dòng mới vào container
            container.appendChild(newRow);
        });

        // Xử lý sự kiện xóa
        document.getElementById('hoadon-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('btn-remove-order')) {
                const row = event.target.closest('.hoadon-row');
                row.remove(); // Xóa dòng hiện tại
            }
        });


        $(document).ready(function() {
            // Thêm dòng sản phẩm mới
            $("#btn-add-product").on("click", function() {
                const productRow = `
            <div class="sanPhamItem row mb-3">
                <div class="col-md-4">
                     <select class="form-select sanPhamSelect" name="sanPham[]">
                                            <?php
                                            // Truy vấn danh mục con (parent_dm != 0)
                                            $sql_danhmuccon = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE parent_dm != 0";
                                            $query_danhmuccon = mysqli_query($link, $sql_danhmuccon);

                                            while ($row_danhmuccon = mysqli_fetch_assoc($query_danhmuccon)) {
                                                $id_dm = $row_danhmuccon['id_dm'];
                                                $ten_dm = $row_danhmuccon['Ten_dm'];

                                                // Bắt đầu nhóm danh mục
                                                echo '<optgroup label="-----' . htmlspecialchars($ten_dm) . '">';

                                                // Truy vấn sản phẩm thuộc danh mục hiện tại
                                                $sql_sanpham = "SELECT id_sp, Ten_sp FROM SanPham WHERE id_dm = $id_dm";
                                                $query_sanpham = mysqli_query($link, $sql_sanpham);

                                                if (mysqli_num_rows($query_sanpham) > 0) {
                                                    while ($row_sanpham = mysqli_fetch_assoc($query_sanpham)) {
                                                        echo '<option value="' . $row_sanpham['id_sp'] . '">' . htmlspecialchars($row_sanpham['Ten_sp']) . '</option>';
                                                    }
                                                } else {
                                                    // Nếu không có sản phẩm trong danh mục
                                                    echo '<option value="">Không có sản phẩm</option>';
                                                }

                                                // Kết thúc nhóm danh mục
                                                echo '</optgroup>';
                                            }
                                            ?>
                                        </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control soLuongInput" name="soLuong[]" min="1" required placeholder="Số lượng">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control giamGiaInput" name="giamGiaSP[]" min="0" max="100" required placeholder="% Giảm giá">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-product">X</button>
                </div>
            </div>`;
                $("#sanPhamContainer").append(productRow);
            });

            // Xóa dòng sản phẩm
            $(document).on("click", ".btn-remove-product", function() {
                $(this).closest(".sanPhamItem").remove();
            });

            // Gửi dữ liệu
            $("#btn-save-khuyenmai").on("click", function() {
                const formData = $("#formAddKhuyenMai").serialize(); // Serialize toàn bộ form
                $.ajax({
                    url: "ajax-process/khuyenmai.php",
                    method: "POST",
                    data: {
                        action: "addKhuyenMai",
                        ...formData,
                    },
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.status === "success") {
                            alert("Thêm chương trình khuyến mãi thành công!");
                            $("#modalAddKhuyenMai").modal("hide");
                        } else {
                            alert(res.message || "Thêm khuyến mãi thất bại!");
                        }
                    },
                    error: function() {
                        alert("Có lỗi xảy ra khi kết nối đến máy chủ!");
                    },
                });
            });
        });
    </script>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Chương Trình</th>
                <th>Ngày Bắt Đầu</th>
                <th>Ngày Kết Thúc</th>
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
                            // Kiểm tra HoatDong có phải là 1 (ON) hay 0 (OFF)
                            const statusClass = product.HoatDong == 1 ? 'btn-danger' : 'btn-success'; // Dùng điều kiện để xác định lớp nút
                            const statusIcon = product.HoatDong == 1 ? 'fa-times' : 'fa-check'; // Dùng điều kiện để xác định biểu tượng
                            const statusText = product.HoatDong == 1 ? 'OFF' : 'ON'; // Dùng điều kiện để xác định văn bản "ON"/"OFF"

                            productsTable += `<tr>
                            <td>${product.Ten_sp}</td>
                            <td>${product.GiamGia} %</td>
                            <td>${product.SoLuongKhuyenMai}</td>
                            <td>
                                <button class="btn btn-sm ${statusClass} btn-toggle-status toggle-product-status"
                                        data-id="${product.id_kmsp}" 
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
        let id_sp = $(this).data("id"); // Lấy ID sản phẩm từ thuộc tính data-id của nút
        let currentStatus = $(this).data("status"); // Lấy trạng thái hiện tại của sản phẩm (0 hoặc 1)

        console.log("ID sản phẩm: ", id_sp); // Log để kiểm tra
        console.log("Trạng thái hiện tại: ", currentStatus); // Log để kiểm tra

        $.ajax({
            url: "ajax-process/khuyenmai.php", // Đường dẫn đến file xử lý PHP
            method: "POST",
            data: {
                action: 'toggleProductStatus', // Thực hiện hành động toggle trạng thái
                id_sp: id_sp, // ID sản phẩm
                status: currentStatus // Trạng thái hiện tại của sản phẩm
            },
            success: function(response) {
                console.log(response); // Log phản hồi từ server để kiểm tra

                // Kiểm tra trạng thái trả về từ PHP
                if (response.status === 'success') {
                    alert(response.message || "Trạng thái đã được cập nhật!");

                    // Cập nhật lại nút trạng thái sau khi thay đổi
                    let newStatus = currentStatus ? 0 : 1;
                    let newStatusClass = newStatus ? 'btn-danger' : 'btn-success';
                    let newStatusIcon = newStatus ? 'fa-times' : 'fa-check';
                    let newStatusText = newStatus ? 'OFF' : 'ON';

                    // Cập nhật giao diện nút
                    $(`button[data-id="${id_sp}"]`)
                        .removeClass('btn-success btn-danger')
                        .addClass(newStatusClass)
                        .find('i')
                        .removeClass('fa-check fa-times')
                        .addClass(newStatusIcon)
                        .next().text(newStatusText);

                    // Cập nhật lại thuộc tính data-status
                    $(`button[data-id="${id_sp}"]`).data('status', newStatus);
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
    // Hàm tạo mã khuyến mãi tự động
    // Hàm tạo mã khuyến mãi tự động
    // Khi nhấn nút lưu
    $('#btn-save-khuyenmai').on('click', function() {
        const formData = $('#formAddKhuyenMai').serialize(); // Thu thập dữ liệu form

        $.ajax({
            url: 'ajax-process/khuyenmai.php', // Đường dẫn đến file xử lý
            method: 'POST',
            data: formData, // Dữ liệu cần gửi
            success: function(response) {
                // Kiểm tra nếu response đã là đối tượng (JSON)
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response); // Parse chuỗi JSON nếu là chuỗi
                    } catch (e) {
                        alert('Có lỗi xảy ra khi xử lý phản hồi!');
                        return;
                    }
                }

                if (response.status === 'success') {
                    alert(response.message || "Chương trình khuyến mãi đã được lưu!");
                    $('#modalAddKhuyenMai').modal('hide');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi lưu chương trình!');
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi gửi dữ liệu!');
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
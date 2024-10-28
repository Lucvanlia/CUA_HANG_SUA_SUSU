<?php
if (isset($_SESSION['id_user'])) {
    $sql_hd = "SELECT *, FROM_UNIXTIME(NgayLapHD, '%Y-%m') as ThangNam 
           FROM hoadon
           WHERE id_kh = " . $_SESSION['id_user'] . "
           ORDER BY NgayLapHD DESC";
    $sql_ct = "SELECT * 
    FROM ctiethd ct
    JOIN dmsp sp ON sp.id_sp = ct.id_sp
    WHERE ct.id_hd IN (
        SELECT id_hd
        FROM hoadon
        WHERE id_kh = " . $_SESSION['id_user'] . "
    )";

    $result_hd = mysqli_query($link, $sql_hd);
    $result_ct = mysqli_query($link, $sql_ct);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Mua Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-status {
            font-weight: bold;
        }

        .order-status.pending {
            color: orange;
        }

        .order-status.paid {
            color: green;
        }

        .order-status.delivered {
            color: blue;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Lịch Sử Mua Hàng</h2>
        <div class="mb-3">
            <input type="text" class="form-control" id="searchOrder" placeholder="Tìm kiếm đơn hàng...">
        </div>
        <?php
        $orders_by_month = []; // Mảng để lưu các hóa đơn theo từng tháng

        // Lặp qua tất cả các hóa đơn và nhóm theo tháng
        while ($row_hd = mysqli_fetch_array($result_hd)) {
            $month_year = $row_hd['ThangNam']; // Lấy giá trị tháng và năm từ câu truy vấn
            $orders_by_month[$month_year][] = $row_hd; // Nhóm các hóa đơn theo tháng
        }
        ?>

        <div class="accordion py-2" id="orderHistory">
            <?php foreach ($orders_by_month as $month_year => $orders) { ?>
                <div class="py-2">
                    <h3 class="text-primary"><?= date("F Y", strtotime($month_year . "-01")) ?></h3>
                    <?php foreach ($orders as $row_hd) { ?>
                        <div class="accordion-item py-2">
                            <h2 class="accordion-header" id="heading<?= $row_hd['id_hd'] ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#order<?= $row_hd['id_hd'] ?>" aria-expanded="true" aria-controls="order<?= $row_hd['id_hd'] ?>">
                                    Đơn hàng #<?= $row_hd['id_hd'] ?> -
                                    <?php
                                    $status = "";
                                    switch ($row_hd['TrangThai']) {
                                        case '0':
                                            $status = "<span class='order-status' style='color: green;'>Đã giao hàng</span>";
                                            break;
                                        case '1':
                                            $status = "<span class='order-status' style='color: orange;'>Chờ xác nhận</span>";
                                            break;
                                        case '2':
                                            $status = "<span class='order-status' style='color: blue;'>Đã xác nhận</span>";
                                            break;
                                        case '3':
                                            $status = "<span class='order-status' style='color: purple;'>Đang giao hàng</span>";
                                            break;
                                        case '4':
                                            $status = "<span class='order-status' style='color: green;'>Đã nhận hàng</span>";
                                            break;
                                        case '5':
                                            $status = "<span class='order-status' style='color: red;'>Yêu cầu hủy đơn hàng</span>";
                                            break;
                                        case '6':
                                            $status = "<span class='order-status' style='color: red;'>Đơn hàng đã được hủy</span>";
                                            break;
                                    }
                                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                                    echo $status;
                                    ?>
                                </button>
                            </h2>
                            <div id="order<?= $row_hd['id_hd'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $row_hd['id_hd'] ?>" data-bs-parent="#orderHistory">
                                <div class="accordion-body">
                                    <p><strong>Ngày đặt hàng:</strong> <?= date("d/m/Y H:i:s", $row_hd['NgayLapHD']) ?></p>
                                    <p><strong>Tổng tiền:</strong> <?= number_format($row_hd['tongtien'], 0, ',', '.') ?> VND</p>
                                    <p><strong>Trạng thái:</strong>
                                        <?php if ($row_hd['thanhtoan'] == 0) echo '<span class="p-1 mb-1 bg-success text-center text-white rounded-pill">Thanh toán trực tuyến';
                                        else echo 'chưa thah thoán'; ?>
                                        </span>
                                    </p>
                                    <p><strong>Chi tiết sản phẩm:</strong></p>
                                    <ul>
                                        <?php
                                        // Lặp qua các chi tiết sản phẩm của đơn hàng hiện tại
                                        mysqli_data_seek($result_ct, 0); // Đặt con trỏ về đầu
                                        while ($row_ct = mysqli_fetch_array($result_ct)) {
                                            if ($row_ct['id_hd'] == $row_hd['id_hd']) {
                                                echo "<li>" . $row_ct['Tensp'] . " - " . $row_ct['SoLuong'] . "Kg - " . number_format($row_ct['gia'], 0, ',', '.') . " VND</li>";
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <button class="btn btn-danger btn-cancel-order">Yêu cầu hủy đơn hàng</button>
                                </div>
                            </div>
                        </div>
                    <?php } // End of orders loop 
                    ?>
                </div>
            <?php } // End of orders_by_month loop 
            ?>
        </div>

        <!-- end con tainer  -->
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Xử lý tìm kiếm
            $('#searchOrder').on('input', function() {
                var value = $(this).val().toLowerCase();
                $('.accordion-item').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Xử lý yêu cầu hủy đơn hàng
            $('.btn-cancel-order').on('click', function() {
                if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
                    alert('Yêu cầu hủy đơn hàng đã được gửi.');
                }
            });
        });
    </script>
</body>

</html>
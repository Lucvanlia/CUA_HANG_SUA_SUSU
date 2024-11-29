<?php
if (isset($_SESSION['id_user'])) {
    $id_user = mysqli_real_escape_string($link, $_SESSION['id_user']);
    $sql_hd = "SELECT *, FROM_UNIXTIME(created_at, '%Y-%m') as ThangNam 
           FROM hdb
           WHERE id_kh = " . $_SESSION['id_user'] . "
           ORDER BY created_at DESC";
           $sql_ct = "
           SELECT 
               sp.Ten_sp AS Ten_sp, 
               ct.SoLuong AS SoLuong, 
               dv.Ten_dv AS Ten_dv, 
               ct.DonGia AS DonGia, 
               ct.id_hdb AS id_hdb
           FROM ct_hdb ct
           JOIN Sanpham sp ON sp.id_sp = ct.id_sp
           JOIN DonVi dv ON dv.id_dv = ct.id_dv
           WHERE ct.id_hdb IN (
               SELECT id_hdb
               FROM hdb
               WHERE id_kh = '$id_user'
           )
           ORDER BY sp.Ten_sp, ct.SoLuong, dv.Ten_dv, ct.DonGia, ct.id_hdb
       ";

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
                    <?php
                    $tongtien = 0;
                    foreach ($orders as $row_hd) { ?>
                        <div class="accordion-item py-2">
                            <h2 class="accordion-header" id="heading<?= $row_hd['id_hdb'] ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#order<?= $row_hd['id_hdb'] ?>" aria-expanded="true" aria-controls="order<?= $row_hd['id_hdb'] ?>">
                                    Đơn hàng #<?= $row_hd['id_hdb'] ?> -
                                    <?php
                                    $status = "";
                                    switch ($row_hd['TrangThai']) {
                                        case '1':
                                            $status = "<span class='order-status' style='color: green;'>Đã giao hàng</span>";
                                            break;
                                        case '2':
                                            $status = "<span class='order-status' style='color: orange;'>Chờ xác nhận</span>";
                                            break;
                                        case '3':
                                            $status = "<span class='order-status' style='color: blue;'>Đã xác nhận</span>";
                                            break;
                                        case '4':
                                            $status = "<span class='order-status' style='color: purple;'>Đang giao hàng</span>";
                                            break;
                                        case '5':
                                            $status = "<span class='order-status' style='color: green;'>Đã nhận hàng</span>";
                                            break;
                                        case '6':
                                            $status = "<span class='order-status' style='color: red;'>Yêu cầu hủy đơn hàng</span>";
                                            break;
                                        case '7':
                                            $status = "<span class='order-status' style='color: red;'>Đơn hàng đã được hủy</span>";
                                            break;
                                    }
                                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                                    echo $status;
                                    ?>
                                </button>
                            </h2>
                            <div id="order<?= $row_hd['id_hdb'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $row_hd['id_hdb'] ?>" data-bs-parent="#orderHistory">
                                <div class="accordion-body">
                                    <p><strong>Ngày đặt hàng:</strong> <?= $row_hd['created_at'] ?></p>
                                    <p><strong>Tổng tiền:</strong> <?= number_format($tongtien, 0, ',', '.') ?> VND</p>
                                    <p><strong>Trạng thái:</strong>
                                        <?php if ($row_hd['ThanhToan'] == 1) echo '<span class="p-1 mb-1 bg-success text-center text-white rounded-pill">Đã thanh toán COD';
                                        else if ($row_hd['ThanhToan'] == 2) echo '<span class="p-1 mb-1 bg-primary text-center text-white rounded-pill">Đã thanh toán chuyển khoản';
                                        else  echo '<span class="p-1 mb-1 bg-danger text-center text-white rounded-pill">Chưa thanh toán' ?>
                                        </span>
                                    </p>
                                    <p><strong>Sản phẩm trong hóa đơn:</strong></p>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">STT</th>
                                                <th scope="col">Tên sản phẩm</th>
                                                <th scope="col">Số lượng</th>
                                                <th scope="col">Kích thước</th>
                                                <th scope="col">Đơn Giá</th>
                                                <th scope="col">Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stt = 1;
                                            
                                            // Lặp qua các chi tiết sản phẩm của đơn hàng hiện tại
                                            mysqli_data_seek($result_ct, 0); // Đặt con trỏ về đầu
                                            while ($row_ct = mysqli_fetch_array($result_ct)) {
                                                $thanhtien = $row_ct['DonGia']*$row_ct['SoLuong'];
                                                if ($row_ct['id_hdb'] == $row_hd['id_hdb']) {
                                                    // var_dump($row_ct);
                                                    echo "                                        <tr>
                                                    <th scope='row'>" . $stt . "</th>
                                                      <td> " . $row_ct['Ten_sp'] . " </td>
                                               <td> " . $row_ct['SoLuong'] . "  </td>
                                                <td>" . $row_ct['Ten_dv'] . "</td>
                                              <td>  " . number_format($row_ct['DonGia'], 0, ',', '.') . "</td> 
                                            <td>  " . number_format($thanhtien, 0, ',', '.') . "</td> 

                                              </tr>";
                                                       $stt++;
                                                $tongtien += $thanhtien;
                                                }
                                       
                                            }
                                            ?>




                                        </tbody>
                                    </table>
                                    <ul>

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
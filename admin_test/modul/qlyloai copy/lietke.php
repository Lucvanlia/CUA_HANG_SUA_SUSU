<?php 
    $sql2 = "SELECT * FROM hoadon";
    $result = mysqli_query($link, $sql2);
    $count = mysqli_num_rows($result);
?>
<div class="mb-4" id="DesignationTable" style="background-color:#fff">
    <div class="card-header py-3" style="background-color:#fff">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách hóa đơn</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <div class="py-4 d-flex flex-row bd-highlight mb-3">
                <form method="post" action="index.php?action=timkiem&query=timkiem_loai" class="py-2">
                    <label for="">Tìm kiếm hóa đơn</label>
                    <input type="text" id="search_loai" name="search" class="col-form-label" placeholder="Tìm mã hóa đơn" autocomplete="off" value="<?php if (isset($_GET['search'])) {echo $_GET['search'];} ?>">
                    <input type="submit" value="Tìm" class="btn btn-primary" name="tim">
                </form>
            </div>
            <form action="modul/qlyloai/xly.php" method="post">
                <td>Số lượng hóa đơn: <?php echo $count ?></td>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã hóa đơn</th>
                            <th>Ngày mua</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Chỉnh sửa</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="searchkq">
                        <?php
                        $stt  = 0;
                        while ($row = mysqli_fetch_array($result)) {
                            $stt++;
                        ?>
                            <tr>
                                <td><?php echo $stt ?></td>
                                <td><?php echo $row['id_hd']; ?></td>
                                <td><?php echo date("d/m/Y H:i:s", $row['NgayLapHD']); ?></td>
                                <td><?php echo number_format($row['tongtien'], 0, ',', '.'); ?> VND</td>
                                <td>
                                    <select class="form-select order-status-select" data-order-id="<?php echo $row['id_hd']; ?>">
                                        <option value="0" <?= $row['TrangThai'] == '0' ? 'selected' : '' ?>>Đã giao hàng</option>
                                        <option value="1" <?= $row['TrangThai'] == '1' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                        <option value="2" <?= $row['TrangThai'] == '2' ? 'selected' : '' ?>>Đã xác nhận</option>
                                        <option value="3" <?= $row['TrangThai'] == '3' ? 'selected' : '' ?>>Đang giao hàng</option>
                                        <option value="4" <?= $row['TrangThai'] == '4' ? 'selected' : '' ?>>Đã nhận hàng</option>
                                        <option value="5" <?= $row['TrangThai'] == '5' ? 'selected' : '' ?>>Yêu cầu hủy đơn hàng</option>
                                        <option value="6" <?= $row['TrangThai'] == '6' ? 'selected' : '' ?>>Đơn hàng đã được hủy</option>
                                    </select>
                                </td>
                                <td><a href="?action=quanlyloai&query=sua&id=<?php echo $row['id_hd']; ?>"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                <td><input name="ckcl[]" type="checkbox" value="<?php echo $row['id_hd']; ?>" class="Organization_Desg_Check_margin"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.order-status-select').on('change', function() {
        var orderId = $(this).data('order-id');
        var status = $(this).val();
        var statusText = $(this).find("option:selected").text(); // Lấy tên trạng thái
        
        $.ajax({
            url: 'ajax-process.php', // File PHP để xử lý cập nhật
            type: 'POST',
            data: {
                id_hd: orderId,
                trang_thai: status
            },
            success: function(response) {
                if (response == 'success') {
                    $.fancybox.open(`
                        <div style="padding: 20px; text-align: center;">
                            <h3>Thông báo</h3>
                            <p>Mã đơn hàng: <strong>${orderId}</strong></p>
                            <p>Trạng thái đã cập nhật: <strong>${statusText}</strong></p>
                            <button onclick="$.fancybox.close();" class="btn btn-primary mt-2">Đóng</button>
                        </div>
                    `);
                } else {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái.');
                }
            }
        });
    });
});
</script>

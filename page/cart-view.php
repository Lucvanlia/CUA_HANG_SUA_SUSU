<style>
    .checkout__order {
        background-color: white !important;
    }
</style>
<?php

?>
<section class="checkout spad">
    <div class="container">
        <div class="checkout__form">
            <div class="col-lg-12 col-md-6 col-">
                <div class="checkout__order">
                    <div class="checkout__order__products">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <style>
                                        .product-column {
                                            width: 300px;
                                            /* Đặt chiều rộng cho cột "Sản phẩm" */
                                            font-size: 14px;
                                            /* Thay đổi kích thước chữ */
                                        }
                                    </style>
                                    <th scope="col" class="text-left">STT</th>
                                    <th scope="col" class="text-left product-column">Sản phẩm</th>
                                    <th scope="col" class="text-center">Ảnh</th>
                                    <th scope="col" class="text-center">Giá</th>
                                    <th scope="col" class="text-center">Số lượng</th>
                                    <th scope="col" class="text-center">Đơn vị</th> <!-- Cột đơn vị -->
                                    <th scope="col" class="text-right">Thành tiền</th>
                                    <th scope="col" class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tong = 0;
                                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                                    $stt = 1;
                                    foreach ($_SESSION['cart'] as $item) {
                                        // Lấy giá, số lượng và tính thành tiền cho sản phẩm
                                        $gia = $item['GiaBan'];
                                        $soluong = $item['SoLuong'];
                                        $tt = $gia * $soluong;
                                        $id_sp =    $item['id_sp'];
                                        // Truy vấn tên đơn vị từ bảng DonVi dựa trên id_dv
                                        $id_dv = $item['id_dv'];
                                        $query_dv = "SELECT Ten_dv FROM DonVi WHERE id_dv = $id_dv";
                                        $result_dv = mysqli_query($link, $query_dv);
                                        $row_dv = mysqli_fetch_assoc($result_dv);
                                        $ten_dv = $row_dv ? $row_dv['Ten_dv'] : 'Không xác định';
                                        $query_dv = "SELECT * FROM SanPham WHERE id_sp = $id_sp";
                                        $result_dv = mysqli_query($link, $query_dv);
                                        $row_dv = mysqli_fetch_assoc($result_dv);
                                        $ten_sp =  $row_dv ? $row_dv['Ten_sp'] : 'Không xác định';
                                        $hinh =  $row_dv ? $row_dv['Hinh_Nen'] : 'Không xác định';
                                        // Hiển thị thông tin sản phẩm
                                ?>
                                        <tr class="cart-item">
                                            <input type="hidden" class="pid" value="<?= $item['id_sp'] ?>" name="id" />
                                            <input type="hidden" class="status" value="del-item" name="status" />
                                            <th><?= $stt ?></th>
                                            <th scope="row" class="text-left"><?= $ten_sp ?> - <?= $ten_dv ?></th> <!-- Hiển thị tên đơn vị -->
                                            <td class="text-center">
                                                <img class="img-thumbnail" width="150" height="150" src="admin_test/uploads/sanpham/<?= $hinh ?>" alt="Ảnh sản phẩm">
                                            </td>
                                            <td class="text-center"><?= number_format($gia, 0, ',', '.') ?> VNĐ</td>
                                            <td class="text-center">
                                                <input style="width: 50px;" type="number" value="<?= $soluong ?>" class="text-center quantity" oninput="updateCart()" name="soluong" data-id="<?= $item['id_sp'] ?>" data-price="<?= $gia ?>">
                                            </td>
                                            <td class="text-center"><?= $ten_dv ?></td> <!-- Hiển thị tên đơn vị -->
                                            <td class="text-right" id="total-<?= $item['id_sp'] ?>"><?= number_format($tt, 0, ',', '.') ?> VNĐ</td>
                                            <td class="text-right">
                                                <button href="#" class="btn btn-warning cart-del-item" style="color: white;" data-id="<?= $item['id_sp'] ?>">
                                                    <i class="fa-regular fa-trash-can"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                <?php
                                        $tong += $tt;
                                        $stt++;
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="text-center">Giỏ hàng của bạn trống.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>

                        <div class="checkout__order__total">
                            Tổng cộng
                            <span id="tong-tien"><?= number_format($tong, 0, ',', '.') ?> VNĐ</span>
                        </div>

                        <?php
                        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                            echo '<p class="text-right"><a href="index.php?action=cart&query=del-all" class="btn btn-danger">Xóa giỏ hàng</a></p>';
                        } else {
                            echo '<h4>Hãy mua sắm ngay tại đây</h4>';
                        }
                        ?>
                    </div>

                    <!-- Nếu người dùng đã đăng nhập, hiển thị form thanh toán -->
                    <?php
                    if (isset($_SESSION['login-facebook']) || isset($_SESSION["login-google"]) || isset($_SESSION["id_user"])) {
                        echo '<form method="post" id="frmPaying">
                                 <label style="padding: 10px"><input type="radio" value="vnpay" name="payment_method" required>Thanh toán VNPAY <img style="padding: 10px" class="img" width="100px" height="100px" src="img/VNPAY_id-sVSMjm2_1.svg" alt="logovnp"></label> <br>
                                <label style="padding: 10px"><input type="radio" value="cod" name="payment_method" required>Thanh toán khi nhận hàng<img  style="padding: 10px" width="80px" height="80px" src="https://img.icons8.com/?size=100&id=9Ah9p7pS6m8u&format=png&color=000000" alt=""></label>
                                <br>
                                <input type="submit" class="site-btn" value="Đặt hàng ngay" name="order">
                            </form>';
                    } else {
                        echo '<a href="login-main.php">Vui lòng đăng nhập tại đây để thanh toán đơn hàng</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

</script>
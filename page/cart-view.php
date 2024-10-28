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
                                    <th scope="col" class="text-left">STT</th>
                                    <th scope="col" class="text-left">Sản phẩm</th>
                                    <th scope="col" class="text-center">Ảnh</th>
                                    <th scope="col" class="text-center">Giá</th>
                                    <th scope="col" class="text-center">Số lượng</th>
                                    <th scope="col" class="text-right">Thành tiền</th>
                                    <th scope="col" class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tong = 0;
                                if (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
                                    $stt = 1;
                                    foreach ($_SESSION['cart'] as $item) {
                                        // Kiểm tra xem các phần tử tồn tại hay không
                                        $gia = isset($item[2]) ? $item[2] : 0;
                                        $soluong = isset($item[4]) ? $item[4] : 0;
                                        $tt = $gia * $soluong;
                                ?>
                                        <tr class="cart-item">
                                            <input type="hidden" class="pid" value="<?= $item['0'] ?>" name="id" />
                                            <input type="hidden" class="status" value="del-item" name="status" />
                                            <th><?= $stt ?></th>
                                            <th scope="row" class="text-left"><?= $item['1'] ?></th>
                                            <td class="text-center">
                                                <img class="img-thumbnail" width="150" height="150" src="<?= $item['3'] ?>" alt="">
                                            </td>
                                            <!-- Giá tiền sản phẩm -->
                                            <td class="text-center"><?= number_format($item['2'], 0, ',', '.') ?> VNĐ</td>
                                            <!-- Số lượng -->
                                            <td class="text-center">
                                                <input style="width: 50px;" type="number" value="<?= $item['4'] ?>" class="text-center quantity" name="soluong" data-id="<?= $item['0'] ?>" data-price="<?= $item['2'] ?> ">
                                            </td>
                                            <!-- Thành tiền -->
                                            <td class="text-right" id="total-<?= $item['0'] ?>"><?= number_format($item['2'] * $item['4'], 0, ',', '.') ?> VNĐ</td>

                                            <span class="total-price"></span>
                                            </td>
                                            <td class="text-right">
                                                <button href="#" class="btn btn-warning cart-del-item" style="color: white;" data-id="<?= $item['0'] ?>">
                                                    <i class="fa-regular fa-trash-can"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                <?php
                                        $tong += $tt;
                                        $stt++;
                                    } // kết thúc foreach
                                } // kết thúc if
                                ?>
                            </tbody>
                        </table>

                        <div class="checkout__order__total">Tổng cộng
                            <span id="tong-tien"><?= isset($_SESSION['tong_tien']) ? number_format($_SESSION['tong_tien'], 0, ',', '.') : 0 ?> VNĐ</span>
                            <div class="total">
                            </div>
                        </div>
                        <?php
                        if (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
                            echo '<p class= "text-right"><a  href="index.php?action=cart&query=del-all" class="btn btn-danger">Xóa giỏ hàng</a></p>';
                        } else {
                            echo '<h4>Hãy mua sắm ngay tại đây</h4>';
                        }
                        ?>

                        <p>Lorem ipsum dolor sit amet, consectetur adip elit, sed do eiusmod tempor incididunt
                            ut labore et dolore magna aliqua.</p>
                        <div class="checkout__input__checkbox">



                        </div>
                    </div>
                    <?php
                    if (isset($_SESSION['login-facebook']) || isset($_SESSION["login-google"]) || isset($_SESSION["login"])) {
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
                <form method="post" action="?action=cart&query=vnpay">
                    <input type="submit" class="site-btn" value="Đặt hàng ngayVNPAY" name="order-vnpay">
                </form>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- Checkout Section End -->
<script>
</script>
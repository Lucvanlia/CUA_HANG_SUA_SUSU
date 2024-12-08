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
                <div class="checkout__order" id="cart-table">
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
                                        $max_quantity = $item['max_quantity'];
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
                                                <input style="width: 50px;" type="number" value="<?= $soluong ?>" class="text-center quantity" data-unit="<?= $id_dv ?>" name="soluong" data-id="<?= $item['id_sp'] ?>" data-price="<?= $gia ?>" max="<?= $max_quantity ?>" min="1">
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
                            echo ' <button style="width: 20% !important" id = "btnCheckOut" class = "btn btn-success">Thanh toán</button>';
                        } else {
                            echo '<h4>Hãy mua sắm ngay tại đây</h4>';
                        }
                        ?>
                    </div>


                </div>
                <div id="checkout-form" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Thông tin khách hàng</h4>
                            <form id="frmPaying">
                                <input type="hidden" name="status" value="Check-Out">
                                <div class="form-group">
                                    <label for="name">Tên</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Tên khách hàng" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Địa chỉ" required>
                                </div>
                                <div class="form-group">
                                    <label for="voucher">Địa chỉ</label>
                                    <input type="text" class="form-control" id="voucher" name="voucher" placeholder="Mã khuyễn mãi" required>
                                    <button type="submit" id="CheckVoucher" class="btn btn-primary">Nhập mã</button>
                                </div>
                                <h4>Chọn phương thức thanh toán</h4>
                                <div class="form-group">
                                    <label style="padding: 10px"><input type="radio" value="vnpay" name="payment_method" required>Thanh toán VNPAY <img style="padding: 10px" class="img" width="100px" height="100px" src="img/VNPAY_id-sVSMjm2_1.svg" alt="logovnp"></label> <br>
                                    <label style="padding: 10px"><input type="radio" value="cod" name="payment_method" required>Thanh toán khi nhận hàng<img style="padding: 10px" width="80px" height="80px" src="https://img.icons8.com/?size=100&id=9Ah9p7pS6m8u&format=png&color=000000" alt=""></label>
                                    <br>
                                </div>

                                <button type="submit" class="btn btn-primary">Đặt hàng ngay</button>
                            </form>
                        </div>
                        <div class="col-md-6" id="product-cartcheckout">
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
                                        <th scope="col" class="text-center">Giá</th>
                                        <th scope="col" class="text-center">Số lượng</th>
                                        <th scope="col" class="text-center">Đơn vị</th> <!-- Cột đơn vị -->
                                        <th scope="col" class="text-right">Thành tiền</th>
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
                                            $max_quantity = $item['max_quantity'];
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

                                                <td class="text-center"><?= number_format($gia, 0, ',', '.') ?> VNĐ</td>
                                                <td class="text-center">
                                                    <input style="width: 50px;" type="number" value="<?= $soluong ?>" class="text-center quantity" data-unit="<?= $id_dv ?>" name="soluong" data-id="<?= $item['id_sp'] ?>" data-price="<?= $gia ?>" max="<?= $max_quantity ?>" min="1">
                                                </td>
                                                <td class="text-center"><?= $ten_dv ?></td> <!-- Hiển thị tên đơn vị -->
                                                <td class="text-right" id="total-<?= $item['id_sp'] ?>"><?= number_format($tt, 0, ',', '.') ?> VNĐ</td>

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
                            <div id="discount-message"></div>
                            <div>
                                <p>Tổng trước khi giảm: <span id="tong-tien"><?= number_format($tong, 0, ',', '.') ?> VNĐ</span></p>
                            </div>
                            <div>
                                <p>Tổng số tiền khuyến mãi sản phẩm:</p>
                                <div id="product_discounts"></div>
                            </div>

                            <div>
                                <p>Tổng số tiền khuyến mãi hóa đơn:</p>
                                <div id="bill_discount_message"></div>
                            </div>

                            <div>
                                <p>Tổng số tiền giảm: <span id="total_discount"></span></p>
                                <p>Số tiền mua hàng: <span id="total_before_discount"></span></p>
                                <hr>
                             <p> <strong>Số tiền sau khi giảm:</strong> <span id="total_after_discount"></span></p>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Thanh Toán (ẩn khi chưa nhấn nút Thanh toán) -->


</section>
<script>
    $(document).ready(function() {
        $(document).on("click", "#CheckVoucher", function(e) {
            e.preventDefault();
            const voucherCode = $("#voucher").val();

            $.ajax({
                url: "checkvoucher.php", // Đường dẫn file PHP xử lý
                type: "POST",
                dataType: "json",
                data: {
                    voucher: voucherCode
                },
                success: function(response) {
                    if (response.status === "success") {
                        $("#discount-message").html(response.discount_message);
                        $('#total_before_discount').text(response.total_before);

                        // Hiển thị giảm giá cho sản phẩm
                        $('#product_discounts').html('');
                        response.product_discounts.forEach(function(discount) {
                            $('#product_discounts').append('<p>' + discount + '</p>');
                        });

                        // Hiển thị giảm giá cho hóa đơn
                        $('#bill_discount_message').html(response.bill_discount_message);

                        // Hiển thị tổng tiền giảm
                        $('#total_discount').text(response.discount);

                        // Hiển thị tổng tiền sau giảm
                        $('#total_after_discount').text(response.total_after);

                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Chi tiết lỗi:", xhr.responseText); // Ghi log chi tiết
                    let errorMessage = "Có lỗi xảy ra, vui lòng thử lại.";
                    if (xhr.responseText) {
                        errorMessage = xhr.responseText; // Hiển thị nội dung lỗi từ PHP
                    }
                    alert(errorMessage);
                },
            });
        });

    });
</script>
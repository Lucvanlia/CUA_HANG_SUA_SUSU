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

                                <h4>Chọn phương thức thanh toán</h4>
                                <div class="form-group">
                                    <label style="padding: 10px"><input type="radio" value="vnpay" name="payment_method" required>Thanh toán VNPAY <img style="padding: 10px" class="img" width="100px" height="100px" src="img/VNPAY_id-sVSMjm2_1.svg" alt="logovnp"></label> <br>
                                    <label style="padding: 10px"><input type="radio" value="cod" name="payment_method" required>Thanh toán khi nhận hàng<img style="padding: 10px" width="80px" height="80px" src="https://img.icons8.com/?size=100&id=9Ah9p7pS6m8u&format=png&color=000000" alt=""></label>
                                    <br>
                                </div>

                                <button type="submit" class="btn btn-primary">Đặt hàng ngay</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h4>Thông tin sản phẩm đã mua</h4>
                            <ul id="product-list">
                                <!-- Sản phẩm sẽ hiển thị ở đây -->
                            </ul>
                            <hr>
                            <h5>Tổng tiền: <span id="total-price">0 VNĐ</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Thanh Toán (ẩn khi chưa nhấn nút Thanh toán) -->


</section>

<script>
    $(document).on("click", "#btnCheckOut", function() {
        // Ẩn giỏ hàng
        $("#cart-table").hide();

        // Hiển thị form thanh toán
        $("#checkout-form").show();

        // Kiểm tra xem người dùng đã đăng nhập chưa (có tồn tại id_user trong session)
        var userId = <?= isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 'null'; ?>;

        if (userId) {
            // Lấy thông tin người dùng từ bảng KhachHang và điền vào form
            $.ajax({
                url: 'ajax-process.php',
                method: 'post',
                data: {
                    status: 'get-user-info',
                    id_user: userId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        // Điền thông tin vào form
                        $('#name').val(data.name);
                        $('#email').val(data.email);
                        $('#phone').val(data.phone);
                        $('#address').val(data.address);
                    } else {
                        alert('Không thể lấy thông tin người dùng.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        }

        // Hiển thị thông tin sản phẩm đã mua
        var cart = <?= json_encode($_SESSION['cart']); ?>;
        var productList = '';
        var totalPrice = 0;

        cart.forEach(function(item) {
            var lineThrough = item.GiaBan * item.SoLuong;
            <?php
            $query_dv = "SELECT * FROM SanPham WHERE id_sp = $id_sp";
            $result_dv = mysqli_query($link, $query_dv);
            $row_dv = mysqli_fetch_assoc($result_dv);
            $ten_sp =  $row_dv ? $row_dv['Ten_sp'] : 'Không xác định';
            ?>
            productList += `<li><?= $ten_sp ?> - Số lượng: ${item.SoLuong} - <span>${item.GiaBan} VNĐ</span> - Thành tiền: ${lineThrough} VNĐ</li>`;
            totalPrice += lineThrough;
        });

        $('#product-list').html(productList);

        // Hiển thị tổng tiền
        $('#total-price').text(totalPrice.toLocaleString() + " VNĐ");
    });


    $(document).on("change", ".quantity", function() {
        var quantity = $(this).val();
        var id_sp = $(this).data("id");
        var id_dv = $(this).data("unit");
        var status = "update-quantity";

        $.ajax({
            url: 'ajax-process.php',
            method: 'POST',
            data: {
                id_sp: id_sp,
                id_dv: id_dv,
                quantity: quantity,
                status: status
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.status === "success") {
                        // alert(data.message);
                        location.reload();
                        $("#total-" + id_sp).text(data.subtotal);
                        $("#tong-tien").text(data.total);
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error("JSON Parse Error: ", error);
                    console.log(response);
                    alert("Có lỗi xảy ra, vui lòng thử lại.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
            }
        });
    });



    $(document).ready(function() {
        $(".cart-del-item").click(function(e) {
            e.preventDefault();
            var $button = $(this); // Lưu lại button được click
            var id = $button.closest("tr").find(".pid").val(); // Lấy id sản phẩm từ input ẩn

            $.ajax({
                url: 'ajax-process.php',
                method: 'post',
                data: {
                    id: id, // Truyền id sản phẩm
                    status: 'del-item' // Tình trạng xóa sản phẩm
                },
                success: function(data) {
                    try {
                        var response = JSON.parse(data); // Parse dữ liệu trả về
                        if (response.status === "success") {
                            $button.closest("tr").remove(); // Xóa phần tử tr chứa sản phẩm

                            // Cập nhật tổng tiền mới trên giao diện
                            $("#tong-tien").text(response.total.toLocaleString('vi-VN') + " VNĐ");

                            // Nếu giỏ hàng trống, hiển thị thông báo
                            if (response.cartEmpty) {
                                $(".checkout__order__products").html("<p>Giỏ hàng của bạn hiện đang trống.</p>");
                            }
                        } else {
                            alert("Lỗi: " + response.message); // Hiển thị thông báo chi tiết từ máy chủ
                        }
                    } catch (e) {
                        console.error("Error parsing response:", e);
                        alert("Đã xảy ra lỗi khi xử lý dữ liệu.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    alert("Lỗi kết nối với máy chủ.");
                }
            });
        });
    });
    $(document).ready(function() {
        $('#frmPaying').on('submit', function(event) {
            event.preventDefault();

            var paymentMethod = $('input[name="payment_method"]:checked').val(); // Lấy phương thức thanh toán được chọn

            if (!paymentMethod) {
                alert("Vui lòng chọn phương thức thanh toán.");
                return; // Ngừng thực hiện nếu không chọn phương thức thanh toán
            }

            var formData = new FormData(this);

            $.ajax({
                url: 'ajax-process.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.trim() === "success_cod") {
                        Fancybox.show([{
                            src: `
                        <div style="padding: 20px; text-align: center;">
                            <img src="img/verified.gif" width="50" height="50" alt="Verified">
                            <h3>Thông báo</h3>
                            <p>Cảm ơn bạn đã đặt hàng</p>
                            <button onclick="location.reload();;" class="btn btn-primary mt-2">Đóng</button>
                        </div>`,
                            type: 'html',
                        }]); // Redirect hoặc cập nhật giao diện nếu cần
                    } else if (response.trim() === "success_vnpay") {
                        window.location.href = "https://banhangviet-tmi.net/doan_php/index.php?action=cart&query=vnpay";
                    } else {
                        alert("Chú ý: " + response);
                    }
                },
                error: function() {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                }
            });
        });
    });
</script>
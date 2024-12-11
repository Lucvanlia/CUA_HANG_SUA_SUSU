<footer class="footer spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer__about">
                    <div class="footer__about__logo">
                    </div>
                    <ul>
                        <li>Địa chỉ: 15 Trần Văn Trà Phú Mỹ Hưng, Tp. Hồ Chí Minh </li>
                        <li>Hotline: 0762323108</li>
                        <li>Email: cuahangsusu@gmail.com</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                <h6>Nhà Cung Cấp Hàng Đầu và Danh Mục</h6>

                <div class="row">
                    <?php
                    // Truy vấn để lấy danh sách nhà cung cấp
                    $nccQuery = "SELECT id_ncc, Ten_ncc, Hinh_ncc FROM NhaCungCap WHERE Hoatdong = 0";
                    $nccResult = mysqli_query($link, $nccQuery);

                    // Kiểm tra và hiển thị nhà cung cấp
                    if ($nccResult && mysqli_num_rows($nccResult) > 0) {
                        while ($ncc = mysqli_fetch_assoc($nccResult)) {
                    ?>
                            <div class="col-md-4">
                                <div class="ncc-item">
                                 <a href="?action=product&query=all&id_ncc=<?= $ncc['id_ncc'] ?>">   <img src="admin_test/uploads/nhacungcap/<?= $ncc['Hinh_ncc'] ?>" alt="<?= $ncc['Ten_ncc'] ?>" class="ncc-img"></a>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>

                    <!-- Hiển thị danh mục -->
                    <div class="col-md-8">
                        <h6>Danh Mục</h6>
                        <ul>
                            <?php
                            // Truy vấn để lấy danh sách danh mục
                            $dmQuery = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE Hoatdong = 0 and parent_dm != 0  LIMIT 5";
                            $dmResult = mysqli_query($link, $dmQuery);

                            if ($dmResult && mysqli_num_rows($dmResult) > 0) {
                                while ($dm = mysqli_fetch_assoc($dmResult)) {
                                    echo '<li><a href="?action=product&query=all&category_id=' . $dm['id_dm'] . '">' . $dm['Ten_dm'] . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-md-12">
                <div class="footer__widget">
                    <div id="google_translate_element"></div>

                    <h6>Join Our Newsletter Now</h6>

                    <p>Đến với cửa hàng SuSu ngay.</p>
                    <form action="#">
                        <input type="text" placeholder="Nhập Email">
                        <button type="submit" class="site-btn">Gửi</button>
                    </form>
                    <div class="footer__widget__social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div id="google_translate_element"></div>


    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Thông tin sản phẩm</h1>
                </div>
                <div class="modal-body">
                    <div class="row mt-2 p-3">
                        <div class="col-md-6">
                            <img src="" alt="" width="100%" height="300px" class="img-fluid rounded" alt="Hinh San Pham">
                        </div>
                        <div class="col-md-6">
                            <h5 id="Ten_SP"></h5>
                            <span>Giá:</span> <strong><span id="gia"></span></strong>
                            <select name="donvi" id="donvi" class="form-select mt-2"></select>

                            <div class="mt-2">
                                <label for="quantity">Số lượng:</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="hidden" id="id_sp">
                    <button type="button" class="btn btn-success" id="btnAddToCart">Thêm giỏ hàng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- </footer>    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script> -->
    <script>
        $(document).on('click', '#btnDetail', function() {
            var productId = $(this).data('id'); // Lấy ID sản phẩm từ nút
            $('#id_sp').val(productId); // Gán giá trị vào input hidden
            $.ajax({
                url: 'test.php',
                type: 'POST',
                data: {
                    action: 'get-detail',
                    id_sp: productId
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parse JSON trả về
                    if (data.status === 'success' && data.data) {
                        var product = data.data;

                        // Điền thông tin vào modal
                        $('#exampleModal img').attr('src', 'admin_test/uploads/sanpham/' + product.Hinh_Nen); // Đường dẫn ảnh
                        $('#Ten_SP').text(product.Ten_sp); // Tên sản phẩm
                        $('#gia').text(product.GiaBan + ' VND'); // Giá sản phẩm mặc định

                        // Tạo danh sách đơn vị và hiển thị số lượng
                        var unitOptions = '';
                        $.each(product.donvi, function(index, dv) {
                            unitOptions += `<option value="${dv.id_dv}" data-price="${dv.GiaBan}" data-quantity="${dv.SoLuong}">
                        ${dv.Ten_dv} (${dv.SoLuong})
                    </option>`;
                        });
                        $('#donvi').html(unitOptions); // Cập nhật danh sách đơn vị

                        // Cập nhật số lượng mua tối đa
                        var firstUnit = product.donvi[0]; // Lấy đơn vị đầu tiên
                        $('#quantity').attr('max', firstUnit.SoLuong).val(1); // Đặt giá trị mặc định và giới hạn

                        // Hiển thị modal
                        $('#exampleModal').modal('show');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra.');
                    }
                },
                error: function() {
                    alert('Không thể tải thông tin sản phẩm.');
                }
            });
        });

        // Cập nhật giá và số lượng khi thay đổi đơn vị
        $(document).on('change', '#donvi', function() {
            var selectedOption = $(this).find(':selected'); // Lấy option được chọn
            var newPrice = selectedOption.data('price'); // Lấy giá từ data-price
            var maxQuantity = selectedOption.data('quantity'); // Lấy số lượng từ data-quantity

            $('#gia').text(newPrice + ' VND'); // Cập nhật giá hiển thị
            $('#quantity').attr('max', maxQuantity).val(1); // Cập nhật giới hạn số lượng
        });
        $('#quantity').on('input', function() {
            var maxQuantity = parseInt($(this).attr('max')); // Lấy số lượng tối đa
            var currentQuantity = parseInt($(this).val());

            if (currentQuantity > maxQuantity) {
                $(this).val(maxQuantity); // Đặt lại giá trị nếu vượt quá
            } else if (currentQuantity < 1) {
                $(this).val(1); // Không cho phép nhỏ hơn 1
            }
        });
        $('#btnAddToCart').on('click', function() {
            var id_sp = $('#id_sp').val(); // Gán giá trị vào input hidden        ;
            var id_dv = $('#donvi').val();
            var quantity = parseInt($('#quantity').val());
            var max_quantity = parseInt($('#quantity').attr('max'));
            var price = $('#donvi option:selected').data('price');
            var donvi = $('#donvi option:selected').data('price');
            console.log('ID Sản phẩm:', id_sp);
            console.log('ID Đơn vị:', id_dv);
            console.log('Số lượng:', quantity);
            console.log('Số lượng tối đa:', max_quantity);
            console.log('Giá bán:', price);

            $.ajax({
                url: 'ajax-process.php',
                type: 'POST',
                data: {
                    status: 'add_to_cart',
                    id_sp: id_sp,
                    id_dv: id_dv,
                    quantity: quantity,
                    max_quantity: max_quantity,
                    price: price
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        $.fancybox.open('<div class="message">' + data.message + '</div>');
                    } else {
                        $.fancybox.open('<div class="message">' + data.message + '</div>');
                    }
                },
                error: function() {
                    $.fancybox.open('<div class="message">Đã xảy ra lỗi, vui lòng thử lại.</div>');
                }
            });
        });
    </script>
    <!-- <?php var_dump($_SESSION['cart']) ?> -->
    <script>
        $(document).on("click", "#OffCheckVoucher", function() {
            // Ẩn giỏ hàng
            $("#cart-table").show();

            // Hiển thị form thanh toán
            $("#checkout-form").hide();


        });

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
                            if (autoLoadVoucher && voucherCode !== "") {
                                checkVoucher(voucherCode);
                            }
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

            // Lặp qua giỏ hàng để hiển thị sản phẩm
            cart.forEach(function(item) {
                var lineThrough = item.GiaBan * item.SoLuong;
                productList += `<tr>
            <td>${item.Ten_sp}</td>
            <td>${item.SoLuong}</td>
            <td>${item.GiaBan.toLocaleString()} VNĐ</td>
            <td>${lineThrough.toLocaleString()} VNĐ</td>
        </tr>`;
                totalPrice += lineThrough;
            });

            // Thêm các sản phẩm vào bảng giỏ hàng
            $('#cart-items').html(productList);

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
                            // location.reload();
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
                                location.reload();
                                // Nếu giỏ hàng trống, hiển thị thông báo
                                if (response.cartEmpty) {
                                    $(".checkout__order__products").html("<p>Giỏ hàng của bạn hiện đang trống.</p>");
                                    <?php
                                    unset($_SESSION['discount']);
                                    unset($_SESSION['MAKM']);
                                    ?>
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
        $(document).ready(function() {
            // Hàm cập nhật số lượng sản phẩm trong giỏ hàng
            function updateCartCount() {
                $.ajax({
                    url: 'Dem-sanpham.php', // Đường dẫn đến API
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Hiển thị số lượng sản phẩm
                        $('#order-count').text(response.count || 0);
                    },
                    error: function() {
                        console.error("Không thể lấy số lượng sản phẩm trong giỏ hàng.");
                    }
                });
            }

            // Gọi hàm ngay khi trang được load
            updateCartCount();

            // Tùy chỉnh: Nếu bạn có các sự kiện thêm/xóa sản phẩm, gọi lại hàm này
            $(document).on('cart-updated', function() {
                updateCartCount();
            });
        });
    </script><!-- jQuery (phải được tải trước Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Bootstrap Bundle (chứa cả Popper.js và JS của Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
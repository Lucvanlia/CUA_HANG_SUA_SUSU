<footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                        </div>
                        <ul>
                            <li>Address: 60-49 Road 11378 New York</li>
                            <li>Phone: +65 11.188.888</li>
                            <li>Email: hello@colorlib.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    <div class="footer__widget">

                        <h6>Useful Links</h6>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">About Our Shop</a></li>
                            <li><a href="#">Secure Shopping</a></li>
                            <li><a href="#">Delivery infomation</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Our Sitemap</a></li>
                        </ul>
                        <ul>
                            <li><a href="#">Who We Are</a></li>
                            <li><a href="#">Our Services</a></li>
                            <li><a href="#">Projects</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Innovation</a></li>
                            <li><a href="#">Testimonials</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <div id="google_translate_element"></div>

                        <h6>Join Our Newsletter Now</h6>

                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#">
                            <input type="text" placeholder="Enter your mail">
                            <button type="submit" class="site-btn">Subscribe</button>
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
            <p>hzxczvalsdakdalsdjkalljsdaksjdaksdjaksldjsalkdjaslkjdlellllo</p>
        <div id="google_translate_element"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright">
                        <div class="footer__copyright__text"><p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright ©<script>document.write(new Date().getFullYear());</script>2024 All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p></div>
                        <div class="footer__copyright__payment"><img src="img/payment-item.png" alt=""></div>
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


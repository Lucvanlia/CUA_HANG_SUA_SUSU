<div class="col-lg-12">
    <div class="section-title">
        <h2>Thế Giới Sữa</h2>
    </div>
    <section class="section-product product-1">
        <div class="container">
            <div class="block-content">
                <div class="row">
                    <!-- Danh mục bên trái -->
                    <div class="col-xl-2 col-12 block-title-cate">
                        <div class="block-title">
                            <!-- Danh mục con -->
                            <ul class="list-unstyled block-cate">
                                <?php
                                $sql_dm_con = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE parent_dm = 102 AND Hoatdong = 0";
                                $result_dm_con = mysqli_query($link, $sql_dm_con);

                                while ($dm_con = mysqli_fetch_assoc($result_dm_con)) {
                                    $id_dm_con = $dm_con['id_dm'];
                                    $ten_dm_con = $dm_con['Ten_dm'];

                                ?>
                                    <li>
                                        <a style="color: #fff !important;" href="category.php?id=<?= $id_dm_con ?>" title="<?= $ten_dm_con ?>"><?= $ten_dm_con ?></a>
                                    </li>
                                <?php } ?>
                            </ul>

                            <!-- Hình nhà cung cấp -->
                            <div class="block-vendor mt-3 ">
                                <?php
                                $sql_ncc = "SELECT DISTINCT NCC.Hinh_ncc 
                                            FROM NhaCungCap NCC
                                            JOIN SanPham SP ON NCC.id_ncc = SP.id_ncc
                                            WHERE SP.id_dm IN (SELECT id_dm FROM DanhMuc WHERE parent_dm = 102) 
                                            AND NCC.Hoatdong = 0 LIMIT 5";
                                $result_ncc = mysqli_query($link, $sql_ncc);

                                while ($ncc = mysqli_fetch_assoc($result_ncc)) {
                                    $hinh_ncc = $ncc['Hinh_ncc'];
                                ?>
                                    <a href="#">
                                        <img src="admin_test/uploads/nhacungcap/<?= $hinh_ncc ?>" alt="Nhà cung cấp" class="rounded-circle" style="width: 100px; height: 50px; object-fit: cover;">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner quảng cáo -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12 block-banner">
                        <div class="banner">
                            <a href="promotion.php" title="Banner quảng cáo">
                                <img src="https://bizweb.dktcdn.net/100/416/540/themes/839121/assets/img_product_banner_1.jpg?1731912787039" alt="Banner quảng cáo" class="banner-img">
                            </a>
                        </div>
                    </div>

                    <!-- Sản phẩm -->
                    <div class="col-xl-7 col-lg-8 col-md-8 col-12 block-product">
                        <div class="row">
                            <?php
                            // Lấy sản phẩm từ các danh mục con
                            $sql_sp = "
                              SELECT SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, MAX(DG.GiaBan) AS GiaBan, SP.id_dm
                                FROM SanPham SP
                                LEFT JOIN DonGia DG ON SP.id_sp = DG.id_sp
                                WHERE SP.id_dm IN (SELECT id_dm FROM DanhMuc WHERE parent_dm = 102) 
                                AND SP.Hoatdong = 0 
                                AND DG.Hoatdong = 0
                                GROUP BY SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, SP.id_dm
                                LIMIT 6;
                                ;
                                ";
                            $result_sp = mysqli_query($link, $sql_sp);

                            while ($sp = mysqli_fetch_assoc($result_sp)) {
                                $id_sp = $sp['id_sp'];
                                $ten_sp = $sp['Ten_sp'];
                                $hinh_nen = $sp['Hinh_Nen'];
                                $gia_ban = $sp['GiaBan'] > 0 ? number_format($sp['GiaBan'], 0, ',', '.') . "₫" : "Liên hệ";
                            ?>
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-img-container">
                                            <img src="admin_test/uploads/sanpham/<?= $hinh_nen ?>" class="card-img-top zoom-on-hover" alt="<?= $ten_sp ?>" style="height: 200px; object-fit: cover;">
                                        </div>
                                        <div class="card-body text-center">
                                            <a href="index.php?action=product&query=details&id=<?php echo $id_sp ?>">
                                                <h6 class="card-title"><?= $ten_sp ?></h6>
                                            </a>
                                            <p class="text-danger fw-bold"><?= $gia_ban ?></p>
                                        </div>
                                        <button type="button" id="btnDetail" class="btn btn-success" data-id="<?= $id_sp ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Mua ngay
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>
<div class="col-lg-12">
    <div class="section-title">
        <h2>Tăng chiều cao</h2>
    </div>
    <section class="section-product product-1">
        <div class="container">
            <div class="block-content">
                <div class="row">


                    <!-- Sản phẩm -->
                    <div class="col-xl-7 col-lg-8 col-md-8 col-12 block-product">
                        <div class="row">
                            <?php
                            // Lấy sản phẩm từ các danh mục con
                            $sql_sp = "
                              SELECT SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, MAX(DG.GiaBan) AS GiaBan, SP.id_dm
                                FROM SanPham SP
                                LEFT JOIN DonGia DG ON SP.id_sp = DG.id_sp
                                WHERE SP.id_dm IN (SELECT id_dm FROM DanhMuc WHERE parent_dm = 109) 
                                AND SP.Hoatdong = 0 
                                AND DG.Hoatdong = 0
                                GROUP BY SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, SP.id_dm
                                LIMIT 6;
                                ;
                                ";
                            $result_sp = mysqli_query($link, $sql_sp);

                            while ($sp = mysqli_fetch_assoc($result_sp)) {
                                $id_sp = $sp['id_sp'];
                                $ten_sp = $sp['Ten_sp'];
                                $hinh_nen = $sp['Hinh_Nen'];
                                $gia_ban = $sp['GiaBan'] > 0 ? number_format($sp['GiaBan'], 0, ',', '.') . "₫" : "Liên hệ";
                            ?>
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-img-container">
                                            <img src="admin_test/uploads/sanpham/<?= $hinh_nen ?>" class="card-img-top zoom-on-hover" alt="<?= $ten_sp ?>" style="height: 200px; object-fit: cover;">
                                        </div>
                                        <div class="card-body text-center">
                                            <a href="index.php?action=product&query=details&id=<?php echo $id_sp ?>">
                                                <h6 class="card-title"><?= $ten_sp ?></h6>
                                            </a>
                                            <p class="text-danger fw-bold"><?= $gia_ban ?></p>
                                        </div>
                                        <button type="button" id="btnDetail" class="btn btn-success" data-id="<?= $id_sp ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Mua ngay
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12 block-banner">
                        <div class="banner">
                            <a href="promotion.php" title="Banner quảng cáo">
                                <img src="https://bizweb.dktcdn.net/100/416/540/themes/839121/assets/img_product_banner_1.jpg?1731912787039" alt="Banner quảng cáo" class="banner-img">
                            </a>
                        </div>
                    </div>
                    <!-- Danh mục bên trái -->
                    <div class="col-xl-2 col-12 block-title-cate">
                        <div class="block-title">
                            <!-- Danh mục con -->
                            <ul class="list-unstyled block-cate">
                                <?php
                                $sql_dm_con = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE parent_dm = 109 AND Hoatdong = 0";
                                $result_dm_con = mysqli_query($link, $sql_dm_con);

                                while ($dm_con = mysqli_fetch_assoc($result_dm_con)) {
                                    $id_dm_con = $dm_con['id_dm'];
                                    $ten_dm_con = $dm_con['Ten_dm'];

                                ?>
                                    <li>
                                        <a style="color: #fff !important;" href="category.php?id=<?= $id_dm_con ?>" title="<?= $ten_dm_con ?>"><?= $ten_dm_con ?></a>
                                    </li>
                                <?php } ?>
                            </ul>

                            <!-- Hình nhà cung cấp -->
                            <div class="block-vendor mt-3 ">
                                <?php
                                $sql_ncc = "SELECT DISTINCT NCC.Hinh_ncc 
                                            FROM NhaCungCap NCC
                                            JOIN SanPham SP ON NCC.id_ncc = SP.id_ncc
                                            WHERE SP.id_dm IN (SELECT id_dm FROM DanhMuc WHERE parent_dm = 109) 
                                            AND NCC.Hoatdong = 0 LIMIT 5";
                                $result_ncc = mysqli_query($link, $sql_ncc);

                                while ($ncc = mysqli_fetch_assoc($result_ncc)) {
                                    $hinh_ncc = $ncc['Hinh_ncc'];
                                ?>
                                    <a href="#">
                                        <img src="admin_test/uploads/nhacungcap/<?= $hinh_ncc ?>" alt="Nhà cung cấp" class="rounded-circle" style="width: 100px; height: 50px; object-fit: cover;">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner quảng cáo -->

                </div>
            </div>
        </div>
    </section>


</div>
<div class="col-lg-12">
    <div class="section-title">
        <h2>Tả Bỉm Cho Bé</h2>
    </div>
    <section class="section-product product-1">
        <div class="container">
            <div class="block-content">
                <div class="row">
                    <!-- Danh mục bên trái -->
                    <div class="col-xl-2 col-12 block-title-cate">
                        <div class="block-title">
                            <!-- Danh mục con -->
                            <ul class="list-unstyled block-cate">
                                <?php
                                $sql_dm_con = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE parent_dm = 102 AND Hoatdong = 0";
                                $result_dm_con = mysqli_query($link, $sql_dm_con);

                                while ($dm_con = mysqli_fetch_assoc($result_dm_con)) {
                                    $id_dm_con = $dm_con['id_dm'];
                                    $ten_dm_con = $dm_con['Ten_dm'];

                                ?>
                                    <li>
                                        <a style="color: #fff !important;" href="category.php?id=<?= $id_dm_con ?>" title="<?= $ten_dm_con ?>"><?= $ten_dm_con ?></a>
                                    </li>
                                <?php } ?>
                            </ul>

                            <!-- Hình nhà cung cấp -->
                            <div class="block-vendor mt-3 ">
                                <?php
                                $sql_ncc = "SELECT DISTINCT NCC.Hinh_ncc 
                                            FROM NhaCungCap NCC
                                            JOIN SanPham SP ON NCC.id_ncc = SP.id_ncc
                                            WHERE SP.id_dm IN (SELECT id_dm FROM DanhMuc WHERE parent_dm = 107) 
                                            AND NCC.Hoatdong = 0 LIMIT 5";
                                $result_ncc = mysqli_query($link, $sql_ncc);

                                while ($ncc = mysqli_fetch_assoc($result_ncc)) {
                                    $hinh_ncc = $ncc['Hinh_ncc'];
                                ?>
                                    <a href="#">
                                        <img src="admin_test/uploads/nhacungcap/<?= $hinh_ncc ?>" alt="Nhà cung cấp" class="rounded-circle" style="width: 100px; height: 50px; object-fit: cover;">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Banner quảng cáo -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12 block-banner">
                        <div class="banner">
                            <a href="promotion.php" title="Banner quảng cáo">
                                <img src="https://bizweb.dktcdn.net/100/416/540/themes/839121/assets/img_product_banner_1.jpg?1731912787039" alt="Banner quảng cáo" class="banner-img">
                            </a>
                        </div>
                    </div>

                    <!-- Sản phẩm -->
                    <div class="col-xl-7 col-lg-8 col-md-8 col-12 block-product">
                        <div class="row">
                            <?php
                            // Lấy sản phẩm từ các danh mục con
                            $sql_sp = "
                              SELECT SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, MAX(DG.GiaBan) AS GiaBan, SP.id_dm
                                FROM SanPham SP
                                LEFT JOIN DonGia DG ON SP.id_sp = DG.id_sp
                                WHERE SP.id_dm IN (SELECT id_dm FROM DanhMuc WHERE parent_dm = 107) 
                                AND SP.Hoatdong = 0 
                                AND DG.Hoatdong = 0
                                GROUP BY SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, SP.id_dm
                                LIMIT 6;
                                ;
                                ";
                            $result_sp = mysqli_query($link, $sql_sp);

                            while ($sp = mysqli_fetch_assoc($result_sp)) {
                                $id_sp = $sp['id_sp'];
                                $ten_sp = $sp['Ten_sp'];
                                $hinh_nen = $sp['Hinh_Nen'];
                                $gia_ban = $sp['GiaBan'] > 0 ? number_format($sp['GiaBan'], 0, ',', '.') . "₫" : "Liên hệ";
                            ?>
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-img-container">
                                            <img src="admin_test/uploads/sanpham/<?= $hinh_nen ?>" class="card-img-top zoom-on-hover" alt="<?= $ten_sp ?>" style="height: 200px; object-fit: cover;">
                                        </div>
                                        <div class="card-body text-center">
                                            <a href="index.php?action=product&query=details&id=<?php echo $id_sp ?>">
                                                <h6 class="card-title"><?= $ten_sp ?></h6>
                                            </a>
                                            <p class="text-danger fw-bold"><?= $gia_ban ?></p>
                                        </div>
                                        <button type="button" id="btnDetail" class="btn btn-success" data-id="<?= $id_sp ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Mua ngay
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>



<style>
    /* Kiểu dáng danh sách */
    .block-cate {
        margin: 0;
        /* Loại bỏ khoảng cách mặc định của ul */
        padding: 0;
        /* Loại bỏ padding mặc định của ul */
        list-style: none;
        /* Ẩn dấu chấm danh sách */
    }

    .block-cate li {
        background-color: #7fad39;
        /* Nền xanh cho li */
        border-radius: 10px;
        /* Bo tròn góc */
        margin-bottom: 15px;
        /* Khoảng cách giữa các li */
        overflow: hidden;
        /* Đảm bảo không tràn viền khi bo tròn */
        transition: transform 0.3s ease, background-color 0.3s ease;
        /* Hiệu ứng mượt */
    }

    .block-cate li:last-child {
        margin-bottom: 0;
        /* Loại bỏ khoảng cách của mục cuối cùng */
    }

    /* Kiểu dáng link trong li */
    .block-cate li a {
        display: block;
        /* Đảm bảo a chiếm toàn bộ không gian li */
        color: white;
        /* Màu chữ trắng */
        text-decoration: none;
        /* Xóa gạch chân */
        padding: 10px 15px;
        /* Khoảng cách chữ với viền nền */
        font-size: 20px;
        /* Kích thước chữ */
        font-weight: bold;
        /* Chữ đậm */
    }

    /* Hiệu ứng hover cho li */
    .block-cate li:hover {
        background-color: #66a531;
        /* Đổi màu nền khi hover */
        transform: scale(1.05);
        /* Phóng to nhẹ toàn bộ li */
    }

    .block-cate li a:hover {
        color: #ffffff;
        /* Đảm bảo chữ vẫn trắng khi hover */
    }

    /* Container chính sử dụng Flexbox để căn chỉnh các phần tử */
    .row {
        display: flex;
        align-items: stretch;
        /* Đảm bảo các phần tử trong cùng một dòng có chiều cao giống nhau */
    }

    /* Đảm bảo banner và phần sản phẩm có chiều cao đồng đều */
    .block-banner,
    .block-product {
        display: flex;
        flex-direction: column;
        height: 900px;
    }

    /* Đảm bảo chiều cao của banner tự động điều chỉnh */
    .block-banner .banner-img {
        width: 100%;
        /* Chiếm hết chiều rộng của phần chứa */
        height: 850px;
        /* Chiều cao bằng với phần chứa */
        object-fit: cover;
        /* Giữ tỉ lệ của ảnh, không bị méo */
    }

    /* Cung cấp một chiều cao cụ thể cho sản phẩm nếu cần */
    .block-product {
        min-height: 400px;
        /* Thay đổi chiều cao này theo nhu cầu */
    }

    /* Hiệu ứng zoom cho ảnh sản phẩm */
    .zoom-on-hover {
        transition: transform 0.3s ease, filter 0.3s ease; /* Chuyển động mượt mà */
    }

    .zoom-on-hover:hover {
        transform: scale(1.1); /* Zoom ảnh lên 10% */
    filter: brightness(1.1); /* Tăng độ sáng nhẹ */    }

    /* Canh chỉnh danh mục con */
    .block-cate ul {
        padding-left: 0;
        margin-bottom: 0;
        list-style-type: none;
    }

    .block-cate li {
        margin: 5px 0;
    }

    .block-cate a {
        text-decoration: none;
        color: #333;
        font-size: 19px;
    }

    .block-cate a:hover {
        color: #007bff;
        text-decoration: underline;
    }

    /* Hình ảnh nhà cung cấp bo tròn */
    .block-vendor img {
        border-radius: 50%;
        border: 2px solid #ddd;
        padding: 3px;
        transition: transform 0.3s ease;
    }

    .block-vendor img:hover {
        transform: scale(1.1);
    }

    /* Ẩn đường viền của card */
    .card {
        border: none !important;
        transition: filter 0.3s ease, box-shadow 0.3s ease;
        /* Thêm chuyển đổi mượt mà */

    }

    /* Ẩn đường viền khi hover trên card */
    .card:hover {
        border: none !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        /* Đổ bóng nhẹ khi hover */
        filter: drop-shadow(16px 38px 45px rgba(0, 0, 0, 0.2));
        transform: translateY(-5px);
        /* Card di chuyển nhẹ lên trên */
    }

    a:hover {
        color: #7fad39 !important;
    }
</style>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
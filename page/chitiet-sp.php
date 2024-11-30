<?php

$id_sp = $_GET['id'];
$sql_sp = "
    SELECT SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, DG.GiaBan, SP.Hinh_ChiTiet , SP.Mota_sp
    FROM SanPham SP
    LEFT JOIN DonGia DG ON SP.id_sp = DG.id_sp
    WHERE SP.id_sp = $id_sp AND SP.HoatDong = 0
    LIMIT 1";
$result_sp = mysqli_query($link, $sql_sp);
$sp = mysqli_fetch_assoc($result_sp);
$sql_donvi = "
SELECT DV.Ten_dv, DG.GiaBan,DG.SoLuong,DG.id_dv
FROM DonGia DG
JOIN DonVi DV ON DG.id_dv = DV.id_dv
WHERE DG.id_sp = $id_sp AND DG.HoatDong = 0";
$result_donvi = mysqli_query($link, $sql_donvi);
$donvi = [];
while ($dv = mysqli_fetch_assoc($result_donvi)) {
    $donvi[] = $dv;
}
if (!$sp) {
    echo "Sản phẩm không tồn tại!";
    exit;
}

// Tách hình ảnh chi tiết
$images = explode(',', $sp['Hinh_ChiTiet']);
?>
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <!-- HÌnh nền -->
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <img class="product__details__pic__item--large" src="admin_test/uploads/sanpham/<?php echo $sp['Hinh_Nen']; ?>" alt="<?php echo $sp['Ten_sp']; ?>">
                    </div>
                    <div class="product__details__pic__slider owl-carousel">
                        <?php foreach ($images as $image): ?>
                            <img data-imgbigurl="admin_test/uploads/sanpham/<?php echo $image; ?>" src="admin_test/uploads/sanpham/<?php echo $image; ?>" alt="<?php echo $sp['Ten_sp']; ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!-- Thông tin sản phẩm -->
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?php echo $sp['Ten_sp']; ?></h3>
                    <div class="product__details__price">
                        <h2 id="gia"> <?php echo number_format($sp['GiaBan'], 0, ',', '.'); ?> VND
                        </h2>
                        <input type="hidden" name="id_sp" id="id_sp" value="<?php echo $id_sp; ?>">
                    </div>

                    <div class="product__details__quantity">
                        </br> </div>
                    <div class="product__details__quantity">

                        </br>
                        <input type="number" id="quantity" class="form-control mt-2 mb-4" value="1" min="1" max="<?php echo $donvi[0]['SoLuong']; ?>">
                    </div>
                    <select name="donvi" id="donvi" class="form-select mt-2 mb-4">
                        <?php if (!empty($donvi)): ?>
                            <?php foreach ($donvi as $dv): ?>
                                <option
                                    value="<?php echo $dv['id_dv']; ?>"
                                    data-price="<?php echo $dv['GiaBan']; ?>"
                                    data-quantity="<?php echo $dv['SoLuong']; ?>">
                                    <?php echo $dv['Ten_dv']; ?> (<?php echo $dv['SoLuong']; ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Không có đơn vị</option>
                        <?php endif; ?>
                    </select>
                    </br>
                    <button type="button" class="primary-btn" style="border: none;" id="btnAddToCart">Thêm giỏ hàng</button>
                    <ul>
                        <li><b>Tình trạng:</b> <span>Còn hàng</span></li>
                        <li><b>Chia sẻ:</b>
                            <div class="share">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Tabs mô tả, thông số, đánh giá -->
        <div class="row">
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Mô tả và thông số</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Đánh giá</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc text-center">
                                <h6>Mô tả chi tiết sản phẩm</h6>
                                <div class="mota-sp" id="mota-sp">
                                    <?php echo $sp['Mota_sp']; ?>
                                </div>
                                <button class="btn btn-primary btn-xem-them" id="btn-xem-them" onclick="toggleDescription()">Xem thêm</button>

                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Đánh giá</h6>
                                <p>Người dùng có thể đánh giá sản phẩm ở đây.</p>
                                <div id="rating-section"></div>

                                <div class="tab-content">
                                    <!-- Tab Mô tả sản phẩm -->
                                    <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                        <div class="product__details__tab__desc">
                                            <h6>Đánh giá</h6>
                                            <!-- Form gửi đánh giá -->
                                            <?php if (isset($_SESSION['id_user'])) {
                                                echo '    <form id="rating-form" style="padding-right: -20px;">
                                        <input type="hidden" id="username" value="' . $_SESSION['id_user'] . '">
                                        <input type="hidden" id="product_id" value="' . $id_sp . '">
                                        <div class="row ">
                                        <div class="stars col-lg-4 col-md-6 col-sm-12">
                                        <input class="star star-5" id="star-5" type="radio" name="star" value="5" />
                                        <label class="star star-5" for="star-5"></label>
                                        <input class="star star-4" id="star-4" type="radio" name="star" value="4" />
                                        <label class="star star-4" for="star-4"></label>
                                        <input class="star star-3" id="star-3" type="radio" name="star" value="3" />
                                        <label class="star star-3" for="star-3"></label>
                                        <input class="star star-2" id="star-2" type="radio" name="star" value="2" />
                                        <label class="star star-2" for="star-2"></label>
                                        <input class="star star-1" id="star-1" type="radio" name="star" value="1" />
                                        <label class="star star-1" for="star-1"></label>
                                        </div>
                                        </div>

                                        <div class="row py-2">
                                        <div class="col-lg-12 col-md-6 col-sm-12">
                                        <textarea id="rating-description" class="form-control" placeholder="Nhập mô tả đánh giá"></textarea>
                                        </div>
                                        </div>
                                        <div class="row py-2">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                        <button type="button" class="site-btn"id="submit-rating">Gửi đánh giá</button>
                                        </div>
                                        </div>
                                        </form>';
                                            } else
                                                echo '<a class="collapse-item active" href="login-main.php">Hãy đăng nhập để lại bình luận của bạn</a>';
                                            ?>
                                            <!-- Dropzone cho phần tải lên nhiều ảnh -->
                                            <div class="row py-2" style="<?php echo isset($_SESSION['id_user']) ? 'display: block;' : 'display: none;'; ?>">
                                                <div class="col-lg-12 col-md-12">
                                                    <form action="upload_images.php" class="dropzone" id="dropzoneArea"></form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</section>

<!-- Thư viện Owl Carousel -->
<link rel="stylesheet" href="css/owl.carousel.min.css">
<link rel="stylesheet" href="css/owl.theme.default.min.css">
<script src="js/jquery.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script>
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            items: 4,
            loop: true,
            margin: 10,
            nav: true,
            dots: true
        });

        $('.product__details__pic__slider img').on('click', function() {
            const bigImgUrl = $(this).attr('data-imgbigurl');
            $('.product__details__pic__item--large').attr('src', bigImgUrl);
        });
    });

    function toggleDescription() {
        const desc = document.getElementById('mota-sp');
        const btn = document.getElementById('btn-xem-them');

        if (desc.classList.contains('open')) {
            desc.classList.remove('open');
            btn.innerText = 'Xem thêm';
        } else {
            desc.classList.add('open');
            btn.innerText = 'Thu gọn';
        }
    }
    $(document).ready(function() {
        // Lắng nghe sự kiện thay đổi đơn vị
        $(document).on('change', '#donvi', function() {
            var selectedOption = $(this).find(':selected'); // Lấy option được chọn
            var newPrice = selectedOption.data('price'); // Lấy giá từ data-price
            var maxQuantity = selectedOption.data('quantity'); // Lấy số lượng từ data-quantity

            // Cập nhật giá hiển thị
            $('#gia').text(newPrice.toLocaleString('vi-VN') + ' VND');

            // Cập nhật giới hạn số lượng
            $('#quantity').attr('max', maxQuantity).val(1);
        });
    });
    Dropzone.autoDiscover = false;

    $(document).ready(function() {
        Dropzone.autoDiscover = false;

        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(function(dropzone) {
                dropzone.destroy();
            });
        }
        var id_sp = $('#id_sp').val(); // Gán giá trị vào input hidden        ;
        var myDropzone = new Dropzone("#dropzoneArea", {
            url: "comment-process.php",
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFiles: 10,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            parallelUploads: 10,
            init: function() {
                var dropzone = this;
                $('#submit-rating').on('click', function(e) {
                    e.preventDefault();
                    var product_id = $('#product_id').val();
                    var user_id = $('#username').val();
                    var star = $('input[name="star"]:checked').val();
                    var description = $('#rating-description').val();

                    if (!star) {
                        alert('Vui lòng chọn số sao đánh giá!');
                        return;
                    }
                    if (!description) {
                        alert('Vui lòng nhập mô tả đánh giá!');
                        return;
                    }

                    myDropzone.options.params = {
                        user_id: user_id,
                        star: star,
                        description: description,
                        id_sp: id_sp
                    };

                    if (dropzone.getQueuedFiles().length > 0) {
                        dropzone.processQueue();
                    } else {
                        // Không có ảnh thì gửi đánh giá trực tiếp
                        $.ajax({
                            url: 'comment-process.php',
                            type: 'POST',
                            data: {
                                user_id: user_id,
                                star: star,
                                description: description,
                                id_sp: id_sp
                            },
                            success: function(response) {
                                console.log(response); // Kiểm tra phản hồi
                                if (response.success === 'success') {
                                    alert('Đánh giá đã được gửi thành công!');
                                    loadFeedback(<?php echo $id_sp ?>);
                                    resetForms();
                                } else {
                                    alert('Đánh giá không được lưu: ' + response.error);
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Lỗi server: ' + error);
                            }
                        });
                    }
                });

                dropzone.on("successmultiple", function(files, response) {
                    console.log(response); // Kiểm tra phản hồi
                    if (response.success) {
                        alert('Ảnh đã được tải lên và đánh giá thành công!');
                        loadFeedback(<?php echo $id_sp ?>);
                        resetForms();
                    } else {
                        alert('Có lỗi trong quá trình tải ảnh: ' + response.error);
                    }
                });

                dropzone.on("errormultiple", function(files, response) {
                    alert('Lỗi khi tải ảnh!');
                });
            }
        });

        // Định nghĩa hàm loadFeedback
        function loadFeedback(productId) {
            $.ajax({
                url: 'fetch_feedback.php',
                type: 'GET',
                data: {
                    id: productId
                },
                success: function(response) {
                    $('#rating-section').html(response);
                }
            });
        }

        // Gọi hàm loadFeedback với giá trị từ PHP

        loadFeedback(<?php echo $id_sp ?>);
        function resetForms() {
    // Reset form submit-rating
    $('#submit-rating-form')[0].reset();

    // Reset Dropzone (xóa các tệp đã thêm)
    if (Dropzone.instances.length > 0) {
        Dropzone.instances.forEach(function(dropzone) {
            dropzone.removeAllFiles(true); // Xóa tất cả tệp
        });
    }
}

    });
</script>

<style>
    .mota-sp {
        overflow: hidden;
        max-height: 100px;
        /* Chỉ hiển thị 100px ban đầu */
        transition: max-height 0.3s ease-in-out;
    }

    .mota-sp.open {
        max-height: none;
        /* Hiển thị toàn bộ nội dung */
    }

    .btn-xem-them {
        margin-top: 10px;
        cursor: pointer;
    }
</style>
<style>
    div.stars {
        width: 400px;
        display: inline-block;
    }

    input.star {
        display: none;
    }

    label.star {
        float: right;
        padding: 10px;
        font-size: 36px;
        color: #444;
        transition: all .2s;
    }

    input.star:checked~label.star:before {
        content: '\f005';
        color: #FD4;
        transition: all .25s;
    }

    input.star-5:checked~label.star:before {
        color: #FE7;
        text-shadow: 0 0 20px #952;
    }

    input.star-1:checked~label.star:before {
        color: #F62;
    }

    label.star:hover {
        transform: rotate(-15deg) scale(1.3);
    }

    label.star:before {
        content: '\f006';
        font-family: FontAwesome;
    }
</style>
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
        font-size: 14px;
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
        height: 100%;
    }

    /* Đảm bảo chiều cao của banner tự động điều chỉnh */
    .block-banner .banner-img {
        width: 100%;
        /* Chiếm hết chiều rộng của phần chứa */
        height: 100%;
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
        transition: transform 0.3s ease;
    }

    .zoom-on-hover:hover {
        transform: scale(1.1);
    }

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
        font-size: 14px;
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
    }

    /* Ẩn đường viền khi hover trên card */
    .card:hover {
        border: none !important;
    }

    a:hover {
        color: #7fad39 !important;
    }

   
</style>
<div class="col-lg-12">
    <div class="section-title">
        <h2>Các sản phẩm mà bạn có thể thích</h2>
    </div>
    <section class="section-product product-1">
        <div class="container">
            <div class="block-content">
                <div class="row">
           

                    <!-- Sản phẩm -->
                    <div class="col-xl-12 col-lg-8 col-md-8 col-12 block-product">
                        <div class="row">
                            <?php
                            // Lấy sản phẩm từ các danh mục con
                            $sql_sp = "
                              SELECT SP.id_sp, SP.Ten_sp, SP.Hinh_Nen, MAX(DG.GiaBan) AS GiaBan, SP.id_dm
                                FROM SanPham SP
                                LEFT JOIN DonGia DG ON SP.id_sp = DG.id_sp
                                WHERE SP.id_sp !=  $id_sp
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
                                        <a href="index.php?action=product&query=details&id=<?php echo $id_sp?>"> <h6 class="card-title"><?= $ten_sp ?></h6></a>
                                        <p class="text-danger fw-bold"><?= $gia_ban ?></p>
                                        </div>
                                        <button type="button" id="btnDetail" class="btn btn-primary" data-id="<?= $id_sp ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Mua ngay
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                   

                    <!-- Banner quảng cáo -->
                  
                </div>
            </div>
        </div>
    </section>


</div>
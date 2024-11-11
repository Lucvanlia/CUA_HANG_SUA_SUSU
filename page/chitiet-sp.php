<?php
if (isset($_GET['id'])) {
    $test = $_GET['id'];
    $productId = $_GET['id'];
    // Lấy id_loai của sản phẩm hiện tại
    $sql_goiy = "SELECT id_loai FROM dmsp WHERE id_sp = '$_GET[id]'";
    $result_goiy = $link->query($sql_goiy);
    $row_goiy = mysqli_fetch_assoc($result_goiy);
    $id_loai = $row_goiy['id_loai'];

    // Lấy danh sách sản phẩm tương tự
    $sql_tuongtu = "SELECT * FROM dmsp WHERE id_sp != '$_GET[id]' AND id_loai = '$id_loai'";
    $query_tuongtu = mysqli_query($link, $sql_tuongtu);

    // Truy vấn chi tiết sản phẩm
    $sql_chitiet = "SELECT * FROM 
        dmsp AS sp 
        JOIN xuatxu AS xx ON sp.id_xuatxu = xx.id_xuatxu 
        JOIN hang AS h ON sp.id_hang = h.id_hang       
        JOIN loai AS l ON sp.id_loai = l.id_loai            
        WHERE sp.id_sp = '$_GET[id]'";
    $query_chitiet = mysqli_query($link, $sql_chitiet);
}


while ($row = mysqli_fetch_assoc($query_chitiet)) {
?>

    <!-- Breadcrumb Section Begin -->
    <style>
        .dropzone {
            border: 2px dashed #0087F7;
            background-color: #f9f9f9;
        }
    </style>
    <!-- Breadcrumb Section End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large"
                                src="img/product/details/product-details-1.jpg" alt="">
                        </div>
                        <div class="product__details__pic__slider owl-carousel">
                            <img data-imgbigurl="img/product/details/product-details-2.jpg"
                                src="img/product/details/thumb-1.jpg" alt="">
                            <img data-imgbigurl="img/product/details/product-details-3.jpg"
                                src="img/product/details/thumb-2.jpg" alt="">
                            <img data-imgbigurl="img/product/details/product-details-5.jpg"
                                src="img/product/details/thumb-3.jpg" alt="">
                            <img data-imgbigurl="img/product/details/product-details-4.jpg"
                                src="img/product/details/thumb-4.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3><?= $row['Tensp'] ?></h3>
                        <div class="product__details__rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <span>(18 reviews)</span>
                        </div>
                        <div class="product__details__price"><?= number_format($row['gia'], 0, ',', '.') ?>&nbsp VNĐ </div>
                        <p><?= $row['MoTa'] ?></p>

                        <form id="quick-buy-form" class="form-submit" action="index.php?action=cart&query=add" method="POST">
                            <input type="hidden" class="pid" value="<?= $row['id_sp'] ?>" name="id" />
                            <input type="hidden" class="pname" value="<?= $row['Tensp'] ?>" name="ten" />
                            <input type="hidden" class="pprice" value="<?= $row['gia'] ?>" name="gia" />
                            <input type="hidden" class="status" value="add" name="status" />
                            <input type="hidden" class="pimage" value="admin_test/modul/uploads/<?= $row['hinh'] ?>" name="hinh" />
                            <div class="product__details__quantity">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="number" class="soluong" name="soluong" value="1" min="1" max="<?= $row['SoLuong'] ?>">
                                    </div>
                                    <input style="width: 100%;" class="btn btn-success addItemBtn" type="button" value="Mua ngay" />
                                </div>
                            </div>
                        </form>

                        <ul>
                            <li><b>Tình trạng</b> <span><?php

                                                        if ($row['SoLuong'] <= 0) {
                                                            echo 'Hết hàng';
                                                        } else
                                                            echo 'Còn hàng';
                                                        ?></span></li>
                            <li><b>Giao hàng:</b> <span>Đơn hàng <samp>Free pickup today</samp></span></li>
                            <li><b>Weight</b> <span>0.5 kg</span></li>
                            <li><b>Share on</b>
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
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab" aria-selected="true">Bình luận</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab" aria-selected="false">Bình luận</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab" aria-selected="false">Đánh giá <span>(1)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab" aria-selected="false">Test <span>(1)</span></a>
                            </li> -->
                        </ul>
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
                <input type="hidden" id="product_id" value="' . $productId . '">
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
    </section>
    <!-- Product Details Section End -->
<?php } ?>
<!-- Related Product Section Begin -->

<!-- Phần hiển thị sản phẩm tương tự -->
<section class="related-product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title related__product__title">
                    <h2>Related Product</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if ($query_tuongtu->num_rows > 0) : ?>
                <?php while ($related_product = $query_tuongtu->fetch_assoc()) : ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="admin_test/modul/uploads/<?= $related_product['hinh']; ?>">
                                <ul class="product__item__pic__hover">
                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                    <li><a href="cart.php?add=<?= $related_product['id_sp']; ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <!-- <a href="index.php?action=product&query=details&id=<?php echo $row['id_sp'] ?>"><?= $row['Tensp'] ?></a> -->
                                <h6><a href="index.php?action=product&query=details&id=<?= $related_product['id_sp']; ?>"><?= $related_product['Tensp']; ?></a></h6>
                                <h5><?= number_format($related_product['gia'], 0, ',', '.'); ?> VNĐ</h5>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Related Product Section End -->

<!-- Footer Section Begin -->
<footer class="footer spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer__about">
                    <div class="footer__about__logo">
                        <a href="./index.html"><img src="img/logo.png" alt=""></a>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="footer__copyright">
                    <div class="footer__copyright__text">
                        <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;<script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    </div>
                    <div class="footer__copyright__payment"><img src="img/payment-item.png" alt=""></div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->

<!-- Js Plugins -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.slicknav.js"></script>
<script src="js/mixitup.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
<style>
    div.stars {
        width: 185px;
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

</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    Dropzone.autoDiscover = false;

    $(document).ready(function() {
        Dropzone.autoDiscover = false;

        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(function(dropzone) {
                dropzone.destroy();
            });
        }

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
                        description: description
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
                                description: description
                            },
                            success: function(response) {
                                console.log(response); // Kiểm tra phản hồi
                                if (response.success) {
                                    alert('Đánh giá đã được gửi thành công!');
                                    loadFeedback();
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
                        loadFeedback();
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
        <?php if ($productId): ?>
            loadFeedback(<?php echo json_encode($productId); ?>);
        <?php else: ?>
            console.warn("ID sản phẩm không được cung cấp trong URL.");
        <?php endif; ?>
        loadFeedback($productId);
    });
</script>
<?php

$sql_tt = "SELECT * FROM tintuc  where id_tt = " . $_GET['id'];
$result_tt = mysqli_query($link, $sql_tt);
$sql_ttgy = "SELECT * FROM tintuc  where id_tt != " . $_GET['id'] . ' LIMIT 3 ';
// var_dump($sql_ttgy);exit();
$result_ttgy = mysqli_query($link, $sql_ttgy);

?>
<!-- Blog Details Hero Begin -->
<section class="blog-details-hero set-bg" data-setbg="img/blog/details/details-hero.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog__details__hero__text">
                    <h2>Chuyên mục bài viết</h2>
                    <ul>
                        <li>By Michael Scofield</li>
                        <li>January 14, 2019</li>
                        <li>8 Comments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Details Hero End -->

<!-- Blog Details Section Begin -->
<section class="blog-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5 order-md-1 order-2">
                <div class="blog__sidebar">
                    <?php
                    // Kết nối đến cơ sở dữ liệu


                    // Lấy tất cả các loại tin tức
                    $sql = "SELECT id_ltt, Ten_ltt, parent_ltt FROM loaitintuc";
                    $result = $link->query($sql);

                    $categories = [];
                    while ($row = $result->fetch_assoc()) {
                        $categories[] = $row;
                    }

                    // Hàm đệ quy để xây dựng mảng phân cấp
                    function buildTree($categories, $parent_id = 0)
                    {
                        $branch = [];
                        foreach ($categories as $category) {
                            if ($category['parent_ltt'] == $parent_id) {
                                $children = buildTree($categories, $category['id_ltt']);
                                if ($children) {
                                    $category['children'] = $children;
                                }
                                $branch[] = $category;
                            }
                        }
                        return $branch;
                    }

                    // Xây dựng cây phân cấp
                    $category_tree = buildTree($categories);

                    // Hàm hiển thị menu với CSS và JS
                    function showMenu($category_tree)
                    {
                        echo '<ul class="menu">';
                        foreach ($category_tree as $category) {
                            echo '<li>';
                            echo '<a href="?action=blog&query=detail&id_theloai=' . $category['id_ltt'] . '">' . $category['Ten_ltt'] . '</a>';
                            // Kiểm tra nếu có danh mục con
                            if (!empty($category['children'])) {
                                echo '<button class="toggle-btn">+</button>';
                                echo '<ul class="submenu">';
                                showMenu($category['children']);
                                echo '</ul>';
                            }
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    ?>

                    <!-- HTML Menu -->
                    <div class="blog__sidebar__item">
                        <h4>Thể loại</h4>
                        <ul>
                            <li><a href="?action=blog&query=all">Tất cả</a></li>
                            <?php showMenu($category_tree); ?>
                        </ul>
                    </div>

                    <div class="blog__sidebar__item">
                        <h4>Sản phẩm liên quan</h4>

                        <?php
                        $sql_tt1 = "SELECT * FROM tintuc  where id_tt = " . $_GET['id'];
                        $result_tt1 = mysqli_query($link, $sql_tt);
                        $kq_tt1 = mysqli_fetch_assoc($result_tt1);
                        $tag_sp = $kq_tt1['tag_sp'];
                        if (!empty($tag_sp)) {
                            // Thêm trường Hinh_Nen vào câu truy vấn
                            $productQuery = "SELECT id_sp, Ten_sp, Hinh_Nen FROM SanPham WHERE id_sp IN ($tag_sp)";
                            $productResult = mysqli_query($link, $productQuery);

                            // Kiểm tra kết quả trả về
                            if ($productResult && mysqli_num_rows($productResult) > 0) {
                                while ($product = mysqli_fetch_assoc($productResult)) {
                        ?>
                                    <div class="blog__sidebar__recent py-3" >
                                        <a href="?action=product&query=details&id=<?= $product['id_sp'] ?>" class="blog__sidebar__recent__item">
                                            <div class="blog__sidebar__recent__item__pic">
                                                <img src="admin_test/uploads/sanpham/<?= $product['Hinh_Nen'] ?>" alt="" style="
                                                    width: 100px;
                                                    height: 100px;
                                                ">
                                            </div>
                                            <div class="blog__sidebar__recent__item__text">
                                                <span><?= $product['Ten_sp'] ?></span>
                                            </div>
                                        </a>
                                    </div>
                        <?php
                                }
                            } else {
                                echo '<p>Không có sản phẩm liên quan.</p>';
                            }
                        } else {
                            echo '<p>Không có sản phẩm liên quan.</p>';
                        }
                        ?>

                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Top tìm kiếm</h4>
                        <div class="blog__sidebar__item__tags">
                            <a href="#">Táo</a>
                            <a href="#">Sức khỏe</a>
                            <a href="#">Rau tươi</a>
                            <a href="#">Thịt cá</a>
                            <a href="#">Giò heo</a>
                            <a href="#">Cá tươi</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 order-md-1 order-1">
                <?php
                while ($row_detail = mysqli_fetch_assoc($result_tt)) {


                ?>
                    <div class="blog__details__text">
                        <p>
                        <h3><?= $row_detail['Title'] ?></h3>

                        <?= $row_detail['NoiDung'] ?>
                    </div>
                    <div class="blog__details__content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="blog__details__author">
                                    <div class="blog__details__author__pic">
                                        <img src="img/blog/details/details-author.jpg" alt="">
                                    </div>
                                    <div class="blog__details__author__text">
                                        <h6>Nguyễn So Ny</h6>
                                        <span>Admin</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="blog__details__widget">
                                    <ul>
                                        <li><span>Loại sản phẩm:</span> Thức ăn</li>
                                        <li><span>Tags:</span> All, Trending, Cooking, Healthy Food, Life Style</li>
                                    </ul>
                                    <div class="blog__details__social">
                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                        <a href="#"><i class="fa fa-google-plus"></i></a>
                                        <a href="#"><i class="fa fa-linkedin"></i></a>
                                        <a href="#"><i class="fa fa-envelope"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php


                } //kt while 
                ?>
            </div>
        </div>
    </div>
</section>
<!-- Blog Details Section End -->

<!-- Related Blog Section Begin -->
<section class="related-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title related-blog-title">
                    <h2>Bài viết đề xuất</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php while ($row_gy = mysqli_fetch_assoc($result_ttgy)) { ?>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="admin_test/uploads/<?= $row_gy['Hinh_Nen'] ?>" alt="">
                        </div>
                        <div class="blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i>&nbsp;<?= $row_gy['created_at'] ?></li>
                                <li><i class="fa fa-comment-o"></i> 100+</li>
                            </ul>
                            <h5><a href="?action=blog&query=detail&id=<?= $row_gy['id_ltt'] ?>"><?= $row_gy['Title'] ?></a></h5>
                            <p> <?php
                                $noidung = explode(' ', $row_gy['NoiDung']);
                                if (count($noidung) > 50) {
                                    $noidung = array_slice($noidung, 0, 50);
                                    $noidung = implode(' ', $noidung) . '...';
                                } else {
                                    $noidung = $row_gy['NoiDung'];
                                }
                                echo $noidung;
                                ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>

<style>
    .menu,
    .submenu {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .menu>li {
        padding: 8px;
        position: relative;
    }

    .menu>li>a {
        text-decoration: none;
        color: #333;
    }

    .submenu {
        max-height: 0;
        /* Ẩn các submenu mặc định */
        overflow: hidden;
        transition: max-height 0.3s ease;
        /* Thêm chuyển tiếp mượt mà */
        padding-left: 15px;
    }

    .toggle-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #333;
        font-weight: bold;
        margin-left: 5px;
    }
</style>
<!-- Related Blog Section End --><!-- Bao gồm Bootstrap CSS và JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButtons = document.querySelectorAll(".toggle-btn");

        toggleButtons.forEach(button => {
            button.addEventListener("click", function() {
                const submenu = this.nextElementSibling;

                // Kiểm tra trạng thái mở/đóng của submenu
                if (submenu.style.maxHeight && submenu.style.maxHeight !== "0px") {
                    submenu.style.maxHeight = "0";
                    this.textContent = "+"; // Đổi nút thành dấu "+"
                } else {
                    submenu.style.maxHeight = submenu.scrollHeight + "px"; // Mở rộng tới chiều cao tự nhiên
                    this.textContent = "-"; // Đổi nút thành dấu "-"
                }
            });
        });
    });
</script>
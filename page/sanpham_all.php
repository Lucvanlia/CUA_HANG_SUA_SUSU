<?php

// Kiểm tra xem có ID sản phẩm trong yêu cầu GET không
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$result = mysqli_query($link, "SELECT * FROM dmsp ");
$result_sp = mysqli_query($link, "SELECT * FROM dmsp ");
$result_banchay = mysqli_query($link, " SELECT * 
                                        FROM dmsp
                                        WHERE dmsp.id_sp in (
                                                SELECT ct.id_sp 
                                                from ctiethd ct
                                                GROUP BY id_sp

                                        )");
$result_loai = mysqli_query($link, "SELECT * FROM `loai` ORDER BY `loai`.`tenloai` ASC");
if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_array($result);
    $loai = mysqli_fetch_array($result);
} else {
    echo "Sản phẩm không tồn tại!";
    exit;
}

?>
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">

                    <div class="breadcrumb__option">
                        <a href="./index.html">Home </a>
                        <span>Sản Phẩm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Danh Mục</h4>
                        <ul>
                            <?php
                            while ($row_loai  =  mysqli_fetch_array($result_loai)) {
                                echo '<h2></h2>
                                 <li><a href="?action=product&query=all&id_loai=' . $row_loai['id_loai'] . '">' . $row_loai['tenloai'] . '</a></li>
                            ';
                            } ?>

                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <h4>Giá</h4>
                        <div class="price-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                data-min="10" data-max="540">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <div class="product__discount">
                    <div class="section-title product__discount__title">
                        <h2>Sale Off</h2>
                    </div>
                    <div class="row">
                        <div class="product__discount__slider owl-carousel">
                            <?php while ($row_banchay = mysqli_fetch_assoc($result_banchay)) {
                                $km = $row_banchay['gia'] - $row_banchay['gia'] * 20 / 100;
                            ?>
                                <div class="col-lg-4">
                                    <div class="product__discount__item">
                                        <div class="product__discount__item__pic set-bg"
                                            data-setbg="admin_test/modul/uploads/<?= $row_banchay['hinh'] ?>">
                                            <ul class="product__item__pic__hover">
                                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                            </ul>
                                        </div>
                                        <div class="product__discount__item__text">
                                            <span></span>
                                            <h5><a href="#"><?= $row_banchay['Tensp'] ?></a></h5>
                                            <div class="product__item__price"><?= number_format($km, 0, ',', '.') . 'VNĐ' ?> <span><span></span><?= number_format($row_banchay['gia'], 0, ',', '.') . 'VNĐ' ?></span></div>
                                            <form id="quick-buy-form" class="form-submit" action="index.php?action=cart&query=add" method="POST">
                                                <input type="hidden" class="pid" value="<?= $row_banchay['id_sp'] ?>" name="id" />
                                                <input type="hidden" class="pname" value="<?= $row_banchay['Tensp'] ?>" name="ten" />
                                                <input type="hidden" class="pprice" value="<?= $row_banchay['gia'] ?>" name="gia" />
                                                <input type="hidden" class="status" value="add" name="status" />
                                                <input type="hidden" class="pimage" value="admin_test/modul/uploads/<?= $row_banchay['hinh'] ?>" name="hinh" />
                                                <div class="product__details__quantity">
                                                    <div class="quantity">
                                                        <div class="pro-qty">
                                                            <input type="hidden" class="soluong" name="soluong" value="1" min="1">
                                                        </div>
                                                        <input style="width: 100%;" class="btn btn-success addItemBtn" type="button" value="Mua ngay" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }

                            ?>

                        </div>
                    </div>
                </div>
                <div class="filter__item">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="filter__sort">
                                <span>Sort By</span>
                                <select>
                                    <option value="0">Default</option>
                                    <option value="0">Default</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6><span><?php echo mysqli_num_rows($result_sp) ?></span> sản phẩm </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
// Number of products per page
$products_per_page = 6;

// Query to count the total number of products
$sql_count = "SELECT COUNT(*) AS total_products FROM dmsp"; // Adjust "dmsp" to your table name
$result_count = mysqli_query($link, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_products = $row_count['total_products'];

// Calculate the total pages
$total_pages = ceil($total_products / $products_per_page);

// Get the current page or default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the OFFSET for the current page
$offset = ($current_page - 1) * $products_per_page;

// Query to fetch products for the current page
$sql_sp = "SELECT * FROM dmsp LIMIT $products_per_page OFFSET $offset";
$result_sp = mysqli_query($link, $sql_sp);

// Display the products and pagination as before
?>
<div class="row">
    <?php while($row_sp = mysqli_fetch_assoc($result_sp)) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="product__item">
                <div class="product__item__pic set-bg" data-setbg="admin_test/modul/uploads/<?= $row_sp['hinh'] ?>">
                    <ul class="product__item__pic__hover">
                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                        <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                    </ul>
                </div>
                <div class="product__item__text">
                    <h6><a href="#"><?= $row_sp['Tensp']?></a></h6>
                    <h5><?= number_format($row_sp['gia'], 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php
$id = isset($_GET['id']) ? $_GET['id'] : ''; // Giả sử bạn lấy 'id' từ URL hiện tại
?>

<div class="product__pagination">
    <?php if ($current_page > 1): ?>
        <a href="?page=<?= $current_page - 1 ?>&id=<?= $id ?>"><i class="fa fa-long-arrow-left"></i> Pre</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&id=<?= $id ?>" class="<?= $i == $current_page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($current_page < $total_pages): ?>
        <a href="?page=<?= $current_page + 1 ?>&id=<?= $id ?>">Next <i class="fa fa-long-arrow-right"></i></a>
    <?php endif; ?>
</div>


        </div>
    </div>
</section>
<!-- Product Section End -->
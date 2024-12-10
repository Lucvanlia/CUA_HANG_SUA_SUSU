<!-- Loading animation bo? -->
<!-- <div id="preloder">
        <div class="loader"></div>
    </div> -->

<!-- Humberger Begin -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<style>
    a {
        text-decoration: none !important;
    }
</style>
<!-- <?php var_dump($_SESSION['id_user']) ?> -->
<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="#"><img src="img/logo.png" alt=""></a>
    </div>
    <div class="humberger__menu__cart">
        <ul>
            <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
            <li><a data-fancybox data-type="ajax" href="cart.php">Load content using AJAX</a><i class="fa fa-shopping-bag"></i></li>

        </ul>
        <div class="header__cart__price">item: <span>Tổng:</span></div>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__social">

        </div>
        <div class="header__top__right__auth">
            <?php
            if (isset($_SESSION["login-facebook"])) {
                $sql2 = "SELECT * FROM  khachhang where facebook_id =" . $_SESSION["login-facebook"];
                $result = mysqli_query($link, $sql2);
                $count = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result)) {
                    $_SESSION['id_user'] = $row['id_kh'];
                    echo 'Xin chào: <a href="index.php?action=profile&query=profile">' . $row["Ten_KH"] . '</a>';
                }
            }
            if (isset($_SESSION["login-google"])) {
                $sql2 = "SELECT * FROM  khachhang where google_id =" . $_SESSION["login-google"];
                $result = mysqli_query($link, $sql2);
                $count = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result)) {
                    $_SESSION['id_user'] = $row['id_kh'];
                    echo 'Xin chào: <a href="index.php?action=profile&query=profile">' . $row["Ten_KH"] . '</a>';
                }
            }
            if (isset($_SESSION['id_user'])) {
                $sql2 = "SELECT * FROM  khachhang where id_kh =" . $_SESSION["id_user"];
                $result = mysqli_query($link, $sql2);
                $count = mysqli_num_rows($result);
                while ($row = mysqli_fetch_array($result)) {
                    $_SESSION['id_user'] = $row['id_kh'];
                    echo $row['id_kh'];
                    echo 'Xin chào: <a href="index.php?action=profile&query=profile">' . $row["Ten_KH"] . '</a>';
                }
            }

            if (isset($_SESSION["login-google"]) || isset($_SESSION["login-facebook"]) || $_SESSION['id_user']) {
                echo ' <a href="index.php?action=dangxuat&query=dangxuat">Đăng xuất</a>';
            } else {
                echo ' <a class="collapse-item" href="login-main.php">Đăng nhập</a>';
            }

            ?>
        </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li class="active"><a href="/doan_php">Home</a></li>
            <li><a href="?action=product&query=all">Shop123</a></li>
            <li><a href="#">Pages</a>
                <ul class="header__menu__dropdown">
                    <li><a href="./shop-details.html">Shop Details</a></li>
                    <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                    <li><a href="?action=cart&query=view">Check Out</a></li>
                    <li><a href="./blog-details.html">Blog Details</a></li>
                </ul>
            </li>
            <li><a href="?action=cart&query=view">Blog</a></li>
            <li><a href="./contact.html">Contact</a></li>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">

    </div>
    <div class="humberger__menu__contact">
        <ul>

            <div id="gt-mordadam-43217984"></div>

        </ul>
    </div>
</div>
<!-- Humberger End -->

<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__left">
                        <ul>
                            <?php

                            // if (isset($_SESSION["login-facebook"])) {
                            //     $sql2 = "SELECT * FROM  khachhang where facebook_id =" . $_SESSION["login-facebook"];
                            //     $result = mysqli_query($link, $sql2);
                            //     $count = mysqli_num_rows($result);
                            //     while ($row = mysqli_fetch_array($result)) {
                            //         echo 'Xin chào: <a href="index.php?action=profile&query=profile">' . $row["Ten_KH"] . '</a>';
                            //     }
                            // }
                            // if (isset($_SESSION["login-google"])) {
                            //     $sql2 = "SELECT * FROM  khachhang where google_id =" . $_SESSION["login-google"];
                            //     $result = mysqli_query($link, $sql2);
                            //     $count = mysqli_num_rows($result);
                            //     while ($row = mysqli_fetch_array($result)) {
                            //         echo 'Xin chào: <a href="index.php?action=profile&query=profile">' . $row["Ten_KH"] . '</a>';
                            //     }
                            // }
                            if (isset($_SESSION['id_user'])) {
                                $sql2 = "SELECT * FROM  Khachhang where id_kh =" . $_SESSION["id_user"];
                                $result = mysqli_query($link, $sql2);
                                $count = mysqli_num_rows($result);
                                while ($row = mysqli_fetch_array($result)) {
                                    $_SESSION['id_user'] = $row['id_kh'];
                                    echo 'Xin chào: <a href="index.php?action=profile&query=profile">' . $row["Ten_kh"] . '</a>';
                                }
                            }
                            ?>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-pinterest-p"></i></a>
                        </div>
                        <div class="header__top__right__social">
                            <div id="gt-mordadam-43217984"></div>

                        </div>
                        <div class="header__top__right__auth">

                            <?php
                            if (isset($_SESSION["login-google"]) || isset($_SESSION["login-facebook"]) || isset($_SESSION['id_user'])) {
                                echo ' <a href="index.php?action=dangxuat&query=dangxuat">Đăng xuất</a>';
                            } else {
                                echo ' <a class="collapse-item" href="login-main.php">Đăng nhập</a>';
                            }

                            ?> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="header__logo text-center">
                    <a href="/doan_php"><img src="https://tatthanh.com.vn/pic/News/image(10838)_HasThumb.png" style="width: 100% !important;" alt=""></a>
                </div>
            </div>
            <div class="col-lg-6">
                <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

                <nav class="header__menu">
                    <ul>
                        <li><a href="https://banhangviet-tmi.net/doan_php/">TRANG CHỦ</a></li>
                        <li><a href="?action=product&query=all">SẢN PHẨM</a></li>

                        <!-- Mega Menu cho Danh mục -->
                        <li class="dropdown">
                            <a href="#" id="danhmuc">DANHMUC</a>
                            <div class="dropdown-content">
                                <div class="header">
                                    <h2>Danh mục Sản phẩm</h2>
                                </div>
                                <div class="row">
                                    <?php
                                    // Kết nối với cơ sở dữ liệu
                                    include "admin_test/ketnoi/conndb.php";

                                    // Lấy danh mục cấp 1
                                    $sql = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE parent_dm = 0 AND Hoatdong = 0";
                                    $result = mysqli_query($link, $sql);

                                    // Duyệt qua các danh mục cấp 1 và hiển thị
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<div class="column">';
                                        echo '<h3>' . $row['Ten_dm'] . '</h3>';

                                        // Lấy danh mục cấp 2 (con) theo từng danh mục cấp 1
                                        $category_id = $row['id_dm'];
                                        $sql_sub = "SELECT id_dm, Ten_dm FROM DanhMuc WHERE parent_dm = $category_id AND Hoatdong = 0";
                                        $sub_result = mysqli_query($link, $sql_sub);

                                        if (mysqli_num_rows($sub_result) > 0) {
                                            while ($sub_row = mysqli_fetch_assoc($sub_result)) {
                                                echo '<a href="?action=product&query=' . $sub_row['id_dm'] . '">' . $sub_row['Ten_dm'] . '</a>';
                                            }
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </li>

                        <li><a href="#">TIPS</a>
                            <ul class="header__menu__dropdown">
                                <li><a href="?action=lienhe&query=them">Liên hệ</a></li>
                                <li><a href="?action=donhang&query=them">Tìm đơn hàng</a></li>
                                <li><a href="./blog-details.html">Bài viết</a></li>
                            </ul>
                        </li>
                        <li><a href="?action=cart&query=view">Giỏ hàng</a></li>
                    </ul>
                </nav>
<style>
    /* Sử dụng font Roboto */
body {
    font-family: 'Roboto', sans-serif;
}

.header__menu {
    font-family: 'Roboto', sans-serif;
    z-index: 100;
    position: relative; /* Đảm bảo Mega Menu hiển thị ở trên */
}

.header__menu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.header__menu li {
    float: left;
    position: relative;
}

.header__menu a {
    color: black; /* Màu mặc định là đen */
    padding: 14px 16px;
    text-decoration: none;
    display: block;
}

/* Xóa màu khi hover */
.header__menu a:hover {
    color: black; /* Giữ màu đen khi hover */
    background-color: transparent; /* Xóa màu nền khi hover */
}

/* Mega Menu */
.dropdown-content {
    display: none;
    position: absolute;
    width: 500px;
    left: 0;
    background-color: #fff;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1000; /* Đảm bảo Mega Menu nằm trên các phần tử khác */
}

.dropdown-content .header {
    color: black;
    padding: 16px;
    background-color: transparent; /* Loại bỏ màu nền */
}

.row {
    display: flex;
    flex-wrap: wrap; /* Đảm bảo các cột sẽ gập lại khi không đủ không gian */
}

.column {
    width: 33.33%;
    padding: 10px;
}

.column a {
    color: black;
    padding: 16px;
    text-decoration: none;
    display: block;
}

/* Xóa màu khi hover trong các cột */
.column a:hover {
    background-color: transparent; /* Không thay đổi màu nền khi hover */
}

/* Hiển thị Mega Menu khi hover */
.dropdown:hover .dropdown-content {
    display: block;
}

/* Responsive layout */
@media screen and (max-width: 600px) {
    .column {
        width: 100%;
    }
}

</style>


            </div>
            <div class="col-lg-3">
                <div class="header__cart">
                    <ul>
                        <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                        <li>
                            <a data-fancybox data-type="ajax" id="cart-link" href="cart.php">
                                <i class="fa fa-shopping-bag"></i>
                                <span id="order-count"></span> <!-- Đây là số lượng đơn hàng sẽ cập nhật -->
                            </a>
                        </li>
                    </ul>

                    <div class="header__cart__price">
                    </div>
                </div>
            </div>
        </div>
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header Section End -->
<!-- <div id="cart-icon"> 
        <a data-fancybox data-type="ajax" data-src="page/cart-popup.php" href="javascipt:;" >
                 <img src="img/cart-icon.gif" alt="">
        </a>
    </div> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                            <?php 
                                
                                    if(isset($_SESSION["login-facebook"]))
                                    {
                                    $sql2 = "SELECT * FROM  khachhang where facebook_id =". $_SESSION["login-facebook"];
                                    $result = mysqli_query($link, $sql2);
                                    $count=mysqli_num_rows($result);
                                    while ($row=mysqli_fetch_array($result)) 
                                    {
                                        echo 'Xin chào: <a href="index.php?action=profile&query=profile">'.$row["Ten_KH"].'</a>';
                                    }
                                   }
                                    if(isset($_SESSION["login-google"]))
                                    {
                                        $sql2 = "SELECT * FROM  khachhang where google_id =". $_SESSION["login-google"];
                                        $result = mysqli_query($link, $sql2);
                                        $count=mysqli_num_rows($result);
                                        while ($row=mysqli_fetch_array($result)) 
                                        {
                                            echo 'Xin chào: <a href="index.php?action=profile&query=profile">'.$row["Ten_KH"].'</a>';
                                        }

                                    }
                                 
                            ?>
                    
                        </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <div id="gt-mordadam-43217984"></div>

                            </div>
                            
                           
                            <div class="header__top__right__auth">
                            <?php 
                             if(isset($_SESSION["login-google"])||isset($_SESSION["login-facebook"]))
                             {
                                echo ' <a href="index.php?action=dangxuat&query=dangxuat">Đăng xuất</a>';

                             }
                             else    
                             {
                                echo ' <a class="collapse-item" href="login-main.php">Đăng nhập</a>';
                            }
                            
                            ?>
                           <!-- <a href="index.php?action=dangxuat&query=dangxuat">Đăng xuất</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo text-center" >
                        <a href="./index.html"><img src="img/logo.png" alt=""   style="	display: block;
                            margin-left: auto;
                            margin-right: auto;
                           " width="100px" height="100px"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="./index.html">Home</a></li>
                            <li><a href="./shop-grid.html">Shop</a></li>
                            <li><a href="#">Pages</a>
                                <ul class="header__menu__dropdown">
                                    <li><a href="./shop-details.html">Shop Details</a></li>
                                    <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                                    <li><a href="./checkout.html">Check Out</a></li>
                                    <li><a href="./blog-details.html">Blog Details</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog.html">Blog</a></li>
                            <li><a href="./contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                            <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span>$150.00</span></div>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
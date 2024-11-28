<?php 
        //========================================SQL==================================================
        $sql_all="SELECT * FROM 
          dmsp  as sp 
          join xuatxu   as xx on sp.id_xuatxu = xx.id_xuatxu 
          join hang     as h  on sp.id_hang = h.id_hang       
          join diadiem  as dd on sp.id_diadiem = dd.id_diadiem            
          join loai     as l  on sp.id_loai = l.id_loai            
          join chatlieu as cl on sp.id_chatlieu = cl.id_chatlieu   
          where h.tenhang = 'Bitis'                  
        ";
        //========================================ressult==================================================
        $result=mysqli_query($link, $sql_all);
        //==========================================================================================
        $smt=mysqli_num_rows($result);

?>
<section class="new_arrivals_area section-padding-80 clearfix">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <h2>Popular Products</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="popular-products-slides owl-carousel">

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="img/product-img/product-1.jpg" alt="">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="img/product-img/product-2.jpg" alt="">
                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>topshop</span>
                                <a href="single-product-details.html">
                                    <h6>Knot Front Mini Dress</h6>
                                </a>
                                <p class="product-price">$80.00</p>

                                <!-- Hover Content -->
                                <div class="hover-content">
                                    <!-- Add to Cart -->
                                    <div class="add-to-cart-btn">
                                        <a href="#" class="btn essence-btn">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="img/product-img/product-2.jpg" alt="">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="img/product-img/product-3.jpg" alt="">
                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>topshop</span>
                                <a href="single-product-details.html">
                                    <h6>Poplin Displaced Wrap Dress</h6>
                                </a>
                                <p class="product-price">$80.00</p>

                                <!-- Hover Content -->
                                <div class="hover-content">
                                    <!-- Add to Cart -->
                                    <div class="add-to-cart-btn">
                                        <a href="#" class="btn essence-btn">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="img/product-img/product-3.jpg" alt="">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="img/product-img/product-4.jpg" alt="">

                                <!-- Product Badge -->
                                <div class="product-badge offer-badge">
                                    <span>-30%</span>
                                </div>

                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>mango</span>
                                <a href="single-product-details.html">
                                    <h6>PETITE Crepe Wrap Mini Dress</h6>
                                </a>
                                <p class="product-price"><span class="old-price">$75.00</span> $55.00</p>

                                <!-- Hover Content -->
                                <div class="hover-content">
                                    <!-- Add to Cart -->
                                    <div class="add-to-cart-btn">
                                        <a href="#" class="btn essence-btn">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="img/product-img/product-4.jpg" alt="">
                                <!-- Hover Thumb -->
                                <img class="hover-img" src="img/product-img/product-5.jpg" alt="">

                                <!-- Product Badge -->
                                <div class="product-badge new-badge">
                                    <span>New</span>
                                </div>

                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>mango</span>
                                <a href="single-product-details.html">
                                    <h6>PETITE Belted Jumper Dress</h6>
                                </a>
                                <p class="product-price">$80.00</p>

                                <!-- Hover Content -->
                                <div class="hover-content">
                                    <!-- Add to Cart -->
                                    <div class="add-to-cart-btn">
                                        <a href="#" class="btn essence-btn">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="container">
		<span class="ftext">DANH SÁCH MÔN HỌC</span>
	<?php 
		//=====================================================================						
        // VÒNG LẶP DÒNG DỮ LIỆU HIỂN THỊ
        while ($rows = mysqli_fetch_assoc($result) )
		{
	?>
	<div class="row">
    
     <?PHP
    	$n = 4;		//Số Mẫu Tin Mỗi Dòng 
		for($i = 1; $i <= $n; $i++)	
		{
			echo "<div class='col-md-3'>";
            if($rows != false )	//Cho View noi dung nao do
            {
?>               
                   <div class="single-product-wrapper">
                            <!-- Product Image -->
                            <div class="product-img">
                                <img src="admin_test/modul/uploads/<?php echo $rows['hinh']?>" alt="" class="card-img-top" >
                                <!-- Hover Thumb -->
                                <!-- <img class="hover-img" src="img/product-img/product-5.jpg" alt="" class="card-img-top" > -->

                                <!-- Product Badge -->
                                <div class="product-badge new-badge">
                                    <span>New</span>
                                </div>

                                <!-- Favourite -->
                                <div class="product-favourite">
                                    <a href="#" class="favme fa fa-heart"></a>
                                </div>
                            </div>
                            <!-- Product Description -->
                            <div class="product-description">
                                <span>mango</span>
                                <a href="single-product-details.html">
                                    <h6><?php echo $rows['Tensp']?></h6>
                                </a>
                                <p class="product-price">$80.00</p>

                                <!-- Hover Content -->
                                <div class="hover-content">
                                    <!-- Add to Cart -->
                                    <div class="add-to-cart-btn">
                                        <a href="#" class="btn essence-btn">Add to Cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
 <?php
			}
			else
			{
				echo "&nbsp;";
			}
			echo "</div>";
      		if($i != $n)
           	{
            	$rows = mysqli_fetch_array($result);
			}
		}	//KT For
?>
	</div>
	<?php
	}	//KT While
	?>          
  </div>
    </section>
    <!-- ##### New Arrivals Area End ##### -->

    <!-- ##### Brands Area Start ##### -->
    <div class="brands-area d-flex align-items-center justify-content-between">
        <!-- Brand Logo -->
        <div class="single-brands-logo">
            <img src="img/core-img/brand1.png" alt="">
        </div>
        <!-- Brand Logo -->
        <div class="single-brands-logo">
            <img src="img/core-img/brand2.png" alt="">
        </div>
        <!-- Brand Logo -->
        <div class="single-brands-logo">
            <img src="img/core-img/brand3.png" alt="">
        </div>
        <!-- Brand Logo -->
        <div class="single-brands-logo">
            <img src="img/core-img/brand4.png" alt="">
        </div>
        <!-- Brand Logo -->
        <div class="single-brands-logo">
            <img src="img/core-img/brand5.png" alt="">
        </div>
        <!-- Brand Logo -->
        <div class="single-brands-logo">
            <img src="img/core-img/brand6.png" alt="">
        </div>
    </div>
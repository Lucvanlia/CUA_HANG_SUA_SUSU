<?php 
        //Truy vấn lấy loại sản phẩm
        $sql_loai = "SELECT  l.tenloai,l.tenloai_class
                    FROM loai as l 
                    WHERE l.id_loai in (
                            SELECT sp.id_loai
                            FROM dmsp as sp
                    )";
        $result_loai = mysqli_query($link,$sql_loai);
        //Truy vấn lấy sản phẩm 
        $sql_all="SELECT * FROM 
          dmsp  as sp 
          join xuatxu   as xx on sp.id_xuatxu = xx.id_xuatxu 
          join hang     as h  on sp.id_hang = h.id_hang       
          join loai     as l  on sp.id_loai = l.id_loai            
          order by id_sp 
        ";
        //========================================ressult==================================================
        $result_all=mysqli_query($link, $sql_all);
        //==========================================================================================
        $count_all=mysqli_num_rows($result_all);

        ?>
<section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Featured Product</h2>
                    </div>
                    <div class="featured__controls">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            <?php 
                                 if ($result_loai->num_rows > 0) {
                                    while ($row = $result_loai->fetch_assoc()) {
                                        echo '<li data-filter=".' . $row['tenloai_class'] . '">' . $row['tenloai'] . '</li>';
                                    }
                                } else {
                                    echo '<li>Chưa tồn tại danh mục nào cả</li>';
                                }
                            ?>
                            <!-- <li data-filter=".oranges">Oranges</li>
                            <li data-filter=".fresh-meat">Fresh Meat</li>
                            <li data-filter=".vegetables">Vegetables</li>
                            <li data-filter=".fastfood1">Fastfood</li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row featured__filter" id="product-list">
      
            <?php 
                if ($result_all->num_rows > 0) {
                    while ($row = $result_all->fetch_assoc()) {
                     ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mix ' . $row['tenloai_class'] . '">
                            <div class="featured__item">
                                <div class="featured__item__pic set-bg" data-setbg="admin_test/modul/uploads/<?=  $row['hinh'] ?>">
                                    <ul class="featured__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                    
                                                                            
                                    </ul>
                                </div>
                                <div class="featured__item__text">
                                    <h6><a href="#"><?=  $row['Tensp'] ?></a></h6>
                                    <h5><?=  number_format($row['gia'],0,',','.')  ?></h5>
                                </div>
                                <?php 
                                    if($row["SoLuong"] > 0)
                                    {
                                ?>
                                <form action="" method="POST" class=" add-cart">
                                     <input type="hidden" value="1" name="quantity[<?php echo $row["id_sp"]?>]">
                                     <input class="btn btn-danger" type="submit" value="Thêm vào giỏ hàng" >
                                </form>
                                <?php 
                                    }
                                ?>
                            </div>
                        </div>
                        <?php 
                           }
                        } else {
                            echo "No products found";
                        }
                    ?>
                 

            </div>
          
        </div>
    </section>
    <!-- Thêm jQuery nếu chưa có -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $(".add-cart").submit(function (event) {
            event.preventDefault();
            alert("THÊM GIỞ HÀNG");
            console.log("data: ", $(this).serializeArray());
            $.ajax({

                
            });
        });
    });
</script>

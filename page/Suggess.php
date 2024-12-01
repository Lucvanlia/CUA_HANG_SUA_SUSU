<!-- Swiper CSS -->

<?php 

    $sql_dmlist   ="SELECT * FROM DanhMuc where parent_dm != 0  ";
    $query_dmlist = mysqli_query($link,$sql_dmlist);
?>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        <?php 
                while($row_dmlist = mysqli_fetch_array($query_dmlist))
                {

        ?>
        <a href="?action=product&query=all&id_dm=<?= $row_dmlist['id_dm']?>">
        <!-- Card 1 -->
        <div class="swiper-slide text-center p-3">
            <div class="card test ">
                <div class="card-body">
                    <img src="admin_test/uploads/<?= $row_dmlist['Hinh_dm']?>" alt="">
                    <h5 class="card-title mt-3"><?= $row_dmlist['Ten_dm']?></h5>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        </a>
        <?php 
                }
        ?>
    </div>
</div>
<style>
    .swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
}

.test  {
    width: 250px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
}
.test img{
    width: 150px;
}
.test i {
    color: #007bff;
}

</style>
<script>
    const swiper = new Swiper(".mySwiper", {
        slidesPerView: 5, // Hiển thị 3 card cùng lúc
        spaceBetween: 20, // Khoảng cách giữa các card
        loop: true, // Cho phép quay vòng liên tục
        autoplay: {
            delay: 0, // Không có khoảng dừng
            disableOnInteraction: false, // Tự động tiếp tục sau khi kéo
        },
        speed: 1000, // Tốc độ di chuyển (ms)
        grabCursor: true, // Cho phép kéo bằng tay
        slidesPerGroupAuto: true, // Di chuyển từng nhóm
    });
</script>
<!-- Thêm CSS Swiper -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">

<!-- Thêm JavaScript Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>


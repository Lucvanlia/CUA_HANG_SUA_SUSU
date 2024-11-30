<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        <!-- Card 1 -->
        <div class="swiper-slide text-center p-3">
            <div class="card test ">
                <div class="card-body">
                    <i class="bi bi-shop" style="font-size: 4rem;"></i>
                    <h5 class="card-title mt-3">Nhà cung cấp A</h5>
                    <p class="card-text">Cung cấp sản phẩm ABC</p>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="swiper-slide text-center p-3">
            <div class="card test ">
                <div class="card-body">
                    <i class="bi bi-basket3" style="font-size: 4rem;"></i>
                    <h5 class="card-title mt-3">Nhà cung cấp B</h5>
                    <p class="card-text">Cung cấp sản phẩm XYZ</p>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="swiper-slide text-center p-3">
            <div class="card test ">
                <div class="card-body">
                    <i class="bi bi-truck" style="font-size: 4rem;"></i>
                    <h5 class="card-title mt-3">Nhà cung cấp C</h5>
                    <p class="card-text">Cung cấp sản phẩm DEF</p>
                </div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="swiper-slide text-center p-3">
            <div class="card test ">
                <div class="card-body">
                    <i class="bi bi-building" style="font-size: 4rem;"></i>
                    <h5 class="card-title mt-3">Nhà cung cấp D</h5>
                    <p class="card-text">Cung cấp sản phẩm GHI</p>
                </div>
            </div>
        </div>
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

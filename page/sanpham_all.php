<?php

// Lấy thông tin từ URL
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_dm = isset($_GET['id_dm']) ? $_GET['id_dm'] : '';
$id_ncc = isset($_GET['id_ncc']) ? $_GET['id_ncc'] : '';
$timkiem = isset($_POST['search_product']) ? $_POST['search_product'] : '';
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0; // ID danh mục
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Số trang
$limit = 12; // Số sản phẩm trên mỗi trang
$offset = ($page - 1) * $limit; // Tính toán offset
$orderBy = '';

// Truy vấn sản phẩm dựa vào danh mục
$sql = "
   SELECT 
    SP.id_sp, 
    SP.Ten_sp, 
    SP.Hinh_Nen, 
    MIN(DG.GiaBan) AS GiaBan 
   FROM 
    SanPham SP
   JOIN 
    DonGia DG ON SP.id_sp = DG.id_sp
    Join
    NhaCungCap  NCC ON SP.id_ncc = NCC.id_ncc
   WHERE 
    SP.HoatDong = 0 
 
";

// Nếu có danh mục, lọc sản phẩm theo danh mục
if ($category_id > 0) {
    $sql .= " AND SP.id_dm = $category_id";
}
if ($id_ncc > 0) {
    $sql .= " AND SP.id_ncc = $id_ncc";
}
if (!empty($timkiem)) {
    $sql .= " AND SP.Ten_sp LIKE '%$timkiem%'";
}
$sql .= "
   GROUP BY 
    SP.id_sp, SP.Ten_sp, SP.Hinh_Nen
   ORDER BY 
    MIN(DG.GiaBan) DESc
";

// Thêm phân trang
$sql .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($link, $sql);

if ($sortOrder == 'asc') {
    $sql .= " AND SP.id_dm = $category_id ORDER BY dg.GiaBan ASC";
    // $orderBy = 'ORDER BY dg.GiaBan ASC';
} elseif ($sortOrder == 'desc') {
    $sql .= " AND SP.id_dm = $category_id ORDER BY dg.GiaBan ASC";
    // $orderBy = 'ORDER BY dg.GiaBan DESC';
}
// Đếm tổng số sản phẩm để phân trang
$sql_count = "SELECT COUNT(*) as total FROM SanPham SP WHERE SP.HoatDong = 0";
if ($category_id > 0) {
    $sql_count .= " AND SP.id_dm = $category_id";
}
if (!empty($timkiem)) {
    $sql_count .= " AND SP.Ten_sp LIKE '%$timkiem%'";
}
if ($id_ncc > 0) {
    $sql_count .= " AND SP.id_ncc = $id_ncc";
}
$total_result = mysqli_query($link, $sql_count);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit);

?>

<div class="container-fluid">
    <div class="row">
        <!-- Cột bên trái: Danh mục -->
        <div class="col-md-2">
            <h5>Danh mục</h5>
            <form action="?action=product&query=all" method="POST">
                <input type="text" id="searchQuery" name="search_product" placeholder="Nhập tên sản phẩm" class="form-control">
                <input class="btn btn-success mt-2 mb-3" type="submit" value="Tìm kiếm">
            </form>

            <?php
            // Kết nối database


            // Lấy danh mục từ cơ sở dữ liệu
            $query_dm = "SELECT id_dm, parent_dm, ten_dm FROM DanhMuc ORDER BY parent_dm ASC, id_dm ASC";
            $result_dm = mysqli_query($link, $query_dm);

            if (!$result_dm) {
                die("Lỗi truy vấn: " . mysqli_error($link));
            }

            // Tổ chức dữ liệu danh mục thành cây
            function buildCategoryTree($categories)
            {
                $tree = [];
                $map = [];

                foreach ($categories as $category) {
                    $category['children'] = [];
                    $map[$category['id_dm']] = $category;
                }

                foreach ($categories as $category) {
                    if ($category['parent_dm'] == 0) {
                        $tree[] = &$map[$category['id_dm']];
                    } else {
                        if (isset($map[$category['parent_dm']])) {
                            $map[$category['parent_dm']]['children'][] = &$map[$category['id_dm']];
                        }
                    }
                }

                return $tree;
            }

            // Hiển thị cây danh mục
            function renderCategoryTree($categories)
            {
                echo '<ul>';
                foreach ($categories as $category) {
                    echo '<li>';
                    
                    // Nếu danh mục không có con, gán liên kết
                    if (empty($category['children'])) {
                        echo '<a href="https://banhangviet-tmi.net/doan_php/?action=product&query=all&category_id=' . $category['id_dm'] . '">';
                    }
            
                    // Nếu có danh mục con, thêm nút toggle
                    if (!empty($category['children'])) {
                        echo '<span class="toggle">+</span> ';
                    }
            
                    // Tên danh mục
                    echo $category['ten_dm'];
            
                    // Đóng thẻ <a> nếu là danh mục con
                    if (empty($category['children'])) {
                        echo '</a>';
                    }
            
                    // Nếu có danh mục con, gọi đệ quy để hiển thị danh mục con
                    if (!empty($category['children'])) {
                        echo '<div class="nested">';
                        renderCategoryTree($category['children']);
                        echo '</div>';
                    }
            
                    echo '</li>';
                }
                echo '</ul>';
            }
            
            // Lấy dữ liệu và hiển thị
            $categories = [];
            while ($row = mysqli_fetch_assoc($result_dm)) {
                $categories[] = $row;
            }

            $categoryTree = buildCategoryTree($categories);
            ?>

            <div id="categoryContainer">
                <h5>Lựa chọn theo danh mục</h5>
                <?php renderCategoryTree($categoryTree); ?>
            </div>

            <h5>Thương hiệu</h5>
            <div class="block-vendor mt-3 ">
                <?php
                $sql_ncc = "SELECT DISTINCT 
                            NCC.Hinh_ncc, 
                            NCC.id_ncc
                           FROM 
                            NhaCungCap NCC
                            JOIN 
                            SanPham SP ON NCC.id_ncc = SP.id_ncc
                            WHERE 
                            EXISTS (
                                SELECT 1 
                                FROM DanhMuc DM 
                                WHERE DM.id_dm = SP.id_dm
                            ) 
                            AND NCC.Hoatdong = 0; ";
                $result_ncc = mysqli_query($link, $sql_ncc);

                while ($ncc = mysqli_fetch_assoc($result_ncc)) {
                    $hinh_ncc = $ncc['Hinh_ncc'];
                ?>
                    <a href="https://banhangviet-tmi.net/doan_php/?action=product&query=all&id_ncc=<?= $ncc['id_ncc'] ?>">
                        <img src="admin_test/uploads/nhacungcap/<?= $hinh_ncc ?>" alt="Nhà cung cấp" class="rounded-circle" style="width: 100px; height: 50px; object-fit: cover;">
                    </a>
                <?php } ?>
            </div>


        </div>


        <div class="col-md-10 container-fluid" id="list-sanpham">

            <form id="filterForm" class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5>Lọc theo giá:</h5>
                    <select id="priceFilter" class="form-select">
                        <option value="all">Tất cả</option>
                        <option value="0-500000">Dưới 500,000 VND</option>
                        <option value="500000-1000000">500,000 - 1,000,000 VND</option>
                        <option value="1000000-2000000">1,000,000 - 2,000,000 VND</option>
                        <option value="2000000">Trên 2,000,000 VND</option>
                    </select>
                </div>
                <div>
                    <h5>Sắp xếp:</h5>
                    <select id="sortOrder" class="form-select">
                        <option value="none">Không sắp xếp</option>
                        <option value="asc">Giá tăng dần</option>
                        <option value="desc">Giá giảm dần</option>
                    </select>
                </div>
            </form>
            <div class="row">
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card shadow-sm h-100 test">
                            <img src="admin_test/uploads/sanpham/<?php echo $product['Hinh_Nen']; ?>"
                                class="card-img-top zoom-on-hover"
                                alt="<?php echo $product['Ten_sp']; ?>"
                                style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <a href="index.php?action=product&query=details&id=<?php echo $product['id_sp']; ?>">
                                    <h6 class="card-title"><?php echo $product['Ten_sp']; ?></h6>
                                </a>
                                <p class="text-danger fw-bold"><?php echo number_format($product['GiaBan'], 0, ',', '.'); ?> VND</p>
                            </div>
                            <button type="button" id="btnDetail" class="btn btn-success" data-id="<?php echo $product['id_sp']; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Mua ngay
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Phân trang -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item    <?php echo $i == $page ? 'active' : ''; ?>" style="background-color: #66a531;">
                            <a class="page-link     " style="background-color: #66a531;" href="?action=product&query=all&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>


        <!-- Cột chính giữa: Sản phẩm -->
        <div class="col-md-9">
            <div class="row" id="product-list">
                <!-- Sản phẩm sẽ được load từ AJAX -->
            </div>
        </div>
    </div>
</div>
<style>
    /* Ẩn danh mục con mặc định */
    /* Ẩn danh mục con mặc định */
    .nested {
        display: none;
        margin-left: 20px;
    }

    /* Hiển thị khi danh mục được mở */
    .nested.open {
        display: block;
    }

    /* Kiểu dáng cho toggle */
    .toggle {
        cursor: pointer;
        font-weight: bold;
        margin-right: 5px;
    }

    /* Định dạng danh mục */
    #categoryContainer ul {
        list-style-type: none;
        padding: 0;
    }

    #categoryContainer li {
        margin-bottom: 5px;
    }

    #categoryContainer li .toggle {
        color: #66a531;
    }

    #categoryContainer li:hover {
        color: #333;
        font-weight: bold;
    }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.toggle');

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const nested = this.parentElement.querySelector('.nested'); // Tìm phần tử danh mục con
            if (nested.classList.contains('open')) {
                nested.classList.remove('open'); // Ẩn danh mục con
                this.textContent = "+"; // Đổi thành dấu "+"
            } else {
                nested.classList.add('open'); // Hiện danh mục con
                this.textContent = "-"; // Đổi thành dấu "-"
            }
        });
    });
});


</script>

<script>
    document.getElementById('priceFilter').addEventListener('change', filterAndSortProducts);
    document.getElementById('sortOrder').addEventListener('change', filterAndSortProducts);

    function filterAndSortProducts() {
        const priceFilterValue = document.getElementById('priceFilter').value;
        const sortOrderValue = document.getElementById('sortOrder').value;

        const productCards = Array.from(document.querySelectorAll('#list-sanpham .card'));

        // Lọc sản phẩm theo khoảng giá
        productCards.forEach(product => {
            const priceText = product.querySelector('.text-danger').textContent;
            const price = parseInt(priceText.replace(/\D/g, ''));

            // Hiển thị tất cả nếu chọn "Tất cả"
            if (priceFilterValue === 'all') {
                product.parentElement.style.display = 'block';
            } else {
                const [min, max] = priceFilterValue.split('-').map(Number);
                if (max) {
                    // Lọc theo khoảng giá
                    product.parentElement.style.display = (price >= min && price <= max) ? 'block' : 'none';
                } else {
                    // Lọc giá lớn hơn giá trị tối thiểu
                    product.parentElement.style.display = (price >= min) ? 'block' : 'none';
                }
            }
        });

        // Sắp xếp sản phẩm theo giá
        if (sortOrderValue !== 'none') {
            const visibleProducts = productCards.filter(product => product.parentElement.style.display !== 'none');

            visibleProducts.sort((a, b) => {
                const priceA = parseInt(a.querySelector('.text-danger').textContent.replace(/\D/g, ''));
                const priceB = parseInt(b.querySelector('.text-danger').textContent.replace(/\D/g, ''));
                return sortOrderValue === 'asc' ? priceA - priceB : priceB - priceA;
            });

            // Cập nhật thứ tự hiển thị
            const productContainer = document.querySelector('#list-sanpham .row');
            visibleProducts.forEach(product => {
                productContainer.appendChild(product.parentElement); // Di chuyển sản phẩm đã sắp xếp
            });
        }
    }
</script>


<script>
    $(document).ready(function() {
        // Xử lý nhấn vào danh mục
        $('.category-item').on('click', function() {
            var categoryId = $(this).data('category');
            $('#list-sanpham').hide();
            // Hiệu ứng toggle sổ xuống danh mục con
            $('#child-' + categoryId).collapse('toggle');

            // Gửi yêu cầu AJAX để load sản phẩm
            // $.ajax({
            //     url: 'fetch_products.php',
            //     type: 'GET',
            //     data: {
            //         category_id: categoryId
            //     },
            //     success: function(response) {

            //         $('#product-list').html(response);
            //     },
            //     error: function() {
            //         alert('Không thể load sản phẩm. Vui lòng thử lại!');
            //     }
            // });
        });
        // $('.parent_cha').on('click', function() {
        //     var categoryId = $(this).data('category');
        //     $('#list-sanpham').hide();
        //     // Hiệu ứng toggle sổ xuống danh mục con
        //     $('#child-' + categoryId).collapse('toggle');

        //     // Gửi yêu cầu AJAX để load sản phẩm
        //     $.ajax({
        //         url: 'fetch_products.php',
        //         type: 'GET',
        //         data: {
        //             category_id: categoryId
        //         },
        //         success: function(response) {

        //             $('#product-list').html(response);
        //         },
        //         error: function() {
        //             alert('Không thể load sản phẩm. Vui lòng thử lại!');
        //         }
        //     });
        // });
        // $('#SapXepGia').on('change', function() {
        //     var SapXepGia = $(this).val(); // Lấy giá trị sắp xếp
        //     $('#list-sanpham').hide(); // Ẩn danh sách sản phẩm trong khi load

        //     // Gửi yêu cầu AJAX
        //     $.ajax({
        //         url: 'fetch_products.php', // File PHP xử lý yêu cầu
        //         type: 'GET',
        //         data: {
        //             SapXepGia: SapXepGia // Gửi giá trị sắp xếp
        //         },
        //         success: function(response) {
        //             $('#product-list').html(response).fadeIn(); // Hiển thị lại sản phẩm
        //         },
        //         error: function() {
        //             alert('Không thể load sản phẩm. Vui lòng thử lại!');
        //         }
        //     });
        // });
    });

    $(document).ready(function() {
        // Xử lý tìm kiếm


        // Tìm kiếm khi người dùng nhấn Enter
        $('#searchQuery').on('keypress', function(e) {
            if (e.which == 13) { // 13 là mã phím Enter
                $('#btnSearch').click();
            }
        });
    });
</script>

<style>
    .test {
        width: 230px;
    }

    .card-img-top {
        transition: transform 0.3s ease-in-out;
    }

    .card-img-top:hover {
        transform: scale(1.1);
    }

    .card-body h6 {
        font-size: 14px;
        color: #333;
        margin-bottom: 10px;
    }

    .text-danger.fw-bold {
        font-size: 16px;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    .pagination .page-link {
        color: #007bff;
    }

    .pagination .page-link:hover {
        background-color: #0056b3;
        color: white;
    }

    .list-group-flush {
        color: black !important;
        font-size: 19px;
    }

    a {
        color: black !important;
    }

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
        font-size: 20px;
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
        height: 900px;
    }

    /* Đảm bảo chiều cao của banner tự động điều chỉnh */
    .block-banner .banner-img {
        width: 100%;
        /* Chiếm hết chiều rộng của phần chứa */
        height: 850px;
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
        transition: transform 0.3s ease, filter 0.3s ease;
        /* Chuyển động mượt mà */
    }

    .zoom-on-hover:hover {
        transform: scale(1.1);
        /* Zoom ảnh lên 10% */
        filter: brightness(1.1);
        /* Tăng độ sáng nhẹ */
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
        font-size: 19px;
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
        transition: filter 0.3s ease, box-shadow 0.3s ease;
        /* Thêm chuyển đổi mượt mà */

    }

    /* Ẩn đường viền khi hover trên card */
    .card:hover {
        border: none !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        /* Đổ bóng nhẹ khi hover */
        filter: drop-shadow(16px 38px 45px rgba(0, 0, 0, 0.2));
        transform: translateY(-5px);
        /* Card di chuyển nhẹ lên trên */
    }

    a:hover {
        color: #7fad39 !important;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<!-- Bootstrap Bundle (chứa cả Popper.js và JS của Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
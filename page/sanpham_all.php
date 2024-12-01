<?php

// Lấy thông tin từ URL
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_dm = isset($_GET['id_dm']) ? $_GET['id_dm'] : '';
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
   WHERE 
    SP.HoatDong = 0 
";

// Nếu có danh mục, lọc sản phẩm theo danh mục
if ($category_id > 0) {
    $sql .= " AND SP.id_dm = $category_id";
}

$sql .= "
   GROUP BY 
    SP.id_sp, SP.Ten_sp, SP.Hinh_Nen
   ORDER BY 
    MAX(DG.GiaBan) DESc
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
            <input type="text" id="searchQuery" placeholder="Nhập tên sản phẩm" class="form-control">
            <button id="btnSearch" class="btn btn-primary mt-3">Tìm kiếm</button>
            <ul class="list-group" id="category-list">
                <?php
                // Hàm hiển thị danh mục cha và con
                function renderCategories($categories, $parentId = 0)
                {
                    foreach ($categories as $category) {
                        if ($category['parent_dm'] == $parentId) {
                            echo '<li class="list-group-flush " style="list-style-type:none">';
                            echo '<a href="javascript:void(0)" class="category-item parent_cha" data-category="' . $category['id_dm'] . '">';
                            echo '';
                            echo $category['Ten_dm'] . ' ';
                            echo '</a>';

                            // Danh mục con
                            $children = array_filter($categories, function ($cat) use ($category) {
                                return $cat['parent_dm'] == $category['id_dm'];
                            });

                            if (count($children) > 0) {
                                echo '<ul class="list-group ms-3 collapse" id="child-' . $category['id_dm'] . '" data-category="' . $category['id_dm'] . '">';
                                renderCategories($categories, $category['id_dm']);
                                echo '</ul>';
                            }

                            echo '</li>';
                        }
                    }
                }

                // Truy vấn danh mục từ cơ sở dữ liệu
                $sql_categories = "
                    SELECT dm.id_dm, dm.Ten_dm, dm.parent_dm, COUNT(sp.id_sp) as SoLuong
                    FROM DanhMuc dm
                    LEFT JOIN SanPham sp ON dm.id_dm = sp.id_dm AND sp.HoatDong = 0
                    WHERE dm.Hoatdong = 0 and dm.id_dm
                    GROUP BY dm.id_dm
                    ORDER BY dm.parent_dm, dm.id_dm";
                $result_categories = mysqli_query($link, $sql_categories);
                $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

                // Gọi hàm render danh mục
                renderCategories($categories);
                ?>
            </ul>
        </div>

        <div class="col-md-10 container-fluid" id="list-sanpham">
            <div class="d-flex justify-content-end mb-3">
                <select id="SapXepGia" class="form-select w-auto">
                    <option value="default" selected>Sắp xếp</option>
                    <option data-sort="asc" value="asc">Giá: Thấp đến Cao</option>
                    <option data-sort="asc" value="desc">Giá: Cao đến Thấp</option>
                </select>
            </div>
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
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?action=product&query=all&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>


        <!-- Cột chính giữa: Sản phẩm -->
        <div class="col-md-9">
            <h4 class="mb-3">Tất cả sản phẩm</h4>
            <div class="row" id="product-list">
                <!-- Sản phẩm sẽ được load từ AJAX -->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Xử lý nhấn vào danh mục
        $('.category-item').on('click', function() {
            var categoryId = $(this).data('category');
            $('#list-sanpham').hide();
            // Hiệu ứng toggle sổ xuống danh mục con
            $('#child-' + categoryId).collapse('toggle');

            // Gửi yêu cầu AJAX để load sản phẩm
            $.ajax({
                url: 'fetch_products.php',
                type: 'GET',
                data: {
                    category_id: categoryId
                },
                success: function(response) {

                    $('#product-list').html(response);
                },
                error: function() {
                    alert('Không thể load sản phẩm. Vui lòng thử lại!');
                }
            });
        });
        $('.parent_cha').on('click', function() {
            var categoryId = $(this).data('category');
            $('#list-sanpham').hide();
            // Hiệu ứng toggle sổ xuống danh mục con
            $('#child-' + categoryId).collapse('toggle');

            // Gửi yêu cầu AJAX để load sản phẩm
            $.ajax({
                url: 'fetch_products.php',
                type: 'GET',
                data: {
                    category_id: categoryId
                },
                success: function(response) {

                    $('#product-list').html(response);
                },
                error: function() {
                    alert('Không thể load sản phẩm. Vui lòng thử lại!');
                }
            });
        });
        $('#SapXepGia').on('change', function() {
            var SapXepGia = $(this).val(); // Lấy giá trị sắp xếp
            $('#list-sanpham').hide(); // Ẩn danh sách sản phẩm trong khi load

            // Gửi yêu cầu AJAX
            $.ajax({
                url: 'fetch_products.php', // File PHP xử lý yêu cầu
                type: 'GET',
                data: {
                    SapXepGia: SapXepGia // Gửi giá trị sắp xếp
                },
                success: function(response) {
                    $('#product-list').html(response).fadeIn(); // Hiển thị lại sản phẩm
                },
                error: function() {
                    alert('Không thể load sản phẩm. Vui lòng thử lại!');
                }
            });
        });
    });

    $(document).ready(function() {
        // Xử lý tìm kiếm
        $('#btnSearch').on('click', function() {
            var query = $('#searchQuery').val(); // Lấy giá trị tìm kiếm
            $('#list-sanpham').hide(); // Ẩn danh sách cũ trong khi load

            // Gửi yêu cầu AJAX để tìm kiếm
            $.ajax({
                url: 'fetch_products123.php', // File xử lý tìm kiếm
                type: 'GET',
                data: {
                    query: query // Gửi từ khóa tìm kiếm
                },
                success: function(response) {
                    $('#product-list').html(response).fadeIn(); // Hiển thị kết quả
                },
                error: function() {
                    alert('Không thể tìm kiếm sản phẩm. Vui lòng thử lại!');
                }
            });
        });

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
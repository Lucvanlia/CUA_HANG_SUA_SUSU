<?php
include "admin_test/ketnoi/conndb.php";

if (isset($_GET['category_id'])) {
    $categoryId = intval($_GET['category_id']);
    
    // Lấy sản phẩm theo danh mục
    $sql_products = "
        SELECT sp.*, dm.Ten_dm, ncc.Ten_ncc, xx.Ten_xx,DG.GiaBan
        FROM SanPham sp
        LEFT JOIN DanhMuc dm ON sp.id_dm = dm.id_dm
        LEFT JOIN NhaCungCap ncc ON sp.id_ncc = ncc.id_ncc
        LEFT JOIN XuatXu xx ON sp.id_xx = xx.id_xx
        LEFT JOIN DonGia DG ON SP.id_sp = DG.id_sp
        WHERE sp.HoatDong = 0 AND sp.id_dm = $categoryId";

    $result_products = mysqli_query($link, $sql_products);

    // Render sản phẩm
    while ($product = mysqli_fetch_assoc($result_products)) {
       
        echo '
            <div class="col-md-2 col-sm-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-img-container">
                        <img src="admin_test/uploads/sanpham/' . $product['Hinh_Nen'] . '" class="card-img-top zoom-on-hover" alt="' . $product['Ten_sp'] . '" style="height: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body text-center">
                        <a href="index.php?action=product&query=details&id=' . $product['id_sp'] . '">
                            <h6 class="card-title">' . $product['Ten_sp'] . '</h6>
                        </a>
                        <p class="text-danger fw-bold">' . number_format($product['GiaBan'], 0, ',', '.'). '</p>
                    </div>
                    <button type="button" id="btnDetail" class="btn btn-success" data-id="' . $product['id_sp'] . '>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Mua ngay
                    </button>
                </div>
            </div>
        ';
    }
}

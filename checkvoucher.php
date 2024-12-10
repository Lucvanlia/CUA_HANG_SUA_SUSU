<?php
session_start();
header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON
include "admin_test/ketnoi/conndb.php";

if (!isset($_POST['voucher']) || empty($_POST['voucher'])) {
    echo json_encode(['status' => 'error', 'message' => 'Mã khuyến mãi không được cung cấp.']);
    exit;
}

$voucher_code = $_POST['voucher'];

// Lấy thông tin mã khuyến mãi từ bảng ChuongTrinhKM
$query = "SELECT * FROM ChuongTrinhKM WHERE MaKM = '$voucher_code' AND CURDATE() BETWEEN NgayBatDau AND NgayKetThuc";
$result = mysqli_query($link, $query);
$voucher = mysqli_fetch_assoc($result);
$name_voucher =  $voucher['TenCTKM'];
$_SESSION['km'] =  $voucher['id_ctkm'];
if ($voucher) {
    $discount = 0;
    $discount_message = '';
    $total_amount = 0;
    $product_discounts = [];
    $product_discounts_name = [];
    $bill_discount_message = '';

    // Tính tổng giá trị hóa đơn từ giỏ hàng
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['GiaBan'] * $item['SoLuong'];
        }
    }

    // Áp dụng giảm giá cho sản phẩm
    $query_products = "SELECT * FROM KMSanPham WHERE id_ctkm = " . $voucher['id_ctkm'];
    $result_products = mysqli_query($link, $query_products);

    while ($product = mysqli_fetch_assoc($result_products)) {
        foreach ($_SESSION['cart'] as &$item) {
            $query_dv = "SELECT Ten_sp FROM SanPham WHERE id_sp = $item[id_sp]";
            $result_dv = mysqli_query($link, $query_dv);
            $row_dv = mysqli_fetch_assoc($result_dv);
            $product_name = htmlspecialchars($row_dv['Ten_sp']);
            // if (mb_strlen($product_name, 'UTF-8') > 26) {
            //     $product_name = mb_substr($product_name, 0, 26, 'UTF-8') . '...';
            // }
            if ($item['id_sp'] == $product['id_sp']) {
                $product_discount = $item['GiaBan'] * ($product['GiamGia'] / 100);
                $discount += $product_discount * $item['SoLuong'];
                $item['discount'] = $product_discount * $item['SoLuong']; // Thêm giảm giá sản phẩm vào giỏ hàng

                // Lưu thông tin giảm giá sản phẩm
                // $product_discounts[] = "- " . number_format($product_discount * $item['SoLuong'], 0, ',', '.') . " VNĐ cho sản phẩm " . $product_name;
                $product_discounts[] = "- " . number_format($product_discount * $item['SoLuong'], 0, ',', '.') . " VNĐ";
                $product_discounts_name[] = $product_name;

            }
        }
    }

    // Áp dụng giảm giá cho hóa đơn
    $query_hd = "SELECT * FROM KMHoaDon WHERE id_ctkm = " . $voucher['id_ctkm'];
    $result_hd = mysqli_query($link, $query_hd);
    $discount_info = mysqli_fetch_assoc($result_hd);

    if ($discount_info && $total_amount >= $discount_info['DieuKienHoaDon']) {
        $bill_discount = $total_amount * ($discount_info['GiamGia'] / 100);
        $discount += $bill_discount;
        // $bill_discount_message = "- Điều kiện đặt " . number_format($discount_info['DieuKienHoaDon'], 0, ',', '.') . " VNĐ: giảm " . $discount_info['GiamGia'] . "% - " . number_format( $bill_discount, 0, ',', '.') . "VNĐ ";
        $bill_discount_message = "- Điều kiện khi mua hơn: " . number_format($discount_info['DieuKienHoaDon'], 0, ',', '.') . " VNĐ";
        $bill_discount_dieukien = "Giảm " . $discount_info['GiamGia'] . "%";
        $bill_discount_value = number_format( $bill_discount, 0, ',', '.') . "VNĐ ";

    }

    // Kiểm tra nếu không có giảm giá nào áp dụng
    if ($discount == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Mã khuyến mãi không áp dụng được.']);
        exit;
    }

    // Tính tổng tiền sau khi áp dụng giảm giá
    $total_after_discount = $total_amount - $discount;

    // Lưu thông tin giảm giá vào session
    $_SESSION['discount'] = $discount;
    $_SESSION['MAKM'] = $voucher_code ;
    $_SESSION['discount_message'] = $discount_message;
    $_SESSION['new_bill'] = $total_after_discount ;
    // Trả về kết quả
    echo json_encode([
        'status' => 'success',
        'message' => "Mã khuyến mãi đã được áp dụng!",
        'namevoucher' => "$name_voucher",
        'total_before' => number_format($total_amount, 0, ',', '.') . " VNĐ",
        'product_discounts' => $product_discounts,
        'product_discounts_name' => $product_discounts_name,
        'bill_discount_message' => $bill_discount_message,
        'bill_discount_value' => $bill_discount_value,
        'bill_discount_dieukien' => $bill_discount_dieukien,
        'discount' => number_format($discount, 0, ',', '.') . " VNĐ",
        'total_after' => number_format($total_after_discount, 0, ',', '.') . " VNĐ"
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn.']);
}

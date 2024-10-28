<?php
session_start();
include "admin_test/ketnoi/conndb.php";
/* if(isset($_POST['user_id']) && isset($_POST['star']) && isset($_POST['description'])){
    $user_id = $_POST['user_id'];
    $star = $_POST['star'];
    $description = $_POST['description'];
    $created_at =time();
    // Lưu đánh giá vào cơ sở dữ liệu
    $sql = "INSERT INTO product_feedback (id_kh, rating, comment,created_at) VALUES ('$user_id', '$star', '$description','$created_at')";
    if(mysqli_query($link, $sql)){
        // Sau khi thêm đánh giá mới, hiển thị lại tất cả đánh giá
        include 'fetch_feedback.php';
    } else {
        echo 'Không thể lưu đánh giá, vui lòng thử lại.';
    }
}*/

if (isset($_POST['id'])) {
    $tong = 0;
    $status = $_POST['status'];
    // Kiểm tra giỏ hàng đã tồn tại chưa, nếu chưa thì tạo
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    switch ($status) {
        case 'add':
            $id = $_POST['id'];
            $ten = $_POST['ten'];
            $gia = $_POST['gia'];
            $hinh = $_POST['hinh'];
            $soluong = $_POST['soluong'];
            $maxStock = 20; // Giả sử số lượng tồn kho là 20 (cần lấy từ DB)

            $exists = false;
            $tong = 0; // Khởi tạo tổng tiền

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            foreach ($_SESSION['cart'] as &$cartItem) {
                if ($cartItem[0] == $id) {


                    // Nếu sản phẩm đã tồn tại, kiểm tra số lượng
                    $newQuantity = $cartItem[4] + $soluong;
                    if ($newQuantity <= $maxStock) {
                        $cartItem[4] = $newQuantity; // Cập nhật số lượng mới
                        $exists = true; // Đánh dấu sản phẩm đã tồn tại
                    } else {
                        echo json_encode(['status' => 'exceeded']); // Số lượng vượt quá số lượng tồn kho
                        exit(); // Thoát ngay sau khi gửi thông báo
                    }


                    break; // Thoát vòng lặp nếu đã tìm thấy sản phẩm
                }
            }

            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            if (!$exists) {
                $item = array($id, $ten, $gia, $hinh, $soluong);
                $_SESSION['cart'][] = $item; // Thêm sản phẩm mới vào giỏ hàng
                $exists = true; // Đánh dấu sản phẩm mới đã được thêm
            }

            // Tính toán tổng tiền
            foreach ($_SESSION['cart'] as $item) {
                $tt = $item[2] * $item[4]; // Thành tiền cho sản phẩm
                $tong += $tt; // Cộng vào tổng tiền
            }

            // Lưu tổng tiền vào session
            $_SESSION['tong_tien'] = $tong;

            // Trả về thông tin cho client
            echo json_encode(['status' => $exists ? 'added' : 'new', 'total' => $tong]);

            exit();
            break;


        case 'del-item':
            if (count($_SESSION['cart']) <= 0) {
                $tong = 0;
                $_SESSION['tong_tien'] = $tong;
            }
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 && isset($_POST['id'])) {
                $itemId = $_POST['id'];
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item[0] == $itemId) {
                        unset($_SESSION['cart'][$key]); // Xóa sản phẩm khỏi giỏ
                        break;
                    }
                }

                // Khởi tạo tổng tiền
                $tong = 0;

                // Tính tổng tiền sau khi xóa sản phẩm
                foreach ($_SESSION['cart'] as $item) {
                    $tt = $item[2] * $item[4]; // Thành tiền cho sản phẩm
                    $tong += $tt; // Cộng vào tổng tiền
                }

                // Trả về thông tin cho client
                header('Content-Type: application/json');

                // Lưu tổng tiền vào session
                $_SESSION['tong_tien'] = $tong;
                echo json_encode(['status' => 'success', 'total' => $tong]);
                exit();
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'false']);
                exit();
            }
            break;
        case 'sl':
            if (isset($_POST['cart'])) {
                $cart = $_POST['cart'];

                // Duyệt qua từng sản phẩm để tính tổng tiền
                foreach ($cart as $item) {
                    $tongTien += $item['quantity'] * $item['price'];
                }

                // Cập nhật tổng tiền vào session
                $_SESSION['tongtien'] = $tongTien;

                // Trả về tổng tiền cho jQuery
                echo number_format($tongTien, 0, ',', '.');
            }
            break;
        case 'get-max-stock':
            $id = intval($_POST['id']);
            // Truy vấn cơ sở dữ liệu để lấy số lượng tồn kho của sản phẩm
            $sql = "SELECT SoLuong FROM dmsp WHERE id_sp = $id";
            $result = $link->query($sql);

            if ($result->num_rows > 0) {
                // Lấy kết quả truy vấn
                $row = $result->fetch_assoc();
                $maxStock = $row['stock'];
                // Trả về kết quả dưới dạng JSON
                echo json_encode(['maxStock' => $maxStock]);
            } else {
                // Trả về lỗi nếu không tìm thấy sản phẩm
                echo json_encode(['error' => 'Sản phẩm không tồn tại']);
            }
            break;
    }
}
if (isset($_POST['payment_method'])) {
    $paymentMethod = $_POST['payment_method'];

    if ($paymentMethod === 'cod') {
        // Bắt đầu giao dịch
        mysqli_begin_transaction($link);
        $success = true; // Biến để kiểm tra nếu có lỗi xảy ra

        foreach ($_SESSION['cart'] as $product_details) {
            $product_id = $product_details[0];  // ID sản phẩm
            $quantity = $product_details[4];    // Số lượng sản phẩm

            // Kiểm tra số lượng tồn kho
            $query = "SELECT SoLuong FROM dmsp WHERE id_sp = $product_id";
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_assoc($result);

            if ($row['SoLuong'] >= $quantity) {
                // Cập nhật số lượng nếu đủ tồn kho
                $updateQuery = "UPDATE dmsp SET SoLuong = SoLuong - $quantity WHERE id_sp = $product_id";
                if (!mysqli_query($link, $updateQuery)) {
                    $success = false;
                    break;
                }
            } else {
                // Nếu không đủ tồn kho
                echo "Sản phẩm  $product_details[1] hiện không đủ số lượng. Vui lòng chọn lại.";
                $success = false;
                break;
            }
        }

        if ($success) {
            // Thêm hóa đơn vào bảng hoadon (thực hiện sau khi trừ kho thành công)
            $id_khachhang = $_SESSION['id_user'];
            $ngay_tao = time();
            $tong_tien = 0;

            // Tính tổng tiền
            foreach ($_SESSION['cart'] as $product_details) {
                $quantity = $product_details[2];
                $price = $product_details[4]; // Giả sử giá sản phẩm lưu tại chỉ mục 1
                $tong_tien += $quantity * $price;
            }

            $insertOrder = "INSERT INTO hoadon (id_kh,NgayLapHD,TrangThai,pttt,tongtien,thanhtoan) VALUES ($id_khachhang, $ngay_tao,'1','0', $tong_tien,'0')";
            if (mysqli_query($link, $insertOrder)) {
                // Thêm chi tiết hóa đơn
                $id_hoadon = mysqli_insert_id($link);
                foreach ($_SESSION['cart'] as $product_details) {
                    $product_id = $product_details[0];
                    $quantity = $product_details[4];
                    $price = $product_details[2];

                    $insertDetail = "INSERT INTO ctiethd (id_hd, id_sp, SoLuong, dongia) 
                                     VALUES ($id_hoadon, $product_id, $quantity, $price)";
                    if (!mysqli_query($link, $insertDetail)) {
                        $success = false;
                        break;
                    }
                }
            } else {
                $success = false;
            }
        }

        // Nếu thành công thì commit, nếu không thì rollback
        if ($success) {
            mysqli_commit($link);
            echo "success_cod";
        } else {
            mysqli_rollback($link);
            // echo "Không thể thêm";
        }
    } elseif ($paymentMethod === 'vnpay') {
        // Xử lý thanh toán qua VNPay
        echo "success_vnpay";
    }
} 

// xử lý giỏ hàng
// Kết nối cơ sở dữ liệu

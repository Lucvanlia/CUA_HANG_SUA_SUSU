<?php
session_start();
include "admin_test/ketnoi/conndb.php";
// Kiểm tra tạm thời
error_log(print_r($_POST, true)); // Kiểm tra dữ liệu trong console PHP
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

$status = isset($_POST['status']) ? $_POST['status'] : '';

if (!$status) {
    echo json_encode(['status' => 'error', 'message' => 'Không xác định được trạng thái yêu cầu.']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = &$_SESSION['cart']; // Tham chiếu giỏ hàng

switch ($status) {
    case 'add_to_cart':
        $id_sp = isset($_POST['id_sp']) ? (int)$_POST['id_sp'] : 0;
        $id_dv = isset($_POST['id_dv']) ? (int)$_POST['id_dv'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $max_quantity = isset($_POST['max_quantity']) ? (int)$_POST['max_quantity'] : 0;
        $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;

        if ($id_sp <= 0 || $id_dv <= 0 || $quantity <= 0 || $quantity > $max_quantity) {
            error_log("Invalid data: id_sp=$id_sp, id_dv=$id_dv, quantity=$quantity, max_quantity=$max_quantity, price=$price");
            echo json_encode(['status' => 'error', 'message' => "Dữ liệu không hợp lệ hoặc vượt giới hạn: id_sp=$id_sp, id_dv=$id_dv, quantity=$quantity, max_quantity=$max_quantity, price=$price"]);
            exit();
        }

        $found = false;

        foreach ($cart as &$item) {
            if ($item['id_sp'] === $id_sp && $item['id_dv'] === $id_dv) {
                if ($item['SoLuong'] + $quantity > $max_quantity) {
                    echo json_encode(['status' => 'error', 'message' => 'Bạn đã mua số lượng tối đa của sản phẩm này.']);
                    exit();
                }

                $item['SoLuong'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'id_sp' => $id_sp,
                'id_dv' => $id_dv,
                'SoLuong' => $quantity,
                'GiaBan' => $price,
                'max_quantity' => $max_quantity
            ];
        }

        echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được thêm vào giỏ hàng.']);
            exit();
            break;
    case 'del-item':
        $id_sp = $_POST['id']; // Lấy ID sản phẩm cần xóa
        $cart = &$_SESSION['cart']; // Giỏ hàng trong session

        // Kiểm tra nếu sản phẩm có tồn tại trong giỏ hàng
        $found = false;
        foreach ($cart as $key => $item) {
            if ($item['id_sp'] == $id_sp) {
                unset($cart[$key]); // Xóa sản phẩm khỏi giỏ hàng
                $found = true;
                break;
            }
        }

        // Nếu không tìm thấy sản phẩm trong giỏ hàng
        if (!$found) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.'
            ]);
            exit;
        }

        // Cập nhật lại tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['SoLuong'] * $item['GiaBan'];
        }

        // Kiểm tra nếu giỏ hàng trống
        $cartEmpty = empty($cart);

        // Trả về dữ liệu cho AJAX
        echo json_encode([
            'status' => 'success',
            'total' => $total,
            'cartEmpty' => $cartEmpty
        ]);
        exit;
        break;
    case 'update-cart':
        $productId = $_POST['id'];
        $quantity = $_POST['quantity'];
        $cart = &$_SESSION['cart'];

        // Lấy số lượng tối đa của sản phẩm (ví dụ, từ cơ sở dữ liệu)
        // Ở đây tôi giả sử bạn có một hàm hoặc biến chứa thông tin tồn kho
        $max_quantity = getMaxQuantityFromDatabase($productId); // Hàm lấy tồn kho sản phẩm

        // Kiểm tra nếu số lượng yêu cầu lớn hơn tồn kho
        if ($quantity > $max_quantity) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Số lượng vượt quá tồn kho'
            ]);
            exit;
        }

        // Kiểm tra nếu sản phẩm tồn tại trong giỏ hàng
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id_sp'] == $productId) {
                $item['SoLuong'] = $quantity; // Cập nhật số lượng sản phẩm
                $found = true;
                break;
            }
        }

        if ($found) {
            // Tính lại tổng tiền
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['SoLuong'] * $item['GiaBan'];
            }

            // Cập nhật tổng tiền vào session
            $_SESSION['tong_tien'] = $total;

            echo json_encode([
                'status' => 'success',
                'total' => $total
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.'
            ]);
        }
        // Hàm lấy số lượng tồn kho từ cơ sở dữ liệu (giả sử bạn đã có bảng hoặc dữ liệu tồn kho)
        function getMaxQuantityFromDatabase($productId)
        {
            function getMaxQuantityFromDatabase($productId) {
                // Kết nối tới cơ sở dữ liệu (giả sử bạn đã có kết nối ở đâu đó)
                global $$link; // $$link là biến kết nối của bạn, nếu dùng PDO hoặc MySQLi
            
                // Truy vấn SQL để lấy số lượng tồn kho của sản phẩm
                $sql = "SELECT SoLuong FROM DonGia WHERE id_sp = ?";
                
                // Sử dụng prepared statement để tránh SQL Injection
                if ($stmt = $$link->prepare($sql)) {
                    // Gắn giá trị cho parameter
                    $stmt->bind_param("i", $productId);
                    
                    // Thực thi câu lệnh
                    $stmt->execute();
                    
                    // Lấy kết quả
                    $stmt->bind_result($maxQuantity);
                    $stmt->fetch();
                    
                    // Đóng statement
                    $stmt->close();
                    
                    // Trả về số lượng tối đa tồn kho
                    return $maxQuantity;
                } else {
                    // Nếu có lỗi, trả về giá trị mặc định là 0
                    return 0;
                }
            }
            
        }

        exit;
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ.']);
        exit;
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
            unset($_SESSION['cart']);
            $_SESSION['tong_tien'] = 0;
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

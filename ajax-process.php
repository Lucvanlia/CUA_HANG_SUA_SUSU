<?php
session_start();
if (isset($_POST['id'])) {
    $tong = 0 ;
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
    }
    
}

// Kết nối cơ sở dữ liệu




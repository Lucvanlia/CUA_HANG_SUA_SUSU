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
        case 'update-quantity':
            // Nhận dữ liệu từ AJAX
            $id_sp = isset($_POST['id_sp']) ? (int) $_POST['id_sp'] : 0;
            $id_dv = isset($_POST['id_dv']) ? (int) $_POST['id_dv'] : 0;
            $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
        
            // Kiểm tra dữ liệu đầu vào
            if ($id_sp <= 0 || $id_dv <= 0 || $quantity < 1) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ.'
                ]);
                exit;
            }
        
            // Kết nối database
            include_once 'config.php'; // Cập nhật đường dẫn file cấu hình
            $query = "SELECT SoLuong FROM DonGia WHERE id_sp = ? AND id_dv = ? AND HoatDong = 0";
            $stmt = $link->prepare($query);
            $stmt->bind_param("ii", $id_sp, $id_dv);
            $stmt->execute();
            $stmt->bind_result($max_quantity);
            $stmt->fetch();
            $stmt->close();
        
            // Kiểm tra tồn kho
            if (!$max_quantity) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sản phẩm hoặc đơn vị đo.'
                ]);
                exit;
            }
        
            if ($quantity > $max_quantity) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Số lượng vượt quá tồn kho!'
                ]);
                exit;
            }
        
            // Kiểm tra giỏ hàng trong session
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Giỏ hàng rỗng hoặc không tồn tại.'
                ]);
                exit;
            }
        
            // Cập nhật giỏ hàng
            $subtotal = 0;
            $total = 0;
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id_sp'] == $id_sp && $item['id_dv'] == $id_dv) {
                    $item['SoLuong'] = $quantity;
                    $item['ThanhTien'] = $quantity * $item['GiaBan'];
                    $subtotal = $item['ThanhTien']; // Thành tiền sản phẩm
                    $found = true;
                    break;
                }
            }
        
            // Nếu không tìm thấy sản phẩm trong giỏ hàng
            if (!$found) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'
                ]);
                exit;
            }
        
            // Tính tổng tiền toàn bộ giỏ hàng
            $total = array_sum(array_map(function ($item) {
                return $item['ThanhTien'];
            }, $_SESSION['cart']));
        
            // Trả về JSON phản hồi
            echo json_encode([
                'status' => 'success',
                'message' => 'Cập nhật số lượng thành công.',
                'subtotal' => number_format($subtotal, 0, ',', '.') . " VNĐ",
                'total' => number_format($total, 0, ',', '.') . " VNĐ"
            ]);
            exit;
        
            case 'get-user-info':
                if (isset($_POST['id_user'])) {
                    $id_user = (int) $_POST['id_user'];
            
                    // Kết nối database và lấy thông tin người dùng
                    $query = "SELECT Ten_kh, Email_kh, SDT_kh, Dchi_kh FROM KhachHang WHERE id_kh = ?";
                    $stmt = $link->prepare($query);
                    $stmt->bind_param("i", $id_user);
                    $stmt->execute();
                    $stmt->bind_result($name, $email, $phone, $address);
                    $stmt->fetch();
                    $stmt->close();
            
                    if ($name) {
                        echo json_encode([
                            'status' => 'success',
                            'name' => $name,
                            'email' => $email,
                            'phone' => $phone,
                            'address' => $address
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'success',
                            'name' => '',
                            'email' => '',
                            'phone' => '',
                            'address' => ''
                        ]);
                    }
                }
                exit;
                case'Check-Out':                
                // Lấy phương thức thanh toán từ form
                $paymentMethod = $_POST['payment_method']; // 'cod' hoặc 'vnpay'
                
                if ($paymentMethod === 'cod') {
                    // Kiểm tra người dùng có đăng nhập chưa (nếu có id_user trong session)
                    $id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
                    $id_ctkm = isset($_SESSION['km']) ? $_SESSION['km'] : '0';
                    // Nếu không có id_user, tạo tài khoản khách hàng mới
                    if (!$id_user) {
                        // Lấy thông tin từ form (người dùng nhập khi thanh toán)
                        $name = $_POST['name'];  // Tên khách hàng
                        $email = $_POST['email'];  // Email khách hàng
                        $address = $_POST['address'];  // Địa chỉ khách hàng
                        $phone = $_POST['phone'];  // Số điện thoại khách hàng
                        $image = 'hinh_kh.jpg';  // Hình ảnh mặc định nếu không có hình ảnh khách hàng
                
                        // Kiểm tra email có tồn tại trong bảng KhachHang không
                        $checkEmailQuery = "SELECT * FROM KhachHang WHERE Email_kh = '$email' and SDT_kh = '$phone'";
                        $result = mysqli_query($link, $checkEmailQuery);
                
                        if (mysqli_num_rows($result) > 0) {
                            // Nếu email đã tồn tại, lấy id_kh của khách hàng đó
                            $user = mysqli_fetch_assoc($result);
                            $id_user = $user['id_kh'];
                        } else {
                            // Nếu email chưa tồn tại, tạo tài khoản khách hàng mới
                            $insertCustomer = "INSERT INTO KhachHang (Ten_kh, Email_kh, Dchi_kh, Hinh_kh, HoatDong) 
                                               VALUES ('$name', '$email', '$address', '$image', 1)";
                            if (mysqli_query($link, $insertCustomer)) {
                                $id_user = mysqli_insert_id($link); // Lấy id_kh của khách hàng mới
                            } else {
                                echo "Không thể tạo tài khoản mới.";
                                exit();
                            }
                        }
                    }
                
                    // Tiến hành thêm hóa đơn và chi tiết hóa đơn
                    mysqli_begin_transaction($link); // Bắt đầu giao dịch
                
                    try {
                        // Tính tổng tiền giỏ hàng
                        $tong_tien = 0;
                        foreach ($_SESSION['cart'] as $item) {
                            $tong_tien += $item['SoLuong'] * $item['GiaBan']; // Tính tổng tiền
                        }
                        // Thêm hóa đơn vào bảng HDB
                        $id_nv = 1; // ID nhân viên mặc định, có thể lấy từ session nếu có
                
                        $insertOrder = "INSERT INTO HDB (id_kh, id_nv, TrangThai, ThanhToan,id_ctkm ) 
                                        VALUES ($id_user, $id_nv, 2, 3,$id_ctkm)";
                        if (!mysqli_query($link, $insertOrder)) {
                            throw new Exception("Không thể thêm hóa đơn.");
                        }
                
                        $id_hdb = mysqli_insert_id($link); // Lấy ID của hóa đơn vừa tạo
                
                        // Thêm chi tiết hóa đơn vào bảng CT_HDB
                        foreach ($_SESSION['cart'] as $item) {
                            $id_sp = $item['id_sp'];
                            $id_dv = $item['id_dv'];
                            $quantity = $item['SoLuong'];
                            $price = $item['GiaBan'];
                            $thanh_tien = $quantity * $price;
                
                            $insertDetail = "INSERT INTO CT_HDB (id_hdb, id_sp, id_dv, SoLuong, DonGia, ThanhTien) 
                                             VALUES ($id_hdb, $id_sp, $id_dv, $quantity, $price, $thanh_tien)";
                            if (!mysqli_query($link, $insertDetail)) {
                                throw new Exception("Không thể thêm chi tiết hóa đơn cho sản phẩm ID: $id_sp.");
                            }
                        
                        }
                        unset($_SESSION['cart']);
                        $_SESSION['tong_tien'] = 0; // Xóa tổng tiền giỏ hàng
                        // Commit giao dịch nếu không có lỗi
                        mysqli_commit($link);
                
                        // Xóa giỏ hàng sau khi đặt hàng thành công
                      
                        // Trả về kết quả thành công
                        echo $paymentMethod === 'cod' ? "success_cod" : "Không thể thanh toán";
                
                    } catch (Exception $e) {
                        // Rollback nếu có lỗi
                        mysqli_rollback($link);
                        echo "Lỗi: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['name'] = $_POST['name'];  // Tên khách hàng
                    $_SESSION['email'] = $_POST['email'];  // Email khách hàng
                    $_SESSION['address']= $_POST['address'];  // Địa chỉ khách hàng
                    $_SESSION['phone'] = $_POST['phone'];  // Số điện thoại khách hàng
                    $image = 'hinh_kh.jpg';  // Hình ảnh mặc định nếu không có hình ảnh khách hàng
                    echo "success_vnpay";
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


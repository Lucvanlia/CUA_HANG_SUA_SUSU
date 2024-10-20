<?php

use Google\Service\CloudSearch\PushItem;    
            // if(isset($_POST['id']))
            // {
            //     require_once'page/ajax-process.php';
            // }
            if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            elseif(isset($_GET['action'])&& $_GET['action'] == 'details' && isset($_GET['id'])){
                    
                require_once'page/chitiet-sp.php';
            } 
            
            if(isset($_GET['action']) && isset($_GET['query']) )
                    {
                        $tam = $_GET['action'];
                        $tam1 = $_GET['query'];
                    }
                    else 
                        {
                            $tam = '';
                            $tam1 = '';
                            include_once"page/Header.php";    
                            include_once"page/Banner-Catory.php";
                            include_once"page/Suggess.php";
                            include_once"page/Product.php";
                            include_once"page/Blogs.php";
                        }
                    // thao tac cho trang chat lieu 
                    if ($tam =='login' && $tam1 == 'them')
                    {
                        include_once"Login-TK.php";

                        
                    }elseif($tam =='dangxuat' && $tam1 =='dangxuat'){
                        if(isset($_SESSION['login-facebook']))
                        {
                            unset($_SESSION['login-facebook']);
                        }
                        
                        if(isset($_SESSION['login-google']))
                        {
                            unset($_SESSION['login-google']);
                        }
                        if(isset($_SESSION['login-user']))
                        {
                            unset($_SESSION['login-user']);
                        }
                        // Chuyển hướng người dùng về trang đăng nhập
                        header("Location: http://localhost/doan_php/");
                    }
                     elseif($tam =='profile' && $tam1 =='profile'){
                            include_once"profile.php";
                    }
                    elseif ($tam == 'profile' && $tam1 == 'orders')
                    {
                        if(isset($_SESSION['login-facebook']) || isset($_SESSION['login']) || isset($_SESSION['login-google']))
                        {
                            require_once"orders.php";
                        }
                        else
                        {
                            header("location: http://banhangviet-tmi.net/doan_php/login-main.php");
                        }
                    }
                    elseif($tam =='checkout' && $tam1 =='checkout'){
                        include_once"cart.php";
                } 
                elseif($tam == 'cart' && $tam1 == 'add')
                {
                    
                    
                echo'123123123123';
                   
                }
 
                elseif($tam =='cart' && $tam1 =='view'){
                    require_once"page/cart-view.php";
                } 
                elseif($tam == 'product' && $tam1=='details')
                {
                    require_once'page/chitiet-sp.php';
                }
                elseif($tam =='cart' && $tam1 =='del-all'){
                    if(isset($_SESSION['cart'])&& count($_SESSION['cart']) )
                    {
                        unset($_SESSION['cart']);
                        header("Location: index.php?action=cart&query=view");
                    }
                } 
                elseif ($tam == 'cart' && $tam1 == 'del-item') {
                    if (isset($_SESSION['cart']) && count($_SESSION['cart']) || isset($_POST['id']))  {
                       if(isset($_GET['id']))
                       {
                        $itemId = $_GET['id'];    
                        foreach ($_SESSION['cart'] as $key => $item) {
                        if ($item['0'] == $itemId) {
                            unset($_SESSION['cart'][$key]);
                            break;
                        }
                    }}
                        // Search for the item in the cart and remove it
                        
                
                        if (isset($_POST['id'])) {
                            $itemId = $_POST['id'];
                    
                            // Kiểm tra nếu giỏ hàng tồn tại
                            if (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
                                // Tìm sản phẩm theo ID và xóa
                                foreach ($_SESSION['cart'] as $key => $item) {
                                    if ($item[0] == $itemId) {
                                        unset($_SESSION['cart'][$key]);
                                        break;
                                    }
                                }
                                
                                // Reset lại mảng giỏ hàng
                                $_SESSION['cart'] = array_values($_SESSION['cart']);
                    
                                // Trả về JSON response thành công
                                echo json_encode(['success' => true]);
                                exit();
                            }
                        }
                        // Trả về lỗi nếu không tìm thấy ID hoặc giỏ hàng trống
                        echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
                        exit();
                    }
                    
                }
              
                elseif ($tam == 'cart' && $tam1 == 'insert') {
                    // echo '123';exit();
                    $tong = 0;
                    $orderItems = [];
                    if (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            $tt = $item[2] * $item[4];
                            $tong += $tt;
                            $orderItems[] = [
                                'product_id' => $item[0], // ID sản phẩm
                                'quantity' => $item[4], // Số lượng
                                'total_price' => $tt // Thành tiền
                            ];
                        }
                    }
                    $id_kh ="";
                    if(isset($_SESSION["login-facebook"]))
                    {
                    $sql2 = "SELECT * FROM  khachhang where facebook_id =". $_SESSION["login-facebook"];
                    $result = mysqli_query($link, $sql2);
                    $count=mysqli_num_rows($result);
                    while ($row=mysqli_fetch_array($result)) 
                    {
                       $id_kh=$row["id_kh"];
                    }
                   }
                        $payment_method = 0;
                        $time = time();
                        $sql = "INSERT INTO hoadon (NgayLapHD, TrangThai, id_kh, pttt, tongtien) VALUES ($time, 0, '$id_kh', '$payment_method', '$tong')";
                    
                        if (mysqli_query($link, $sql)) {
                            $order_id = mysqli_insert_id($link); // Lấy ID đơn hàng vừa tạo
                    
                            // Thêm từng sản phẩm vào bảng order_items
                            foreach ($_SESSION['cart'] as $item) {
                                $sql_item = "INSERT INTO ctiethd (id_hd , id_sp ,SoLuong, dongia) VALUES ('$order_id', '{$item[0]}', '{$item[4]}', '{$item[2]}')";
                                mysqli_query($link, $sql_item);
                            }
                    
                            // Xóa giỏ hàng sau khi hoàn thành đơn hàng
                            unset($_SESSION['cart']);
                            echo '<script>alert("Đặt hàng thành công!"); window.location.href = "index.php";</script>';
                        } else {
                            echo "Lỗi: " . $sql . "<br>" . mysqli_error($link);
                        }
                    }                
            else
             {
                include_once"page/Header.php";    
                include_once"page/Banner-Catory.php";
                include_once"page/Suggess.php";
                include_once"page/Product.php";
                include_once"page/Blogs.php";
             }
            // elseif ($tam =='quanlynhanvien')
            // {
            //     include('');
            // }
            // elseif ($tam =='quanlykhachhang')
            // {
            //     include('');
            // }
            // elseif ($tam =='quanlydanhmucbaiviet')
            // {
            //     include('');
            // }
            // elseif ($tam =='quanlybaiviet')
            // {
            //     include('');
            // }
            
    ?>
</div>
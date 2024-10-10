<?php
            if(isset($_GET['action']) && isset($_GET['query']) )
                    {
                        $tam = $_GET['action'];
                        $tam1 = $_GET['query'];
                    }
                    else 
                        {
                            $tam = '';
                            $tam1 = '';
                        }
                    // thao tac cho trang chat lieu 
                    if ($tam =='login' && $tam1 == 'them')
                    {
                        include_once"Login-TK.php";

                        
                    }elseif($tam =='dangxuat' && $tam1 =='dangxuat'){
                        session_unset();  // Xóa tất cả các biến session
                        session_destroy(); // Hủy toàn bộ session

                        // Chuyển hướng người dùng về trang đăng nhập
                        header("Location: http://localhost/doan_php/");
                    }
                     elseif($tam =='profile' && $tam1 =='profile'){
                            include_once"profile.php";
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
<div class="clear"></div>
<div class="main">

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
                    if ($tam =='quanlychatlieu' && $tam1 == 'them')
                    {
                        include"modul/qlychatlieu/them.php";
                        include"modul/qlychatlieu/lietke.php";
                        
                    }elseif($tam =='quanlychatlieu' && $tam1 =='sua'){
                        include"modul/qlychatlieu/sua.php";
                    }
                    elseif($tam == 'timkiem' && $tam1=='timkiem'){
                        include"modul/mau/timkiem.php";
                }
                    // kt trang chat lieu 

                    //============Trang hang =================
                    elseif($tam =='quanlyhang' && $tam1 =='them'){
                        include"modul/qlyhang/them.php";
                        include"modul/qlyhang/lietke.php";
                    }
                    elseif($tam =='quanlyhang' && $tam1 =='sua'){
                        include"modul/qlyhang/sua.php";
                    }
                    //============ KT Trang hang =================

                    //============Trang xuat xư =================
                    elseif($tam =='quanlyxuatxu' && $tam1 =='them'){
                        include"modul/qlyxuatxu/them.php";
                        include"modul/qlyxuatxu/lietke.php";
                    }
                    elseif($tam =='quanlyxuatxu' && $tam1 =='sua'){
                        include"modul/qlyxuatxu/sua.php";
                    }
                    elseif($tam == 'timkiem' && $tam1=='timkiem_xuatxu'){
                        include"modul/qlyxuatxu/timkiem.php";
                }
                    //============ KT Trang xuat xu =================
                    //============Trang xuat xư =================
                    elseif($tam =='quanlyloai' && $tam1 =='them'){
                        include"modul/qlyloai/them.php";
                        include"modul/qlyloai/lietke.php";
                    }
                    elseif($tam =='quanlyloai' && $tam1 =='sua'){
                        include"modul/qlyloai/sua.php";
                    }
                    elseif($tam == 'timkiem' && $tam1=='timkiem_loai'){
                        include"modul/qlyloai/timkiem.php";
                }
                    //============ KT Trang xuat xu =================
                            //============Trang diadiem =================
                      elseif($tam =='quanlydiadiem' && $tam1 =='them'){
                        include"modul/qlydiadiem/them.php";
                        include"modul/qlydiadiem/lietke.php";
                     }
                    elseif($tam =='quanlydiadiem' && $tam1 =='sua'){
                        include"modul/qlydiadiem/sua.php";
                    }
                    elseif($tam == 'timkiem' && $tam1=='timkiem_diadiem'){
                        include"modul/qlydiadiem/timkiem.php";
                   }
                    //============ KT Trang xuat xu =================
                            //============Trang san pham=================
                            elseif($tam =='quanlysanpham' && $tam1 =='them'){
                                include"modul/qlysanpham/them.php";
                               include"modul/qlysanpham/lietke.php";
                             }
                            elseif($tam =='quanlysanpham' && $tam1 =='sua'){
                                include"modul/qlysanpham/sua.php";
                            }
                            elseif($tam == 'timkiem' && $tam1=='timkiem_sanpham'){
                                include"modul/qlysanpham/timkiem.php";
                           }
                            //============ kt quanlysanpham =================

                            //============ kt quanlytintuc =================
                            elseif($tam =='quanlytintuc' && $tam1 =='them'){
                                include"modul/qlytintuc/them.php";
                               include"modul/qlytintuc/lietke.php";
                             }
                            elseif($tam =='quanlysanpham' && $tam1 =='sua'){
                                include"modul/qlytintuc/sua.php";
                            }
                            elseif($tam == 'timkiem' && $tam1=='timkiem_sanpham'){
                                include"modul/qlytintuc/timkiem.php";
                           }
                            //============ kt quanlytintuc =================

             elseif($tam == 'admin' && $tam1 == 'admin'){
                include('welcome.php');
           }
            else
             {
                 include('welcome.php');
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
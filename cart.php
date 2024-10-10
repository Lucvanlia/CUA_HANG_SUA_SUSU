<?php 

    if(!isset($_SESSION["cart"])) $_SESSION["cart"] =   array();
    $GLOBALS['Change_Cart'] = false ;
    $error = false ; 
    $success = false ; 
    if(isset($_GET['action']))
    {
        function update_cart($link,$add = false)
        {
            foreach($_POST['quantity'] as $id => $quantity)
            {
                if($quantity == 0 )
                {
                    unset($_SESSION['cart'][$id]);
                }
                else
                {
                    if(!isset($_SESSION['cart'][$id]))
                    {
                        $_SESSION['cart'][$id] = 0 ;
                    }
                    var_dump($_SESSION['cart'][$id]);
                    if($add)
                    {
                        $_SESSION['cart'][$id] += $quantity;
                    }
                    else
                    {
                        $_SESSION['cart'][$id] = $quantity;
                    }
                    // check so luongwj sanr pha m
                    $sql = "SELECT SoLuong FROM dmsp WHERE id_sp = ".$id;
                    $addsp= mysqli_query($link,$sql);
                    if($_SESSION['cart'][$id] > $addsp['quantity'])
                         $_SESSION['cart'][$id] > $addsp['quantity'];
                          $GLOBALS['Change_Cart'] = true;
                }
            }
        }
    }   
    
?>